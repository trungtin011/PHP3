@extends('layouts.user')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Web Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .sidebar-item:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), transparent);
            transform: translateX(5px);
            transition: all 0.3s ease;
        }
        .banner-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .banner-shadow:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background: linear-gradient(45deg, #3b82f6, #60a5fa);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #2563eb, #3b82f6);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        .bottom-nav-item {
            transition: all 0.3s ease;
        }
        .bottom-nav-item:hover {
            color: #3b82f6;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-1/5 bg-white p-6 shadow-lg">
            <ul class="space-y-3">
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-mobile-alt text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Điện thoại, Tablet</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-laptop text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Laptop</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-headphones-alt text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Âm thanh</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-camera text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Đồng hồ, Camera</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-blender text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Đồ gia dụng</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-plug text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Phụ kiện</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-desktop text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">PC, Màn hình, Máy in</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-tv text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">TiVi</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-sync-alt text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Thu cũ đổi mới</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-box-open text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Hàng cũ</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-tags text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Khuyến mãi</span>
                </li>
                <li class="flex items-center space-x-3 sidebar-item p-3 rounded-lg">
                    <i class="fas fa-newspaper text-blue-500 text-lg"></i>
                    <span class="text-gray-700 font-medium">Tin công nghệ</span>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-6">
            <div class="flex space-x-6">
                <!-- Main Banner -->
                <div class="w-2/3 bg-white p-6 rounded-xl banner-shadow">
                    <div class="flex justify-between items-center">
                        <div class="space-y-3">
                            <img alt="Samsung Logo" height="50" src="https://nguyencongpc.vn/media/news/3012_laptop-gaming-dang-mua-dau-nam-2024-6.jpg" width="800" class="mb-2"/>
                            <h1 class="text-3xl font-bold text-gray-800">Galaxy A56 | A36 5G <span class="text-blue-500 text-xl">Mới</span></h1>
                            <p class="text-2xl text-blue-500 font-semibold">Giá chỉ từ 7.990.000đ</p>
                            <p class="text-lg text-gray-600 font-medium">Ưu Đãi Mua Kèm</p>
                            <div class="flex space-x-6">
                                <div>
                                    <p class="line-through text-gray-500">1.99 Triệu</p>
                                    <p class="text-blue-500 font-semibold">490k</p>
                                </div>
                                <div>
                                    <p class="line-through text-gray-500">550k</p>
                                    <p class="text-blue-500 font-semibold">99k</p>
                                </div>
                            </div>
                            <pdfd class="text-sm text-gray-600">S-Teacher | S-Student Giảm thêm 6%</p>
                            <button class="btn-primary text-white px-6 py-2 rounded-full font-medium">MUA NGAY</button>
                        </div>
                    </div>
                </div>

                <!-- Side Banners -->
                <div class="w-1/3 space-y-6">
                    <div class="bg-white p-6 rounded-xl banner-shadow">
                        <img alt="Samsung Logo" height="50" src="https://storage.googleapis.com/a1aa/image/ChC0I_y3Q4c33Duy6hnpIT-fXbsjCVA13IHUZFH0Z7o.jpg" width="100" class="mb-2"/>
                        <h2 class="text-xl font-bold text-gray-800">Galaxy M55 5G <span class="text-blue-500 text-sm">Mới</span></h2>
                        <p class="text-lg text-gray-600">12GB - 256GB</p>
                        <p class="text-xl text-blue-500 font-semibold">Giá chỉ 9.19 Triệu</p>
                        <p class="text-sm text-gray-600">Độc Quyền S-Student Giảm thêm 600k</p>
                        <button class="btn-primary text-white px-6 py-2 rounded-full font-medium">MUA NGAY</button>
                    </div>
                    <div class="bg-white p-6 rounded-xl banner-shadow">
                        <img alt="Apple Logo" height="50" src="https://storage.googleapis.com/a1aa/image/4mfzN0cU_NtUNSLyVTaK53w7XBz0ax3NHTyQHEb29x8.jpg" width="100" class="mb-2"/>
                        <h2 class="text-xl font-bold text-gray-800">iMac</h2>
                        <p class="text-lg text-gray-600">Sáng tạo. Đầy màu sắc.</p>
                        <button class="bg-gray-200 text-gray-800 px-6 py-2 rounded-full font-medium hover:bg-gray-300 transition-all">Mua ngay</button>
                    </div>
                    <div class="bg-white p-6 rounded-xl banner-shadow">
                        <h2 class="text-xl font-bold text-gray-800">Laptop</h2>
                        <p class="text-lg text-gray-600">Say Hi! S-Student & S-Teacher</p>
                        <p class="text-sm text-red-500 font-medium">Giảm 3% Tới đa 400k</p>
                        <p class="text-sm text-gray-600">Tặng Balo Cellphones 600K</p>
                        <button class="btn-primary text-white px-6 py-2 rounded-full font-medium">MUA NGAY</button>
                    </div>
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="flex justify-between mt-6 bg-white p-6 rounded-xl shadow-lg">
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">IPHONE 16 PRO MAX <span class="text-red-500">Tặng AirPods 4</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">OPPO FIND N5 <span class="text-red-500">Đặt gạch ngay</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">GALAXY S25 ULTRA <span class="text-red-500">Giá tốt chốt ngay</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium border-b-2 border-blue-500">GALAXY A56 | A36 <span class="text-red-500">Mở bán ưu đãi tốt</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">XIAOMI 15 SERIES <span class="text-red-500">Giá ưu đãi chốt ngay</span></a>
            </div>
        </div>
    </div>
</body>
</html>
@endsection