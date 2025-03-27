<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MagicShop</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* CSS cho footer */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .bg-animated-dark {
            background: linear-gradient(120deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            background-size: 200% 200%;
            animation: gradientFlow 15s ease infinite;
            transition: all 0.4s ease;
        }

        .shadow-3d {
            box-shadow: 0 -15px 40px rgba(0, 0, 0, 0.3), 0 -5px 15px rgba(0, 0, 0, 0.2);
        }

        .footer-glow {
            position: relative;
            overflow: hidden;
        }

        .footer-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #d4af37, transparent);
            animation: shineLine 4s infinite ease-in-out;
        }

        .text-gradient {
            background: linear-gradient(45deg, #d4af37, #e6c774, #ffffff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: textShine 3s ease infinite;
            font-weight: 700;
            letter-spacing: 1.5px;
        }

        .footer-text {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s ease;
        }

        .footer-content:hover .footer-text {
            color: #e6c774;
        }

        .footer-subtitle {
            color: #d4af37;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
            position: relative;
            display: inline-flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .footer-subtitle i {
            transition: transform 0.3s ease;
        }

        .footer-subtitle:hover {
            transform: translateX(5px);
        }

        .footer-subtitle:hover i {
            transform: scale(1.2);
        }

        .footer-subtitle::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 1px;
            background: #d4af37;
            bottom: -6px;
            left: 0;
            transition: width 0.3s ease;
        }

        .footer-subtitle:hover::after {
            width: 100%;
        }

        .footer-detail {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s ease, padding-left 0.3s ease;
        }

        .footer-detail:hover {
            color: #e6c774;
            padding-left: 5px;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease, padding-left 0.3s ease;
        }

        .footer-link:hover {
            color: #e6c774;
            text-decoration: underline;
            padding-left: 5px;
        }

        .social-links {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .footer-glow:hover .social-links {
            opacity: 1;
            transform: translateY(0);
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            color: #d4af37;
            border-radius: 50%;
            text-decoration: none;
            font-size: 1.4rem;
            transition: all 0.4s ease;
        }

        .social-icon:hover {
            background: #d4af37;
            color: #1a1a1a;
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
        }

        /* Keyframes cho animations */
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes textShine {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes shineLine {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }
    </style>
</head>
<body>
    <!-- Nội dung chính có thể thêm vào đây -->
    <div style="flex: 1;"></div> <!-- Spacer để đẩy footer xuống dưới -->

    <footer class="bg-animated-dark text-white text-center py-5 mt-auto shadow-3d footer-glow">
        <div class="container">
            <div class="footer-content">
                <p class="mb-3 fw-light fs-4">
                    <span class="text-gradient">MagicShop</span>
                </p>
                <p class="mb-4 footer-text fs-6">
                    © {{ date('Y') }} MagicShop. All rights reserved.
                </p>
                <div class="footer-info row justify-content-center text-start">
                    <div class="col-md-3 mb-4">
                        <h6 class="footer-subtitle"><i class="fas fa-map-marker-alt me-2"></i> Address</h6>
                        <p class="footer-detail mb-1">123 Magic Street, Dream City</p>
                        <p class="footer-detail mb-1">Postal Code: 12345</p>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="footer-subtitle"><i class="fas fa-phone-alt me-2"></i> Contact</h6>
                        <p class="footer-detail mb-1">Email: contact@magicshop.com</p>
                        <p class="footer-detail mb-1">Phone: +1 (555) 123-4567</p>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="footer-subtitle"><i class="fas fa-link me-2"></i> Quick Links</h6>
                        <p class="footer-detail mb-1"><a href="/products" class="footer-link">Products</a></p>
                        <p class="footer-detail mb-1"><a href="/about" class="footer-link">About Us</a></p>
                    </div>
                    <div class="col-md-3 mb-4">
                        <h6 class="footer-subtitle"><i class="fas fa-clock me-2"></i> Opening Hours</h6>
                        <p class="footer-detail mb-1">Mon - Fri: 9:00 - 18:00</p>
                        <p class="footer-detail mb-1">Sat - Sun: 10:00 - 16:00</p>
                    </div>
                </div>
                <div class="social-links mt-4">
                    <a href="#" class="social-icon mx-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon mx-2"><i class="fab fa-telegram-plane"></i></a>
                    <a href="#" class="social-icon mx-2"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-icon mx-2"><i class="fab fa-whatsapp"></i></a> <!-- Thay thế cho Zalo -->
                    <a href="#" class="social-icon mx-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon mx-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon mx-2"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>