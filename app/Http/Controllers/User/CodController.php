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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

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

        try {
            DB::beginTransaction();

            $cartItemsCollection = collect($cartItems)->map(function ($item) {
                return (object) [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_image' => $item->product_image,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            });

            $order = Order::createOrder(
                Auth::id(),
                $address->address,
                'cod',
                $cartItemsCollection,
                0, // shipping_fee
                session('applied_coupon') ? session('applied_coupon')['discount'] : 0,
                $notes
            );

            if (session('applied_coupon')) {
                $coupon = Coupon::where('code', session('applied_coupon')['code'])->first();
                if ($coupon && $coupon->isValid()) {
                    if ($coupon->used_count >= $coupon->usage_limit) {
                        throw new \Exception("Mã giảm giá {$coupon->code} đã đạt giới hạn sử dụng.");
                    }
                    $order->coupon_code = $coupon->code;
                    $order->save();
                    $coupon->increment('used_count');
                    Log::info("Increased used_count for coupon: {$coupon->code}");
                }
            }

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
            session()->forget('applied_coupon');

            DB::commit();

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
            Log::error("Error processing COD payment: {$e->getMessage()}");
            return redirect()->route('user.checkout')->with('error', 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }
}