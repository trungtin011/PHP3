<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Cart;

class MoMoController extends Controller
{
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $amount = $request->input('amount');
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Kiểm tra tồn kho trước khi tạo đơn hàng
        foreach ($cartItems as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                return redirect()->route('user.checkout')->with('error', "Sản phẩm {$item->product_name} không đủ số lượng trong kho.");
            }
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'address' => 'Địa chỉ mặc định',
                'payment_method' => 'momo',
                'total' => $amount,
                'status' => 'pending',
            ]);

            DB::commit();

            $payUrl = $this->generateMomoUrl($order, $amount);

            Log::info("Redirecting to MoMo: $payUrl");

            return redirect()->away($payUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo đơn hàng MoMo: ' . $e->getMessage());
            return redirect()->route('user.checkout')->with('error', 'Có lỗi xảy ra khi tạo đơn hàng.');
        }
    }

    private function generateMomoUrl($order, $amount)
    {
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $endpoint = env('MOMO_URL', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $returnUrl = route('momo.callback');

        $orderId = $order->id . '-MOMOPAY-' . rand(10000, 99999);
        $orderInfo = "Thanh toán đơn hàng #{$order->id}";
        $requestId = $partnerCode . time();
        $requestType = "payWithATM";
        $extraData = "";

        $rawSignature = "accessKey=$accessKey" .
            "&amount=$amount" .
            "&extraData=$extraData" .
            "&ipnUrl=$returnUrl" .
            "&orderId=$orderId" .
            "&orderInfo=$orderInfo" .
            "&partnerCode=$partnerCode" .
            "&redirectUrl=$returnUrl" .
            "&requestId=$requestId" .
            "&requestType=$requestType";

        $signature = hash_hmac("sha256", $rawSignature, $secretKey);

        $data = [
            "partnerCode" => $partnerCode,
            "accessKey" => $accessKey,
            "requestId" => $requestId,
            "amount" => $amount,
            "orderId" => $orderId,
            "orderInfo" => $orderInfo,
            "redirectUrl" => $returnUrl,
            "ipnUrl" => $returnUrl,
            "extraData" => $extraData,
            "requestType" => $requestType,
            "signature" => $signature,
            "lang" => "vi"
        ];

        $response = $this->execPostRequest($endpoint, json_encode($data));
        $result = json_decode($response, true);

        if (!empty($result['payUrl'])) {
            return $result['payUrl'];
        } else {
            Log::error('MoMo payment URL creation failed', ['response' => $result]);
            abort(500, 'Không thể tạo URL thanh toán MoMo');
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function momoCallback(Request $request)
    {
        $data = $request->all();
        $secretKey = env('MOMO_SECRET_KEY');

        Log::info('MoMo Callback Data: ', $data);

        if (!isset($data['orderId']) || !isset($data['resultCode'])) {
            return redirect()->route('user.checkout')->with('error', 'Dữ liệu callback không hợp lệ');
        }

        $orderIdParts = explode('-MOMOPAY-', $data['orderId']);
        $originalOrderId = $orderIdParts[0];

        $order = Order::find($originalOrderId);
        if (!$order) {
            return redirect()->route('user.checkout')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $rawSignature = "accessKey=" . env('MOMO_ACCESS_KEY') .
            "&amount={$data['amount']}" .
            "&extraData={$data['extraData']}" .
            "&message={$data['message']}" .
            "&orderId={$data['orderId']}" .
            "&orderInfo={$data['orderInfo']}" .
            "&orderType={$data['orderType']}" .
            "&partnerCode={$data['partnerCode']}" .
            "&payType={$data['payType']}" .
            "&requestId={$data['requestId']}" .
            "&responseTime={$data['responseTime']}" .
            "&resultCode={$data['resultCode']}" .
            "&transId={$data['transId']}";

        $calculatedSignature = hash_hmac('sha256', $rawSignature, $secretKey);

        if ($calculatedSignature === $data['signature'] && $data['resultCode'] == '0') {
            $cartItems = Cart::with('product')->where('user_id', $order->user_id)->get();

            if ($cartItems->isEmpty()) {
                $order->status = 'canceled';
                $order->save();
                return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            try {
                DB::beginTransaction();

                // Kiểm tra tồn kho
                foreach ($cartItems as $item) {
                    if (!$item->product || $item->product->stock < $item->quantity) {
                        throw new \Exception("Sản phẩm {$item->product_name} không đủ số lượng trong kho.");
                    }
                }

                // Tạo các mục đơn hàng
                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'product_image' => $item->product_image,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                    ]);
                }

                // Trừ tồn kho
                foreach ($cartItems as $item) {
                    $product = $item->product;
                    $oldStock = $product->stock;
                    $product->stock -= $item->quantity;
                    $product->status = $product->stock > 0 ? 'in_stock' : 'out_of_stock';
                    $product->save();
                    Log::info("Trừ tồn kho sản phẩm ID {$product->id}: từ {$oldStock} xuống {$product->stock}");
                }

                // Cập nhật trạng thái đơn hàng
                $order->status = 'completed';
                $order->save();

                // Xóa giỏ hàng
                Cart::where('user_id', $order->user_id)->delete();

                DB::commit();

                // Gửi email xác nhận
                try {
                    $user = $order->user;
                    Log::info("Bắt đầu gửi mail đến: " . $user->email);

                    Mail::send('emails.order-confirmation', [
                        'user' => $user,
                        'order' => $order,
                        'cartItems' => $cartItems
                    ], function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Xác nhận đơn hàng thành công');
                    });

                    Log::info("Đã gửi mail xác nhận đến: " . $user->email);
                } catch (\Exception $e) {
                    Log::error('Lỗi gửi mail: ' . $e->getMessage());
                }

                return redirect()->route('user.thankyou')->with([
                    'success' => 'Thanh toán thành công!',
                    'order_id' => $order->id
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                $order->status = 'canceled';
                $order->save();
                Log::error('Lỗi xử lý đơn hàng MoMo: ' . $e->getMessage());
                return redirect()->route('user.checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng.');
            }
        } else {
            $order->status = 'canceled';
            $order->save();
            return redirect()->route('user.checkout')->with('error', 'Thanh toán không thành công!');
        }
    }
}