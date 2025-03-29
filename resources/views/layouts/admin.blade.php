<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MagicShop - Admin')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6D28D9; /* Màu tím đậm sang trọng */
            --secondary: #9CA3AF; /* Xám nhạt tinh tế */
            --sidebar-bg: linear-gradient(180deg, #1E3A8A, #1E1E1E); /* Gradient cho sidebar */
            --hover-bg: rgba(255, 255, 255, 0.15); /* Hiệu ứng hover nhẹ */
            --accent: #FBBF24; /* Vàng ánh kim nổi bật */
            --card-bg: #FFFFFF; /* Nền trắng cho nội dung */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #E5E7EB; /* Nền xám nhạt mềm mại */
            line-height: 1.6;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            position: fixed;
            padding: 2.5rem 1.5rem;
            transition: width 0.3s ease;
            z-index: 1000;
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.2);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: opacity 0.3s ease;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .sidebar.collapsed .logo span {
            opacity: 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            color: #D1D5DB;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: var(--hover-bg);
            color: var(--accent);
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar-nav i {
            font-size: 1.3rem;
            width: 22px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .sidebar-nav a:hover i {
            transform: scale(1.2);
        }

        .sidebar.collapsed .sidebar-nav span {
            display: none;
        }

        .content-wrapper {
            margin-left: 260px;
            padding: 2.5rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background: var(--card-bg);
        }

        .content-wrapper.expanded {
            margin-left: 80px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: linear-gradient(90deg, #F9FAFB, #F3F4F6);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .user-profile img:hover {
            transform: scale(1.1);
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            color: #1F2937;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: var(--accent);
            color: #fff;
            border-radius: 8px;
        }

        .toggle-btn {
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: var(--primary);
            color: #fff;
            transform: rotate(90deg);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .content-wrapper {
                margin-left: 80px;
            }
            .sidebar .logo span,
            .sidebar-nav span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>MagicShop</span>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
            <a href="{{ route('admin.products.index') }}"><i class="fa-solid fa-box"></i><span>Products</span></a>
            <a href="{{ route('admin.categories.index') }}"><i class="fa-solid fa-tags"></i><span>Categories</span></a>
            <a href="{{ route('admin.brands.index') }}"><i class="fa-solid fa-building"></i><span>Brands</span></a>
            <a href="#"><i class="fa-solid fa-cart-shopping"></i><span>Orders</span></a>
            <a href="{{ route('admin.users.index') }}"><i class="fa-solid fa-users"></i><span>Users</span></a>
        </div>
    </div>

    <div class="content-wrapper" id="contentWrapper">
        <div class="top-bar">
            <div class="toggle-btn" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </div>
            <div class="user-profile">
                <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/40' }}" alt="Avatar">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('contentWrapper');
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
        }
    </script>
</body>
</html>