<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Cart; // Ensure the Cart model is imported from the correct namespace

class VNPayController extends Controller
{
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $orderId = uniqid(); // Unique transaction reference
        session(['vnp_TxnRef' => $orderId]); // Store transaction reference in session

        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = route('vnpay.callback');

        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $orderId;
        $vnp_Amount = $request->amount * 100; // Convert to VNPay's required format
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

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Log VNPay URL
        Log::info("Redirecting to VNPay: $vnp_Url");

        // Redirect to VNPay
        return redirect()->away($vnp_Url);
    }

    public function callback(Request $request)
    {
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_Amount = $request->input('vnp_Amount') / 100; // Convert to original amount

        if ($vnp_ResponseCode == '00') {
            // Payment successful
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            $order = Order::createOrder(
                Auth::id(),
                'Địa chỉ mặc định',
                'vnpay',
                $cartItems,
                0, // Shipping fee
                0, // Discount
                null // Notes
            );

            // Clear the cart
            Cart::where('user_id', Auth::id())->delete();

            return redirect()->route('user.thankyou')->with('success', 'Thanh toán thành công!');
        } else {
            // Payment failed
            return redirect()->route('user.checkout')->with('error', 'Thanh toán không thành công!');
        }
    }

    public function paymentHistory()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.payment.history', compact('orders'));
    }
}
