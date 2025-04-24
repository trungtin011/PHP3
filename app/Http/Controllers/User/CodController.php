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

class CodController extends Controller
{
    public function placeOrder(Request $request)
    {
        // Validate request
        $request->validate([
            'address_id' => 'required|exists:addresses,id', // Thêm kiểm tra address_id
            'notes' => 'nullable|string|max:500',
        ]);

        // Lấy giỏ hàng
        $cartItems = Cart::with('product', 'variant')->where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Kiểm tra địa chỉ
        $address = Address::where('user_id', Auth::id())->where('id', $request->address_id)->first();
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

        try {
            DB::beginTransaction();

            // Tính tổng và giảm giá
            $total = $cartItems->sum('total');
            $discount = session('applied_coupon')['discount'] ?? 0;
            $couponCode = session('applied_coupon')['code'] ?? null;
            $totalAfterDiscount = $total - $discount;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $request->address_id,
                'address' => $address->address,
                'payment_method' => 'cod',
                'total' => $total,
                'discount' => $discount,
                'total_after_discount' => $totalAfterDiscount,
                'coupon_code' => $couponCode,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Thêm các mục đơn hàng
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id ?? null,
                    'product_name' => $item->product_name,
                    'variant_name' => $item->variant ? $item->variant->name . ': ' . $item->variant->value : null,
                    'product_image' => $item->product_image,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);

                // Giảm tồn kho
                if ($item->variant) {
                    if ($item->variant->stock < $item->quantity) {
                        throw new \Exception("Biến thể {$item->product_name} (ID {$item->variant_id}) không đủ số lượng trong kho.");
                    }
                    $oldStock = $item->variant->stock;
                    $item->variant->decrement('stock', $item->quantity);
                    Log::info("Reduced stock for product ID {$item->product_id}, Variant ID {$item->variant_id}: from {$oldStock} to {$item->variant->stock}");
                } else {
                    if ($item->product->stock < $item->quantity) {
                        throw new \Exception("Sản phẩm {$item->product_name} không đủ số lượng trong kho.");
                    }
                    $oldStock = $item->product->stock;
                    $item->product->decrement('stock', $item->quantity);
                    $item->product->status = $item->product->stock > 0 ? 'in_stock' : 'out_of_stock';
                    $item->product->save();
                    Log::info("Reduced stock for product ID {$item->product_id}: from {$oldStock} to {$item->product->stock}");
                }
            }

            // Xử lý mã giảm giá
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->lockForUpdate()->first();
                if ($coupon && $coupon->isValid()) {
                    if ($coupon->used_count >= $coupon->usage_limit) {
                        throw new \Exception("Mã giảm giá {$coupon->code} đã đạt giới hạn sử dụng.");
                    }
                    $coupon->increment('used_count');
                    Log::info("Increased used_count for coupon: {$coupon->code}, used_count: {$coupon->used_count}");
                } else {
                    Log::warning("Invalid or expired coupon: {$couponCode}");
                }
            }

            // Xóa giỏ hàng và session coupon
            Cart::where('user_id', Auth::id())->delete();
            session()->forget('applied_coupon');

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

            return redirect()->route('user.thankyou')->with([
                'success' => 'Đơn hàng của bạn đã được đặt thành công.',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('COD Order Error: ' . $e->getMessage());
            return redirect()->route('user.checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }
}