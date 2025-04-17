<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MoMoController extends Controller
{
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $amount = $request->input('amount');
        $addressId = $request->input('address_id');
        $notes = $request->input('notes');
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $address = Address::where('user_id', Auth::id())->where('id', $addressId)->first();
        if (!$address) {
            return redirect()->route('user.checkout')->with('error', 'Địa chỉ giao hàng không hợp lệ.');
        }

        foreach ($cartItems as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                return redirect()->route('user.checkout')->with('error', "Sản phẩm {$item->product_name} không đủ số lượng trong kho.");
            }
        }

        $orderData = [
            'user_id' => Auth::id(),
            'address_id' => $addressId,
            'address' => $address->address,
            'cart_items' => $cartItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_image' => $item->product_image,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            })->toArray(),
            'total' => $cartItems->sum('total'),
            'total_after_discount' => $amount,
            'shipping_fee' => 0,
            'discount' => session('applied_coupon_code') ? session('applied_coupon.discount', 0) : 0,
            'coupon_code' => session('applied_coupon_code'),
            'notes' => $notes,
        ];
        session(['order_data' => $orderData]);

        try {
            $payUrl = $this->generateMomoUrl($orderData);
            Log::info("Redirecting to MoMo: $payUrl");
            return redirect()->away($payUrl);
        } catch (\Exception $e) {
            Log::error("Error generating MoMo payment URL: {$e->getMessage()}");
            return redirect()->route('user.checkout')->with('error', 'Không thể tạo URL thanh toán MoMo.');
        }
    }

    private function generateMomoUrl($orderData)
    {
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $endpoint = env('MOMO_URL', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $returnUrl = route('momo.callback');

        $orderId = 'MOMO-' . uniqid();
        session(['momo_order_id' => $orderId]);
        $orderInfo = "Thanh toán đơn hàng #{$orderId}";
        $requestId = $partnerCode . time();
        $amount = $orderData['total_after_discount'];
        $requestType = 'payWithATM';
        $extraData = '';

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

        $signature = hash_hmac('sha256', $rawSignature, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $returnUrl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
            'lang' => 'vi',
        ];

        $response = $this->execPostRequest($endpoint, json_encode($data));
        $result = json_decode($response, true);

        if (!empty($result['payUrl'])) {
            return $result['payUrl'];
        }

        Log::error('MoMo payment URL creation failed', ['response' => $result]);
        throw new \Exception('Không thể tạo URL thanh toán MoMo: ' . ($result['message'] ?? 'Unknown error'));
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('cURL error: ' . curl_error($ch));
            throw new \Exception('Lỗi kết nối tới MoMo: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function momoCallback(Request $request)
    {
        return $this->handleCallback($request);
    }

    public function callback(Request $request)
    {
        return $this->handleCallback($request);
    }

    private function handleCallback(Request $request)
    {
        $data = $request->all();
        Log::info('MoMo Callback Data: ', $data);

        if (!isset($data['orderId']) || !isset($data['resultCode']) || !isset($data['signature'])) {
            Log::error('Invalid MoMo callback data: Missing required fields');
            return redirect()->route('user.checkout')->with('error', 'Dữ liệu callback không hợp lệ.');
        }

        $secretKey = env('MOMO_SECRET_KEY');
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
        if ($calculatedSignature !== $data['signature']) {
            Log::error("Invalid MoMo signature, orderId: {$data['orderId']}");
            return redirect()->route('user.checkout')->with('error', 'Chữ ký thanh toán không hợp lệ.');
        }

        if (session('momo_order_id') !== $data['orderId']) {
            Log::error("Invalid orderId in session, orderId: {$data['orderId']}");
            return redirect()->route('user.checkout')->with('error', 'Mã giao dịch không hợp lệ.');
        }

        if ($data['resultCode'] == '0') {
            $orderData = session('order_data');
            if (!$orderData || $orderData['user_id'] !== Auth::id()) {
                Log::error("Invalid or missing order_data in session, orderId: {$data['orderId']}");
                return redirect()->route('user.checkout')->with('error', 'Dữ liệu đơn hàng không hợp lệ.');
            }

            if (abs($orderData['total_after_discount'] - $data['amount']) > 100) {
                Log::error("Mismatched amount: Session={$orderData['total_after_discount']}, MoMo={$data['amount']}, orderId: {$data['orderId']}");
                return redirect()->route('user.checkout')->with('error', 'Số tiền thanh toán không khớp.');
            }

            try {
                DB::beginTransaction();

                $cartItems = collect($orderData['cart_items'])->map(function ($item) {
                    return (object) $item;
                });

                $order = Order::createOrder(
                    Auth::id(),
                    $orderData['address'],
                    'momo',
                    $cartItems,
                    $orderData['shipping_fee'] ?? 0,
                    $orderData['discount'] ?? 0,
                    $orderData['notes'] ?? null
                );

                if (!empty($orderData['coupon_code'])) {
                    $coupon = Coupon::where('code', $orderData['coupon_code'])->first();
                    if ($coupon && $coupon->isValid()) {
                        if ($coupon->used_count >= $coupon->usage_limit) {
                            throw new \Exception("Mã giảm giá {$coupon->code} đã đạt giới hạn sử dụng.");
                        }
                        $order->coupon_code = $coupon->code;
                        $order->save();
                        $coupon->increment('used_count');
                        Log::info("Increased used_count for coupon: {$coupon->code}, used_count: {$coupon->used_count}");
                    }
                }

                $order->status = 'completed';
                $order->save();

                foreach ($cartItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $oldStock = $product->stock;
                        $product->stock -= $item->quantity;
                        $product->status = $product->stock > 0 ? 'in_stock' : 'out_of_stock';
                        $product->save();
                        Log::info("Reduced stock for product ID {$product->id}: from {$oldStock} to {$product->stock}");
                    }
                }

                Cart::where('user_id', Auth::id())->delete();

                DB::commit();

                try {
                    Mail::send('emails.order_confirmation', ['order' => $order], function ($message) use ($order) {
                        $message->to($order->user->email)->subject('Xác nhận đơn hàng #' . $order->id);
                    });
                    Log::info("Sent order confirmation email for order #{$order->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email for order #{$order->id}: {$e->getMessage()}");
                }

                session()->forget(['order_data', 'momo_order_id', 'applied_coupon_code']);

                return redirect()->route('user.thankyou')->with([
                    'success' => 'Thanh toán thành công! Đơn hàng của bạn đã được đặt.',
                    'order_id' => $order->id,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error processing MoMo payment: {$e->getMessage()}, orderId: {$data['orderId']}");
                return redirect()->route('user.checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
            }
        } else {
            Log::warning("MoMo payment failed, resultCode: {$data['resultCode']}, orderId: {$data['orderId']}");
            return redirect()->route('user.checkout')->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
        }
    }
}