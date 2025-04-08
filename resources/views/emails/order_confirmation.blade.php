<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #ee4d2d;">Cảm ơn bạn đã đặt hàng!</h2>
    <p>Xin chào {{ $order->user->name }},</p>
    <p>Đơn hàng của bạn đã được đặt thành công. Dưới đây là thông tin đơn hàng:</p>
    <ul>
        <li><strong>Mã đơn hàng:</strong> #{{ $order->id }}</li>
        <li><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}</li>
        <li><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</li>
        <li><strong>Tổng tiền:</strong> {{ number_format($order->total, 0, ',', '.') }} đ</li>
    </ul>
    <p>Chúng tôi sẽ xử lý đơn hàng và giao hàng sớm nhất có thể.</p>
    <p style="margin-top: 20px;">Trân trọng,<br>Đội ngũ TinMagic</p>
</body>
</html>
