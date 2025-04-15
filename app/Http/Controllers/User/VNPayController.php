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

class VNPayController extends Controller
{
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $orderId = uniqid();
        session(['vnp_TxnRef' => $orderId]);

        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = route('vnpay.callback');

        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $orderId;
        $vnp_Amount = $request->amount * 100;
        $vnp_Locale = config('vnpay.locale');
        $vnp_IpAddr = $request->ip();
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            "vnp_Version" => config('vnpay.version'),
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => config('vnpay.command'),
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => config('vnpay.currency'),
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $orderId,
        ];

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url .= "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        Log::info("Redirecting to VNPay: $vnp_Url");

        return redirect()->away($vnp_Url);
    }

    public function callback(Request $request)
    {
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_Amount = $request->input('vnp_Amount') / 100;

        if ($vnp_ResponseCode == '00') {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

            if ($cartItems->isEmpty()) {
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

                // Tạo đơn hàng với trạng thái completed
                $order = Order::createOrder(
                    Auth::id(),
                    'Địa chỉ mặc định',
                    'vnpay',
                    $cartItems,
                    0,
                    0,
                    null
                );

                // Đảm bảo trạng thái là completed
                $order->status = 'completed';
                $order->save();

                // Trừ tồn kho
                foreach ($cartItems as $item) {
                    $product = $item->product;
                    $oldStock = $product->stock;
                    $product->stock -= $item->quantity;
                    $product->status = $product->stock > 0 ? 'in_stock' : 'out_of_stock';
                    $product->save();
                    Log::info("Trừ tồn kho sản phẩm ID {$product->id}: từ {$oldStock} xuống {$product->stock}");
                }

                // Xóa giỏ hàng
                Cart::where('user_id', Auth::id())->delete();

                DB::commit();

                // Gửi email xác nhận đơn hàng
                $emailSent = false;
                try {
                    $user = Auth::user();
                    if (!$user->email) {
                        throw new \Exception("Email người dùng không hợp lệ.");
                    }

                    $statusTranslations = [
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'completed' => 'Hoàn thành',
                        'canceled' => 'Đã hủy',
                    ];

                    Log::info("Bắt đầu gửi mail xác nhận đơn hàng #{$order->id} đến: {$user->email}");

                    Mail::send('emails.order-confirmation', [
                        'user' => $user,
                        'order' => $order,
                        'cartItems' => $cartItems,
                        'statusTranslations' => $statusTranslations,
                    ], function ($message) use ($user, $order) {
                        $message->to($user->email)
                                ->subject('Xác nhận đơn hàng thành công #' . $order->id);
                    });

                    $emailSent = true;
                    Log::info("Đã gửi mail xác nhận đơn hàng #{$order->id} đến: {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Lỗi gửi mail xác nhận đơn hàng #{$order->id}: " . $e->getMessage() . " | Stack: " . $e->getTraceAsString());
                }

                return redirect()->route('user.thankyou')->with([
                    'success' => 'Thanh toán thành công!' . ($emailSent ? ' Email xác nhận đã được gửi.' : ' Nhưng không thể gửi email xác nhận.'),
                    'order_id' => $order->id
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Lỗi xử lý thanh toán VNPay: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
                return redirect()->route('user.checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng.');
            }
        } else {
            return redirect()->route('user.checkout')->with('error', 'Thanh toán không thành công!');
        }
    }

    public function paymentHistory()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('user.payment.history', compact('orders'));
    }
}