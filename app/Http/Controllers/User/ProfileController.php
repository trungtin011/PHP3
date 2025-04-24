<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $query = $user->orders()->with('items.product')->orderBy('created_at', 'desc');
        
        // Apply status filter if provided
        if ($request->has('status') && in_array($request->status, ['pending', 'processing', 'completed', 'canceled'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->appends($request->only('status'));
        return view('profile.edit', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15|regex:/^[0-9]{10,15}$/',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'addresses.*' => 'nullable|string|max:255|min:5',
            'default_address' => 'required|integer|min:1',
        ], [
            'name.required' => 'Vui lòng nhập tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'addresses.*.min' => 'Địa chỉ phải có ít nhất 5 ký tự.',
            'default_address.required' => 'Vui lòng chọn một địa chỉ mặc định.',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists(str_replace('/storage/', 'public/', $user->avatar))) {
                Storage::delete(str_replace('/storage/', 'public/', $user->avatar));
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = Storage::url($avatarPath);
        }

        try {
            DB::transaction(function () use ($request, $user, $data) {
                $user->update($data);

                $addresses = collect($request->addresses)->filter()->values();
                if ($addresses->isEmpty()) {
                    throw new \Exception('Vui lòng cung cấp ít nhất một địa chỉ.');
                }

                $defaultIndex = $request->default_address - 1;
                if (!isset($addresses[$defaultIndex])) {
                    throw new \Exception('Địa chỉ mặc định không hợp lệ.');
                }

                $user->addresses()->delete();
                foreach ($addresses as $index => $address) {
                    $user->addresses()->create([
                        'address' => $address,
                        'default' => $index === $defaultIndex,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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
        DB::transaction(function () use ($user, $order, &$outOfStock) {
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
        });

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

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'canceled']);

            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        });

        return redirect()->route('profile.edit')->with('success', 'Đơn hàng đã được hủy thành công.');
    }
}