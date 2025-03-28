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
            --primary: #4F46E5;
            --secondary: #6B7280;
            --sidebar-bg: #1F2937;
            --hover-bg: #374151;
            --accent: #FCD34D;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F3F4F6;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            position: fixed;
            padding: 2rem 1.5rem;
            transition: width 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .logo span {
            opacity: 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: #D1D5DB;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: var(--hover-bg);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar-nav i {
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-nav span {
            display: none;
        }

        .content-wrapper {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content-wrapper.expanded {
            margin-left: 80px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent);
        }

        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            color: #374151;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--accent);
            color: #1F2937;
        }

        .toggle-btn {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
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