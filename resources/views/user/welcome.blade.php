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
        /* Sidebar */
        .sidebar {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            max-height: 85vh;
            overflow-y: auto;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .sidebar-item {
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .sidebar-item:hover {
            background: linear-gradient(90deg, rgba(238, 77, 45, 0.1), transparent);
            transform: translateX(4px);
        }

        /* Banner */
        .banner-shadow {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        .banner-shadow:hover {
            transform: translateY(-4px);
        }
        .btn-primary {
            background: linear-gradient(45deg, #ee4d2d, #ff6b50);
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #d43f21, #ee4d2d);
            box-shadow: 0 6px 15px rgba(238, 77, 45, 0.3);
        }

        /* Bottom Nav */
        .bottom-nav-item {
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .bottom-nav-item:hover {
            color: #ee4d2d;
            background: rgba(238, 77, 45, 0.1);
        }

    
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-1/5 bg-white p-5 shadow-lg sidebar">
            <ul class="space-y-2">
                @foreach ($categories as $parent)
                    <li class="sidebar-item">
                        <div class="flex items-center justify-between cursor-pointer" onclick="toggleCategory(this)">
                            <div class="flex items-center space-x-2">
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
                            <ul class="ml-5 space-y-1 hidden mt-2">
                                @foreach ($parent->children as $child)
                                    <li class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50">
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
            <div class="flex space-x-5">
                <!-- Main Banner -->
                <div class="w-2/3 bg-white p-5 rounded-xl banner-shadow">
                    <div class="space-y-4">
                        <img alt="Laptop Banner" src="https://nguyencongpc.vn/media/news/3012_laptop-gaming-dang-mua-dau-nam-2024-6.jpg" class="rounded-lg" style="max-width: 100%; height: auto;"/>
                        <h1 class="text-2xl font-bold text-gray-800">Laptop Gaming RTX 4060 <span class="text-orange-500 text-lg">Mới</span></h1>
                        <p class="text-xl text-orange-500 font-semibold">Giá từ 19.990.000đ</p>
                        <p class="text-base text-gray-600">Ưu đãi mua kèm:</p>
                        <div class="flex space-x-4">
                            <div>
                                <p class="line-through text-gray-500 text-sm">2.99 Triệu</p>
                                <p class="text-orange-500 font-semibold">990k</p>
                            </div>
                            <div>
                                <p class="line-through text-gray-500 text-sm">1.5 Triệu</p>
                                <p class="text-orange-500 font-semibold">490k</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">S-Teacher | S-Student giảm thêm 5%</p>
                        <button class="btn-primary text-white"><i class="bi bi-cart me-2"></i>MUA NGAY</button>
                    </div>
                </div>
                <!-- Sub Banners -->
                <div class="w-1/3 space-y-5">
                    <div class="bg-white p-5 rounded-xl banner-shadow">
                        <img alt="Laptop Dell" src="https://storage.googleapis.com/a1aa/image/ChC0I_y3Q4c33Duy6hnpIT-fXbsjCVA13IHUZFH0Z7o.jpg" class="rounded-lg" style="max-width: 100%; height: auto;"/>
                        <h2 class="text-lg font-bold text-gray-800 mt-2">Dell XPS 13 <span class="text-orange-500 text-sm">Mới</span></h2>
                        <p class="text-base text-gray-600">16GB - 512GB SSD</p>
                        <p class="text-lg text-orange-500 font-semibold">29.99 Triệu</p>
                        <p class="text-sm text-gray-600">S-Student giảm thêm 1 Triệu</p>
                        <button class="btn-primary text-white"><i class="bi bi-cart me-2"></i>MUA NGAY</button>
                    </div>
                    <div class="bg-white p-5 rounded-xl banner-shadow">
                        <img alt="iMac" src="https://storage.googleapis.com/a1aa/image/4mfzN0cU_NtUNSLyVTaK53w7XBz0ax3NHTyQHEb29x8.jpg" class="rounded-lg" style="max-width: 100%; height: auto;"/>
                        <h2 class="text-lg font-bold text-gray-800 mt-2">iMac M3</h2>
                        <p class="text-base text-gray-600">Sáng tạo. Đầy màu sắc.</p>
                        <button class="bg-gray-200 text-gray-800 px-5 py-2 rounded-full font-medium hover:bg-gray-300 transition-all"><i class="bi bi-cart me-2"></i>MUA NGAY</button>
                    </div>
                </div>
            </div>
            <!-- Bottom Navigation -->
            <div class="mt-6 bg-white p-5 rounded-xl shadow-lg flex justify-between">
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">MACBOOK PRO M4 <span class="text-red-500">Tặng Chuột Magic</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">ASUS ROG ZEPHYRUS <span class="text-red-500">Đặt trước ngay</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium">DELL ALIENWARE <span class="text-red-500">Giá tốt chốt ngay</span></a>
                <a href="#" class="bottom-nav-item text-sm text-gray-700 font-medium border-b-2 border-orange-500">LAPTOP RTX 4060 <span class="text-red-500">Ưu đãi tốt</span></a>
            </div>
        </div>
    </div>

 
</body>
</html>
@endsection