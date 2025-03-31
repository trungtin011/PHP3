<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $price = $product->price; // Giá sản phẩm

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
                'product_sku' => $product->sku ?? null, // Nếu sản phẩm có SKU
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
}
