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

class VNPayController extends Controller
{
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        $amount = $request->input('amount');
        $addressId = $request->input('address_id');
        $notes = $request->input('notes');
        $cartItems = Cart::with('product', 'variant')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Kiểm tra địa chỉ
        $address = Address::where('user_id', Auth::id())->where('id', $addressId)->first();
        if (!$address) {
            return redirect()->route('user.checkout')->with('error', 'Địa chỉ giao hàng không hợp lệ.');
        }

        // Kiểm tra tồn kho
        foreach ($cartItems as $item) {
            $stock = $item->variant ? $item->variant->stock : $item->product->stock;
            if (!$item->product || $stock < $item->quantity) {
                return redirect()->route('user.checkout')->with('error', "Sản phẩm {$item->product_name} không đủ số lượng trong kho.");
            }
        }

        // Lưu order_data vào session
        $orderData = [
            'user_id' => Auth::id(),
            'address_id' => $addressId,
            'address' => $address->address,
            'cart_items' => $cartItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id ?? null,
                    'product_name' => $item->product_name,
                    'product_image' => $item->product_image,
                    'variant_name' => $item->variant ? $item->variant->name . ': ' . $item->variant->value : null,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            })->toArray(),
            'total' => $cartItems->sum('total'),
            'total_after_discount' => $amount,
            'shipping_fee' => 0,
            'discount' => session('applied_coupon') ? session('applied_coupon')['discount'] : 0,
            'coupon_code' => session('applied_coupon') ? session('applied_coupon')['code'] : null,
            'notes' => $notes,
        ];
        session(['order_data' => $orderData]);

        $vnp_TxnRef = uniqid();
        session(['vnp_TxnRef' => $vnp_TxnRef]);

        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = route('vnpay.callback');

        $vnp_OrderInfo = "Thanh toán đơn hàng #{$vnp_TxnRef}";
        $vnp_Amount = $amount * 100;
        $vnp_Locale = config('vnpay.locale', 'vn');
        $vnp_IpAddr = $request->ip();
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            'vnp_Version' => config('vnpay.version', '2.1.0'),
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_Command' => config('vnpay.command', 'pay'),
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_CurrCode' => config('vnpay.currency', 'VND'),
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_Locale' => $vnp_Locale,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => 'billpayment',
            'vnp_ReturnUrl' => $vnp_ReturnUrl,
            'vnp_TxnRef' => $vnp_TxnRef,
        ];

        ksort($inputData);
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $inputData['vnp_SecureHash'] = $vnpSecureHash;

        $vnp_Url .= '?' . http_build_query($inputData);
        Log::info("Redirecting to VNPay: $vnp_Url");

        return redirect()->away($vnp_Url);
    }

    public function callback(Request $request)
    {
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $vnp_Amount = $request->input('vnp_Amount') / 100;
        $vnp_SecureHash = $request->input('vnp_SecureHash');

        Log::info("VNPay callback received: TxnRef=$vnp_TxnRef, ResponseCode=$vnp_ResponseCode, Amount=$vnp_Amount");

        $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');
        ksort($inputData);
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }
        $vnp_HashSecret = config('vnpay.hash_secret');
        $calculatedHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        if ($vnp_SecureHash !== $calculatedHash) {
            Log::error("Invalid VNPay secure hash, TxnRef: $vnp_TxnRef");
            return redirect()->route('user.checkout')->with('error', 'Chữ ký thanh toán không hợp lệ.');
        }

        if (session('vnp_TxnRef') !== $vnp_TxnRef) {
            Log::error("Invalid TxnRef in session, TxnRef: $vnp_TxnRef");
            return redirect()->route('user.checkout')->with('error', 'Mã giao dịch không hợp lệ_listing.php.');
        }

        if ($vnp_ResponseCode === '00') {
            $orderData = session('order_data');
            if (!$orderData || $orderData['user_id'] !== Auth::id()) {
                Log::error("Invalid or missing order_data in session, TxnRef: $vnp_TxnRef");
                return redirect()->route('user.checkout')->with('error', 'Dữ liệu đơn hàng không hợp lệ.');
            }

            if (abs($orderData['total_after_discount'] - $vnp_Amount) > 100) {
                Log::error("Mismatched amount: Session={$orderData['total_after_discount']}, VNPay=$vnp_Amount, TxnRef: $vnp_TxnRef");
                return redirect()->route('user.checkout')->with('error', 'Số tiền thanh toán không khớp.');
            }

            try {
                DB::beginTransaction();

                $cartItems = collect($orderData['cart_items'])->map(function ($item) {
                    return (object) $item;
                });

                // Tạo đơn hàng
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'address_id' => $orderData['address_id'], // Ensure address_id is included
                    'address' => $orderData['address'],
                    'payment_method' => 'vnpay',
                    'total' => $orderData['total'],
                    'discount' => $orderData['discount'],
                    'total_after_discount' => $orderData['total_after_discount'],
                    'shipping_fee' => 0,
                    'notes' => $orderData['notes'],
                    'status' => 'pending',
                ]);

                // Tạo các mục đơn hàng
                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id ?? null,
                        'product_name' => $item->product_name,
                        'variant_name' => $item->variant_name ?? null,
                        'product_image' => $item->product_image,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                    ]);
                }

                // Xử lý mã giảm giá
                if (!empty($orderData['coupon_code'])) {
                    $coupon = Coupon::where('code', $orderData['coupon_code'])->lockForUpdate()->first();
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

                // Cập nhật trạng thái đơn hàng
                $order->status = 'completed';
                $order->save();

                // Cập nhật tồn kho
                foreach ($cartItems as $item) {
                    $product = Product::find($item->product_id);
                    if (!$product) {
                        throw new \Exception("Sản phẩm ID {$item->product_id} không tồn tại.");
                    }

                    if (!empty($item->variant_id)) {
                        $variant = $product->variants()->find($item->variant_id);
                        if (!$variant) {
                            throw new \Exception("Biến thể ID {$item->variant_id} không tồn tại.");
                        }
                        if ($variant->stock < $item->quantity) {
                            throw new \Exception("Biến thể {$item->product_name} (ID {$item->variant_id}) không đủ số lượng trong kho.");
                        }
                        $oldStock = $variant->stock;
                        $variant->stock -= $item->quantity;
                        $variant->save();
                        Log::info("Reduced stock for product ID {$product->id}, Variant ID {$item->variant_id}: from {$oldStock} to {$variant->stock}");
                    } else {
                        if ($product->stock < $item->quantity) {
                            throw new \Exception("Sản phẩm {$item->product_name} không đủ số lượng trong kho.");
                        }
                        $oldStock = $product->stock;
                        $product->stock -= $item->quantity;
                        $product->status = $product->stock > 0 ? 'in_stock' : 'out_of_stock';
                        $product->save();
                        Log::info("Reduced stock for product ID {$product->id}: from {$oldStock} to {$product->stock}");
                    }
                }

                // Xóa giỏ hàng
                Cart::where('user_id', Auth::id())->delete();

                DB::commit();

                // Gửi email xác nhận
                try {
                    Mail::send('emails.order_confirmation', ['order' => $order], function ($message) use ($order) {
                        $message->to($order->user->email)->subject('Xác nhận đơn hàng #' . $order->id);
                    });
                    Log::info("Sent order confirmation email for order #{$order->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email for order #{$order->id}: {$e->getMessage()}");
                }

                // Xóa session
                session()->forget(['order_data', 'vnp_TxnRef']);

                return redirect()->route('user.thankyou')->with([
                    'success' => 'Thanh toán thành công! Đơn hàng của bạn đã được đặt.',
                    'order_id' => $order->id,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error processing VNPay payment: {$e->getMessage()}, TxnRef: $vnp_TxnRef");
                return redirect()->route('user.checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
            }
        } else {
            Log::warning("VNPay payment failed, ResponseCode: $vnp_ResponseCode, TxnRef: $vnp_TxnRef");
            return redirect()->route('user.checkout')->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
        }
    }
}