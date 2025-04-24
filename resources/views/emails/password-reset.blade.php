<!-- resources/views/emails/password-reset.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 30px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background-color: #f4f4f4; padding: 20px; text-align: center; color: white; }
        .content { padding: 30px; }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #f4f4f4;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer { background-color: #f8f8f8; padding: 20px; text-align: center; font-size: 14px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Đặt lại mật khẩu</h2>
        </div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình tại <strong>MagicShop</strong>.</p>
            <p>Vui lòng nhấn vào nút bên dưới để tiến hành đặt lại mật khẩu:</p>
            <div style="text-align: center;">
                <a href="{{ $resetLink ?? '#' }}" class="button">Đặt lại mật khẩu</a>
            </div>
            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua email này. Nếu nghi ngờ có hoạt động đáng ngờ, vui lòng liên hệ với chúng tôi.
            </p>
        </div>
        <div class="footer">
            <p>Trân trọng,<br><strong>Đội ngũ MagicShop</strong></p>
            <p>© {{ date('Y') }} MagicShop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
