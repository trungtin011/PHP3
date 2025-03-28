<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MagicShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        :root {
            --primary: #D4AF37;
            --primary-hover: #E6C774;
            --dark-bg: #1A1A1A;
            --dark-secondary: #2D2D2D;
            --text-light: #F3F4F6;
        }

        .bg-dark-gradient {
            background: linear-gradient(120deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        .text-gradient {
            background: linear-gradient(45deg, var(--primary), var(--primary-hover));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 700;
        }

        .nav-link {
            position: relative;
            padding-bottom: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--dark-bg);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-outline {
            border: 1px solid var(--primary);
            color: var(--text-light);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: var(--dark-bg);
            border-color: var(--primary);
        }

        .search-bar {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            width: 250px;
            color: var(--text-light);
            transition: all 0.3s ease;
        }

        .search-bar:focus {
            outline: none;
            width: 300px;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
        }

        .dropdown-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            color: var(--primary);
        }

        .dropdown-menu {
            background: var(--dark-secondary);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            padding: 10px;
            min-width: 180px;
            animation: dropdownFade 0.25s ease-out;
            transform-origin: top right;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            color: var(--text-light);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: var(--primary);
            color: var(--dark-bg);
            transform: translateX(4px);
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        .location-btn {
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .location-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
</head>
<body>
<header class="bg-dark-gradient text-white py-4 sticky-top">
    <div class="container mx-auto px-4 flex items-center justify-between">
        <!-- Left Section -->
        <div class="flex items-center space-x-6">
            <a href="/" class="flex items-center space-x-2">
                <i class="fa-solid fa-wand-magic-sparkles text-gradient text-2xl"></i>
                <span class="text-2xl font-bold"><span class="text-gradient">Magic</span>Shop</span>
    </a>
            <div class="location-btn flex items-center space-x-2 text-base">
                <i class="fa-solid fa-map-marker-alt"></i>
                <span>HCM</span>
                <i class="fa-solid fa-chevron-down text-sm"></i>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-6">
            <input type="text" class="search-bar" placeholder="Tìm kiếm sản phẩm...">
            <a href="#" class="nav-link text-base hidden lg:block">
                <i class="fa-solid fa-phone-alt mr-1"></i>1800.2097
            </a>
            <a href="#" class="nav-link text-base hidden lg:block">
                <i class="fa-solid fa-store mr-1"></i>Cửa hàng
            </a>
            <a href="#" class="nav-link text-base">
                <i class="fa-solid fa-truck mr-1"></i>Đơn hàng
            </a>
            <a href="#" class="nav-link text-base">
                <i class="fa-solid fa-shopping-bag mr-1"></i>Giỏ hàng
            </a>
            <nav class="hidden md:flex space-x-6">
                <a href="/products" class="nav-link text-base">
                    <i class="fa-solid fa-box-open mr-1"></i>Products
                </a>
                <a href="/index" class="nav-link text-base">
                    <i class="fa-solid fa-info-circle mr-1"></i>About
                </a>
            </nav>
            @auth
                <div class="relative group">
                    <div class="nav-link text-base dropdown-toggle">
                        <i class="fa-solid fa-user mr-1"></i>{{ Auth::user()->name }}
                        <i class="fa-solid fa-chevron-down text-sm ml-1"></i>
                    </div>
                    <div class="absolute right-0 hidden group-hover:block dropdown-menu">
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="fa-solid fa-tachometer-alt"></i> Trang quản trị
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fa-solid fa-user-gear"></i> Profile
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item w-full text-left">
                                <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline text-base">Đăng nhập</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary text-base">Đăng ký</a>
                @endif
            @endauth
        </div>
    </div>
</header>
</body>
</html>