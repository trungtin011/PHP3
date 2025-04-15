<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
    
class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->get();
        return view('profile.edit', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'addresses.*' => 'nullable|string|max:255',
            'default_address' => 'nullable|integer|exists:addresses,id',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $avatarPath;
        }

        $user->update($data);

        $user->addresses()->delete();
        if ($request->addresses) {
            foreach ($request->addresses as $address) {
                if (!empty($address)) {
                    $user->addresses()->create(['address' => $address]);
                }
            }
        }

        if ($request->default_address) {
            $user->addresses()->update(['default' => false]);
            $user->addresses()->where('id', $request->default_address)->update(['default' => true]);
        }

        return redirect()->route('profile.edit')->with('success', 'Hồ sơ đã được cập nhật.');
    }

    public function reorder(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::with('items.product')->findOrFail($orderId);

        if ($order->user_id !== $user->id) {
            return redirect()->route('profile.edit')->with('error', 'Bạn không có quyền mua lại đơn hàng này.');
        }

        $outOfStock = [];
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);

            if (!$product || $product->stock < $item->quantity) {
                $outOfStock[] = $item->product_name;
                continue;
            }

            Cart::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                ],
                [
                    'product_name' => $item->product_name,
                    'product_image' => $item->product_image,
                    'product_sku' => $product->sku ?? null,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                    'total' => $product->price * $item->quantity,
                    'discount' => 0,
                ]
            );
        }

        if (!empty($outOfStock)) {
            return redirect()->route('profile.edit')->with('error', 'Một số sản phẩm đã hết hàng: ' . implode(', ', $outOfStock));
        }

        return redirect()->route('user.cart.index')->with('success', 'Đã thêm sản phẩm từ đơn hàng vào giỏ hàng.');
    }

    public function cancelOrder(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::findOrFail($orderId);

        if ($order->user_id !== $user->id) {
            return redirect()->route('profile.edit')->with('error', 'Bạn không có quyền hủy đơn hàng này.');
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('profile.edit')->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        $order->update(['status' => 'canceled']);

        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        return redirect()->route('profile.edit')->with('success', 'Đơn hàng đã được hủy thành công.');
    }
}