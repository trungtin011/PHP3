<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        if (is_null($product->title) || is_null($product->main_image)) {
            Log::error("Product ID {$productId} has null title or main_image: title={$product->title}, main_image={$product->main_image}");
            return redirect()->back()->with('error', 'Sản phẩm không hợp lệ do thiếu thông tin tên hoặc hình ảnh.');
        }

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            if ($cartItem->quantity + 1 > $product->stock) {
                return redirect()->route('user.cart.index')->with('error', 'Số lượng sản phẩm vượt quá tồn kho.');
            }
            $cartItem->increment('quantity');
            $cartItem->update(['total' => $cartItem->quantity * $product->price]);
        } else {
            if ($product->stock < 1) {
                return redirect()->route('user.cart.index')->with('error', 'Sản phẩm đã hết hàng.');
            }
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'product_name' => $product->title,
                'product_image' => $product->main_image,
                'product_s_cart_item_sku' => $product->sku ?? null,
                'quantity' => 1,
                'price' => $product->price,
                'total' => $product->price,
            ]);
        }

        return redirect()->route('user.cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function update(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        return $this->updateCartItem($cartItem, $request->quantity, false);
    }

    public function updateQuantity(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        return $this->updateCartItem($cartItem, $request->quantity, true);
    }

    protected function updateCartItem(Cart $cartItem, $quantity, $isAjax = false)
    {
        $product = $cartItem->product;

        if ($quantity > $product->stock) {
            $error = 'Số lượng sản phẩm vượt quá tồn kho.';
            return $isAjax
                ? response()->json(['error' => $error], 400)
                : redirect()->route('user.cart.index')->with('error', $error);
        }

        $cartItem->update([
            'quantity' => $quantity,
            'total' => $quantity * $product->price,
        ]);

        $successMessage = 'Giỏ hàng đã được cập nhật.';
        return $isAjax
            ? response()->json([
                'success' => $successMessage,
                'total' => number_format($cartItem->total, 0, ',', '.') . ' đ',
            ])
            : redirect()->route('user.cart.index')->with('success', $successMessage);
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
        $defaultAddress = $addresses->where('default', true)->first();
        if (!$defaultAddress) {
            return redirect()->route('profile.edit')->with('error', 'Vui lòng thiết lập địa chỉ mặc định trước khi thanh toán.');
        }

        $total = $cartItems->sum('total');
        $discount = 0;
        $coupon = null;
        $couponCode = null;

        if ($request->isMethod('post') && $request->has('coupon_code')) {
            $couponCodeInput = trim($request->input('coupon_code'));
            if (empty($couponCodeInput)) {
                session()->forget('applied_coupon');
                return redirect()->route('user.checkout')->with('error', 'Vui lòng nhập mã giảm giá.');
            }

            $coupon = Coupon::where('code', $couponCodeInput)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->discount_type === 'percentage'
                    ? $total * ($coupon->discount_value / 100)
                    : $coupon->discount_value;
                $couponCode = $coupon->code;
                session(['applied_coupon' => [
                    'code' => $coupon->code,
                    'discount' => $discount,
                ]]);
                $request->session()->flash('success', 'Mã giảm giá đã được áp dụng.');
            } else {
                session()->forget('applied_coupon');
                $request->session()->flash('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
            }
        }

        $totalAfterDiscount = round($total - $discount);

        return view('user.checkout.index', compact(
            'cartItems',
            'total',
            'discount',
            'totalAfterDiscount',
            'addresses',
            'defaultAddress',
            'coupon',
            'couponCode'
        ));
    }

    public function placeOrder(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        foreach ($cartItems as $item) {
            if (!$item->product || $item->product->stock < $item->quantity) {
                return redirect()->route('user.checkout')->with('error', "Sản phẩm {$item->product_name} không đủ số lượng trong kho.");
            }
        }

        $addressId = $request->input('address_id');
        if (!$addressId) {
            Log::warning("No address_id provided in request");
            return redirect()->route('user.checkout')->with('error', 'Vui lòng chọn địa chỉ giao hàng.');
        }

        $address = Address::where('user_id', Auth::id())->where('id', $addressId)->first();
        if (!$address) {
            Log::warning("Invalid address_id: $addressId");
            return redirect()->route('user.checkout')->with('error', 'Địa chỉ giao hàng không hợp lệ.');
        }

        $total = $cartItems->sum('total');
        $discount = 0;
        $couponCode = null;
        if (session('applied_coupon')) {
            $coupon = Coupon::where('code', session('applied_coupon.code'))->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->discount_type === 'percentage'
                    ? $total * ($coupon->discount_value / 100)
                    : $coupon->discount_value;
                $couponCode = $coupon->code;
            } else {
                session()->forget('applied_coupon');
            }
        }

        $totalAfterDiscount = round($total - $discount);
        $notes = $request->input('notes', '');

        $orderData = [
            'user_id' => Auth::id(),
            'address' => $address->address,
            'address_id' => $address->id,
            'payment_method' => $request->input('payment_method', 'cod'),
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
            'total' => $total,
            'discount' => $discount,
            'total_after_discount' => $totalAfterDiscount,
            'notes' => $notes,
            'coupon_code' => $couponCode,
        ];

        session(['order_data' => $orderData]);
        Log::info("Prepared order_data for payment: ", $orderData);

        $paymentMethod = $orderData['payment_method'];
        switch ($paymentMethod) {
            case 'cod':
                return redirect()->route('user.order.place');
            case 'momo':
                return redirect()->route('momo.payment')->with('amount', $totalAfterDiscount);
            case 'vnpay':
                return redirect()->route('vnpay.payment')->with('amount', $totalAfterDiscount);
            default:
                return redirect()->route('user.checkout')->with('error', 'Phương thức thanh toán không hợp lệ.');
        }
    }

    public function thankYou()
    {
        return view('user.thankyou');
    }
}