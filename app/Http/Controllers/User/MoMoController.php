<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // Tạo đơn hàng tạm (chưa có order item)
        $order = Order::create([
            'user_id' => Auth::id(),
            'address' => 'Địa chỉ mặc định',
            'payment_method' => 'momo',
            'total' => $amount,
            'status' => 'pending',
        ]);

        $payUrl = $this->generateMomoUrl($order, $amount);

        Log::info("Redirecting to MoMo: $payUrl");

        return redirect()->away($payUrl);
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

        // Tách orderId gốc
        $orderIdParts = explode('-MOMOPAY-', $data['orderId']);
        $originalOrderId = $orderIdParts[0];

        // Tìm lại đơn hàng
        $order = Order::find($originalOrderId);
        if (!$order) {
            return redirect()->route('user.checkout')->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Kiểm tra chữ ký hợp lệ
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
            // Thanh toán thành công

            $cartItems = Cart::with('product')->where('user_id', $order->user_id)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            // Tạo OrderItem
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name, // ❌ bị null ở đây
                    'product_image' => $item->product_image,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }
            

            // Cập nhật trạng thái
            $order->status = 'pending';
            $order->save();

            // Xoá giỏ hàng
            Cart::where('user_id', $order->user_id)->delete();

            return redirect()->route('user.thankyou')->with('success', 'Thanh toán thành công!');
        } else {
            return redirect()->route('user.checkout')->with('error', 'Thanh toán không thành công!');
        }
    }
}
