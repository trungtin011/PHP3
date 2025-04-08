<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        $statusTranslations = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'canceled' => 'Đã hủy',
        ];
        return view('admin.orders.show', compact('order', 'statusTranslations'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,canceled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send email notification if the status has changed
        if ($oldStatus !== $order->status) {
            Mail::send('emails.order_status_update', ['order' => $order], function ($message) use ($order) {
                $message->to($order->user->email)
                        ->subject('Cập nhật trạng thái đơn hàng #' . $order->id);
            });
        }

        return redirect()->route('admin.orders.show', $id)->with('success', 'Trạng thái đơn hàng đã được cập nhật và email đã được gửi.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công.');
    }
}
