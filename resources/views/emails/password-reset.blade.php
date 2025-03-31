<!-- resources/views/emails/password-reset.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4a90e2; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Đặt lại mật khẩu</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <p style="margin: 0 0 15px; font-size: 16px;">Xin chào,</p>
                            <p style="margin: 0 0 15px; font-size: 16px; line-height: 1.5;">
                                Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình tại MagicShop. 
                                Vui lòng nhấn vào nút bên dưới để tiến hành đặt lại mật khẩu:
                            </p>
                            
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ $resetLink ?? '#' }}" 
                                           style="background-color: #4a90e2; color: #ffffff; padding: 12px 24px; text-decoration: none; 
                                                  border-radius: 4px; display: inline-block; font-size: 16px; font-weight: bold;">
                                            Đặt lại mật khẩu
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        
                            <p style="margin: 0 0 15px; font-size: 14px; color: #666; line-height: 1.5;">
                                Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi 
                                nếu bạn nghi ngờ có hoạt động đáng ngờ.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px; text-align: center; background-color: #f8f8f8; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                            <p style="margin: 0; font-size: 14px; color: #666;">
                                Trân trọng,<br>
                                <strong>Đội ngũ MagicShop</strong>
                            </p>
                            <p style="margin: 10px 0 0; font-size: 12px; color: #999;">
                                © {{ date('Y') }} MagicShop. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>