<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f8f8; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Xác nhận đơn hàng</h2>
        </div>
        <div class="content">
            <p>Xin chào {{ $order->user->name }},</p>
            <p>Cảm ơn bạn đã đặt hàng! Dưới đây là chi tiết đơn hàng của bạn:</p>
            <h3>Đơn hàng #{{ $order->id }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2) }} VND</td>
                            <td>{{ number_format($item->total, 2) }} VND</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Tổng cộng: {{ number_format($order->total, 2) }} VND</p>
            <p>Phương thức thanh toán: {{ $order->payment_method }}</p>
            <p>Địa chỉ giao hàng: {{ $order->address }}</p>
            <p>Trạng thái: 
                @php
                    $statusTranslations = [
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'completed' => 'Hoàn thành',
                        'canceled' => 'Đã hủy',
                    ];
                    echo $statusTranslations[$order->status] ?? $order->status;
                @endphp
            </p>
            <p>Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất có thể!</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Your Store Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>