<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('user.cart.index', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

        $price = $product->price; 

        if ($cartItem) {
            if ($cartItem->quantity + 1 > $product->stock) {
                return redirect()->route('user.cart.index')->with('error', 'Số lượng sản phẩm vượt quá tồn kho.');
            }
            $cartItem->increment('quantity');
            $cartItem->update([
                'total' => $cartItem->quantity * $price,
            ]);
        } else {
            if ($product->stock < 1) {
                return redirect()->route('user.cart.index')->with('error', 'Sản phẩm đã hết hàng.');
            }
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'product_name' => $product->title,
                'product_image' => $product->main_image,
                'product_sku' => $product->sku ?? null, 
                'quantity' => 1,
                'price' => $price,
                'total' => $price,
            ]);
        }

        return redirect()->route('user.cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function update(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $product = $cartItem->product;

        if ($request->quantity > $product->stock) {
            return redirect()->route('user.cart.index')->with('error', 'Số lượng sản phẩm vượt quá tồn kho.');
        }

        $price = $product->price;

        $cartItem->update([
            'quantity' => $request->quantity,
            'total' => $request->quantity * $price,
        ]);

        return redirect()->route('user.cart.index')->with('success', 'Giỏ hàng đã được cập nhật.');
    }

    public function updateQuantity(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $product = $cartItem->product;

        if ($request->quantity > $product->stock) {
            return response()->json(['error' => 'Số lượng sản phẩm vượt quá tồn kho.'], 400);
        }

        $price = $product->price;

        $cartItem->update([
            'quantity' => $request->quantity,
            'total' => $request->quantity * $price,
        ]);

        return response()->json([
            'success' => 'Giỏ hàng đã được cập nhật.',
            'total' => number_format($cartItem->total, 0, ',', '.') . ' đ',
        ]);
    }

    public function remove($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->delete();

        return redirect()->route('user.cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

    public function checkout(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $addresses = Auth::user()->addresses;
        $total = $cartItems->sum('total');
        $discount = 0;

        if ($request->has('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if ($coupon && $coupon->isValid()) {
                if ($coupon->discount_type === 'percentage') {
                    $discount = $total * ($coupon->discount_value / 100);
                } elseif ($coupon->discount_type === 'fixed') {
                    $discount = $coupon->discount_value;
                }
            } else {
                return redirect()->route('user.checkout')->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
            }
        }

        $totalAfterDiscount = $total - $discount;

        return view('user.checkout.index', compact('cartItems', 'total', 'discount', 'totalAfterDiscount', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $order = \App\Models\Order::createOrder(
            Auth::id(),
            $request->input('address', 'Địa chỉ mặc định'),
            $request->input('payment_method', 'COD'),
            $cartItems,
            0, // Shipping fee
            0, // Discount
            $request->input('notes') // Notes
        );

        // Clear the cart
        Cart::where('user_id', Auth::id())->delete();

        // Send email confirmation
        Mail::send('emails.order_confirmation', ['order' => $order], function ($message) use ($order) {
            $message->to($order->user->email)
                    ->subject('Xác nhận đơn hàng #' . $order->id);
        });

        return redirect()->route('user.thankyou')->with('success', 'Đơn hàng của bạn đã được đặt thành công.');
    }

    public function thankYou()
    {
        return view('user.thankyou');
    }
}
