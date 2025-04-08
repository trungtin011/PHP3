<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật trạng thái đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #ee4d2d;">Cập nhật trạng thái đơn hàng</h2>
    <p>Xin chào {{ $order->user->name }},</p>
    <p>Trạng thái đơn hàng của bạn đã được cập nhật:</p>
    <ul>
        <li><strong>Mã đơn hàng:</strong> #{{ $order->id }}</li>
        <li><strong>Trạng thái mới:</strong> 
            @php
                $statusTranslations = [
                    'pending' => 'Chờ xử lý',
                    'processing' => 'Đang xử lý',
                    'completed' => 'Hoàn thành',
                    'canceled' => 'Đã hủy',
                ];
            @endphp
            {{ $statusTranslations[$order->status] ?? $order->status }}
        </li>
        <li><strong>Ngày cập nhật:</strong> {{ now()->format('Y-m-d H:i') }}</li>
    </ul>
    <p>Chúng tôi sẽ tiếp tục xử lý đơn hàng của bạn. Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
    <p style="margin-top: 20px;">Trân trọng,<br>Đội ngũ TinMagic</p>
</body>
</html>
