@extends('layouts.user')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Trang Chủ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar-item:hover {
            background: linear-gradient(90deg, rgba(238, 77, 45, 0.1), transparent);
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
            background: linear-gradient(45deg, #ee4d2d, #ff6b50);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #d43f21, #ee4d2d);
            box-shadow: 0 5px 15px rgba(238, 77, 45, 0.4);
        }
        .bottom-nav-item {
            transition: all 0.3s ease;
        }
        .bottom-nav-item:hover {
            color: #ee4d2d;
            transform: translateY(-2px);
        }
        .sidebar {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-1/5 bg-white p-6 shadow-lg sidebar">
            <ul class="space-y-3">
                @foreach ($categories as $parent)
                    <li class="sidebar-item p-3 rounded-lg">
                        <div class="flex items-center justify-between cursor-pointer" onclick="toggleCategory(this)">
                            <div class="flex items-center space-x-3">
                                @if ($parent->icon)
                                    <i class="{{ $parent->icon }} text-orange-500 text-lg"></i>
                                @else
                                    <i class="fas fa-folder text-orange-500 text-lg"></i>
                                @endif
                                <span class="text-gray-700 font-medium">{{ $parent->name }}</span>
                            </div>
                            @if ($parent->children->isNotEmpty())
                                <i class="fas fa-chevron-down text-gray-500 text-sm transition-transform duration-300"></i>
                            @endif
                        </div>
                        @if ($parent->children->isNotEmpty())
                            <ul class="ml-6 space-y-2 hidden mt-2">
                                @foreach ($parent->children as $child)
                                    <li class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                                        @if ($child->icon)
                                            <i class="{{ $child->icon }} text-orange-400 text-sm"></i>
                                        @else
                                            <i class="fas fa-folder text-orange-400 text-sm"></i>
                                        @endif
                                        <span class="text-gray-600 font-medium">{{ $child->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-6">
            <div class="flex space-x-6">
                <!-- Banner chính -->
                <div class="w-2/3 bg-white p-6 rounded-xl banner-shadow">
                    <div class="flex justify-between items-center">
                        <div class="space-y-4">
                            <img alt="Laptop Banner" src="https://nguyencongpc.vn/media/news/3012_laptop-gaming-dang-mua-dau-nam-2024-6.jpg" class="mb-2 rounded-lg" style="max-width: 100%; height: auto;"/>
                            <h1 class="text-3xl font-bold text-gray-800">Laptop Gaming RTX 4060 <span class="text-orange-500 text-xl">Mới</span></h1>
                            <p class="text-2xl text-orange-500 font-semibold">Giá chỉ từ 19.990.000đ</p>
                            <p class="text-lg text-gray-600 font-medium">Ưu Đãi Mua Kèm</p>
                            <div class="flex space-x-6">
                                <div>
                                    <p class="line-through text-gray-500">2.99 Triệu</p>
                                    <p class="text-orange-500 font-semibold">990k</p>
                                </div>
                                <div>
                                    <p class="line-through text-gray-500">1.5 Triệu</p>
                                    <p class="text-orange-500 font-semibold">490k</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">S-Teacher | S-Student Giảm thêm 5%</p>
                            <button class="btn-primary text-white px-6 py-2 rounded-full font-medium"><i class="bi bi-cart me-2"></i>MUA NGAY</button>
                        </div>
                    </div>
                </div>
                <!-- Banner phụ -->
                <div class="w-1/3 space-y-6">
                    <div class="bg-white p-6 rounded-xl banner-shadow">
                        <img alt="Laptop Dell" src="https://storage.googleapis.com/a1aa/image/ChC0I_y3Q4c33Duy6hnpIT-fXbsjCVA13IHUZFH0Z7o.jpg" class="mb-2 rounded-lg" style="max-width: 100%; height: auto;"/>
                        <h2 class="text-xl font-bold text-gray-800">Dell XPS 13 <span class="text-orange-500 text-sm">Mới</span></h2>
                        <p class="text-lg text-gray-600">16GB - 512GB SSD</p>
                        <p class="text-xl text-orange-500 font-semibold">Giá chỉ 29.99 Triệu</p>
                        <p class="text-sm text-gray-600">Độc Quyền S-Student Giảm thêm 1 Triệu</p>
                        <button class="btn-primary text-white px-6 py-2 rounded-full font-medium"><i class="bi bi-cart me-2"></i>MUA NGAY</button>
                    </div>
                    <div class="bg-white p-6 rounded-xl banner-shadow">
                        <img alt="iMac" src="https://storage.googleapis.com/a1aa/image/4mfzN0cU_NtUNSLyVTaK53w7XBz0ax3NHTyQHEb29x8.jpg" class="mb-2 rounded-lg" style="max-width: 100%; height: auto;"/>
                        <h2 class="text-xl font-bold text-gray-800">iMac M3</h2>
                        <p class="text-lg text-gray-600">Sáng tạo. Đầy màu sắc.</p>
                        <button class="bg-gray-200 text-gray-800 px-6 py-2 rounded-full font-medium hover:bg-gray-300 transition-all"><i class="bi bi-cart me-2"></i>MUA NGAY</button>
                    </div>
                    <div class="bg-white p-6 rounded-xl banner-shadow">
                        <h2 class="text-xl font-bold text-gray-800">PC Gaming</h2>
                        <p class="text-lg text-gray-600">Say Hi! S-Student & S-Teacher</p>
                        <p class="text-sm text-red-500 font-medium">Giảm 4% Tối đa 800k</p>
                        <p class="text-sm text-gray-600">Tặng Bàn Phím Cơ 1 Triệu</p>
                        <button class="btn-primary text-white px-6 py-2 rounded-full font-medium"><i class="bi bi-cart me-2"></i>MUA NGAY</button>
                    </div>
                </div>
            </div>
            <!-- Bottom Navigation -->
            <div class="flex justify-between mt-6 bg-white p-6 rounded-xl shadow-lg">
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">MACBOOK PRO M4 <span class="text-red-500">Tặng Chuột Magic</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">ASUS ROG ZEPHYRUS <span class="text-red-500">Đặt trước ngay</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">DELL ALIENWARE <span class="text-red-500">Giá tốt chốt ngay</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium border-b-2 border-orange-500">LAPTOP RTX 4060 <span class="text-red-500">Mở bán ưu đãi tốt</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">HP OMEN SERIES <span class="text-red-500">Giá ưu đãi chốt ngay</span></a>
            </div>
        </div>
    </div>

    <!-- JavaScript for toggling -->
    <script>
        function toggleCategory(element) {
            const childList = element.nextElementSibling;
            const chevron = element.querySelector('.fa-chevron-down');
            if (childList) {
                childList.classList.toggle('hidden');
                chevron.classList.toggle('rotate-180');
            }
        }
    </script>
</body>
</html>
@endsection