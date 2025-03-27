<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MagicShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .bg-dark-gradient {
            background: linear-gradient(90deg, #1a1a1a, #2d2d2d);
            background-size: 200% 200%;
            animation: gradientFlow 8s ease infinite;
        }
        .text-gradient {
            background: linear-gradient(45deg, #d4af37, #e6c774);
            color: transparent;
        }
        .hover-glow:hover {
            color: #e6c774;
            text-shadow: 0 0 5px rgba(212, 175, 55, 0.5);
            transition: all 0.2s ease;
        }
        .btn {
            padding: 5px 14px;
            border: 1px solid #d4af37;
            border-radius: 16px;
            transition: all 0.2s ease;
            color: white;
        }
        .btn:hover {
            background: #d4af37;
            color: #1a1a1a;
        }
        .btn-login {
            background: transparent;
        }
        .btn-register {
            background: #d4af37;
            color: #1a1a1a;
        }
        .btn-register:hover {
            background: #e6c774;
            color: #1a1a1a;
        }
        .search-bar {
            background: #333;
            border: none;
            border-radius: 16px;
            padding: 7px 14px;
            width: 200px;
            color: white;
            font-size: 1.1rem;
        }
        .dropdown-menu {
            background: #2d2d2d;
            border: none;
            border-radius: 8px;
        }
        .dropdown-item:hover {
            background: #d4af37;
            color: #1a1a1a;
        }
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body>
<header class="bg-dark-gradient text-white py-3 sticky-top">
    <div class="container flex items-center justify-between">
        <!-- Left -->
        <div class="flex items-center space-x-4">
            <a href="/" class="flex items-center hover-glow">
                <span class="text-2xl font-bold ml-2"><span class="text-gradient">Magic</span>Shop</span>
            </a>
            <button class="btn text-lg">Danh mục</button>
            <div class="flex items-center space-x-1 bg-gray-700 px-3 py-1 rounded">
                <i class="fas fa-map-marker-alt"></i>
                <span class="text-base">HCM</span>
                <i class="fas fa-caret-down"></i>
            </div>
            <nav class="flex space-x-4">
                <a href="/products" class="hover-glow text-base">Products</a>
                <a href="/index" class="hover-glow text-base">About</a>
            </nav>
        </div>

        <!-- Right -->
        <div class="flex items-center space-x-7">
            <input type="text" class="search-bar" placeholder="Tìm kiếm">
            <a href="#" class="hover-glow text-base"><i class="fas fa-phone-alt mr-1"></i>1800.2097</a>
            <a href="#" class="hover-glow text-base"><i class="fas fa-map-marker-alt mr-1"></i>Cửa hàng</a>
            <a href="#" class="hover-glow text-base"><i class="fas fa-truck mr-1"></i>Đơn hàng</a>
            <a href="#" class="hover-glow text-base"><i class="fas fa-shopping-bag mr-1"></i>Giỏ hàng</a>
            @auth
                <div class="relative group">
                    <a href="#" class="hover-glow text-base"><i class="fas fa-user mr-1"></i>{{ Auth::user()->name }}</a>
                    <div class="absolute right-0 hidden group-hover:block dropdown-menu p-2">
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="block text-white hover:bg-yellow-600 p-1 text-base">Trang quản trị</a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="block text-white hover:bg-yellow-600 p-1 text-base">Profile</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block text-white hover:bg-yellow-600 p-1 text-base w-full text-left">Đăng xuất</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-login text-base">Đăng nhập</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-register text-base">Đăng ký</a>
                @endif
            @endauth
        </div>
    </div>
</header>
</body>
</html>