<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MagicShop - Admin')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: #eef2f7;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        .sidebar {
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #1f2a44 0%, #141b2d 100%);
            color: #ffffff;
            padding: 40px 25px;
            position: fixed;
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.15);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar .logo {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 50px;
            color: #ffd700;
            text-align: center;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 12px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            font-weight: 500;
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .sidebar a:hover {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.2) 0%, rgba(255, 215, 0, 0) 100%);
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar a::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 6px;
            height: 0;
            background: #ffd700;
            transition: all 0.4s ease;
            transform: translateY(-50%);
        }

        .sidebar a:hover::after {
            height: 100%;
        }

        .content-area {
            margin-left: 280px;
            padding: 40px;
            min-height: 100vh;
            background: #eef2f7;
            position: relative;
        }

        .user-dropdown {
            position: absolute;
            top: 15px;
            right: 30px;
            display: flex;
            align-items: center;
        }

        .user-dropdown img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
        }

        .user-dropdown .dropdown-toggle {
            background: linear-gradient(135deg, #ffd700, #e6c774);
            color: #1f2a44;
            font-weight: 500;
            border: none;
            padding: 7px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.4s ease;
        }

        .user-dropdown .dropdown-toggle:hover {
            background: linear-gradient(135deg, #e6c774, #ffd700);
            color: #141b2d;
        }

        .dropdown-menu {
            background: #1f2a44;
            border: none;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu .dropdown-item {
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .dropdown-menu .dropdown-item:hover {
            background: #ffd700;
            color: #1f2a44;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .sidebar {
            animation: fadeIn 0.5s ease-in;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">MagicShop</div>
        <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i> Products</a>
        <a href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Categories</a>
        <a href="{{ route('admin.brands.index') }}"><i class="bi bi-building"></i> Brands</a>
        <a href="#"><i class="bi bi-cart4"></i> Orders</a>
        <a href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Users</a>
    </div>
    <div class="content-area">
        <div class="user-dropdown">
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/30' }}" alt="Avatar">
                    Hello, {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-5">
            @yield('content')
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>