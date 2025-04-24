<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật trạng thái đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f8f8; padding: 15px; text-align: center; border-bottom: 1px solid #ddd; }
        .content { padding: 20px; background-color: #fff; }
        .footer { font-size: 12px; color: #777; text-align: center; padding: 10px; border-top: 1px solid #ddd; }
        ul { padding-left: 20px; }
        li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cập nhật trạng thái đơn hàng</h2>
        </div>
        <div class="content">
            <p>Xin chào {{ $order->user->name }},</p>
            <p>Chúng tôi xin thông báo rằng trạng thái đơn hàng của bạn đã được cập nhật như sau:</p>
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
            <p>Nếu bạn có bất kỳ câu hỏi nào về đơn hàng, đừng ngần ngại liên hệ với chúng tôi.</p>
            <p style="margin-top: 20px;">Trân trọng,<br>Đội ngũ TinMagic</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} TinMagic. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
