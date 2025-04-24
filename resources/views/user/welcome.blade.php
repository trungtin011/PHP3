@extends('layouts.user')

@section('content')
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #f97316;
            border-radius: 3px;
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 1rem;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .sidebar-item {
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .sidebar-item:hover {
            background: #fff7ed;
            transform: translateX(4px);
        }
        .sidebar-item.active {
            background: #fef3c7;
            border-left: 3px solid #f97316;
        }

        /* Banner */
        .banner {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            border-radius: 16px; /* Increased border radius */
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); /* Enhanced shadow */
        }
        .banner img {
            transition: transform 0.5s ease, opacity 0.3s ease; /* Added opacity transition */
            height: 220px; /* Adjusted height */
            object-fit: cover;
            width: 100%;
        }
        .banner:hover img {
            transform: scale(1.08); /* Increased scale effect */
            opacity: 0.9; /* Slight opacity change */
        }
        .btn-shop {
            background: #f97316;
            color: white;
            border-radius: 50px;
            padding: 12px 24px; /* Adjusted padding */
            font-weight: 700; /* Increased font weight */
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-shop:hover {
            background: #d97706; /* Slightly darker hover color */
            transform: translateY(-4px); /* Increased hover lift */
        }

        /* Product Card */
        .product-card {
            background: #fff;
            border-radius: 16px; /* Increased border radius */
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-width: 260px; /* Adjusted minimum width */
        }
        .product-card:hover {
            transform: translateY(-6px); /* Increased hover lift */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
        }
        .product-image-container {
            width: 100%;
            height: 220px; /* Adjusted height */
            overflow: hidden;
            position: relative;
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease, opacity 0.3s ease; /* Added opacity transition */
        }
        .product-card:hover .product-image {
            transform: scale(1.05); /* Increased scale effect */
            opacity: 0.95; /* Slight opacity change */
        }
        .product-card form button {
            display: inline-block; /* Ensure button does not stretch */
            width: auto; /* Adjust width to fit content */
            padding: 8px 16px; /* Compact padding */
            margin: 0 auto; /* Center the button */
            text-align: center;
            background: #f97316;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .product-card form button:hover {
            background: #d97706; /* Slightly darker hover color */
            transform: translateY(-2px); /* Add hover lift */
        }

        /* Bottom Navigation */
        .bottom-nav {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .bottom-nav-item {
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .bottom-nav-item:hover {
            color: #f97316;
            transform: translateY(-2px);
        }
        .bottom-nav-item.active {
            color: #f97316;
            border-bottom: 2px solid #f97316;
        }

        /* Responsive Grid */
        @media (max-width: 1024px) {
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px; /* Adjusted gap */
            }
        }
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: 1fr;
                gap: 12px; /* Adjusted gap */
            }
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <div class="flex gap-6">
            <!-- Sidebar -->
            <aside class="w-1/5 sidebar p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Danh Mục</h2>
                <ul class="space-y-2">
                    @foreach ($categories as $parent)
                        <li class="sidebar-item rounded-lg">
                            <div class="flex items-center justify-between cursor-pointer p-3" onclick="toggleCategory(this)">
                                <div class="flex items-center gap-3">
                                    @if ($parent->icon)
                                        <i class="{{ $parent->icon }} text-orange-500 text-lg"></i>
                                    @else
                                        <i class="fa-solid fa-folder text-orange-500 text-lg"></i>
                                    @endif
                                    <span class="text-gray-700 font-medium">{{ $parent->name }}</span>
                                </div>
                                @if ($parent->children->isNotEmpty())
                                    <i class="fa-solid fa-chevron-down text-gray-500 text-xs transition-transform duration-300"></i>
                                @endif
                            </div>
                            @if ($parent->children->isNotEmpty())
                                <ul class="ml-4 space-y-1 hidden mt-2">
                                    @foreach ($parent->children as $child)
                                        <li class="flex items-center gap-2 p-2 rounded-lg hover:bg-orange-50">
                                            @if ($child->icon)
                                                <i class="{{ $child->icon }} text-orange-400 text-sm"></i>
                                            @else
                                                <i class="fa-solid fa-folder text-orange-400 text-sm"></i>
                                            @endif
                                            <span class="text-gray-600 text-sm">{{ $child->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="w-4/5">
                <!-- Banner Section -->
                <div class="grid grid-cols-3 gap-6 mb-8"> <!-- Adjusted gap -->
                    <!-- Main Banner -->
                    <div class="col-span-2 banner p-6 relative">
                        <div class="relative rounded-lg overflow-hidden">
                            <img src="https://nguyencongpc.vn/media/news/3012_laptop-gaming-dang-mua-dau-nam-2024-6.jpg" alt="Laptop Banner" class="w-full product-image">
                            <span class="absolute top-3 right-3 badge">Mới</span>
                        </div>
                        <div class="mt-4 text-white">
                            <h1 class="text-3xl font-bold">Laptop Gaming RTX 4060</h1> <!-- Increased font size -->
                            <p class="text-lg font-semibold">Từ 19.990.000đ</p>
                            <p class="text-sm mt-2">Ưu đãi mua kèm: Giảm đến 2 triệu</p>
                            <a href="#" class="btn-shop mt-4 inline-flex items-center gap-2">
                                <i class="bi bi-cart"></i>
                                <span>MUA NGAY</span>
                            </a>
                        </div>
                    </div>
                    <!-- Sub Banners -->
                    <div class="space-y-6"> <!-- Adjusted spacing -->
                        <div class="banner p-4">
                            <div class="relative rounded-lg overflow-hidden">
                                <img src="https://storage.googleapis.com/a1aa/image/ChC0I_y3Q4c33Duy6hnpIT-fXbsjCVA13IHUZFH0Z7o.jpg" alt="Laptop Dell" class="w-full product-image">
                                <span class="absolute top-3 right-3 badge">Mới</span>
                            </div>
                            <div class="mt-3 text-white">
                                <h2 class="text-lg font-bold">Dell XPS 13</h2>
                                <p class="text-sm">16GB - 512GB SSD</p>
                                <p class="text-base font-semibold">29.99 Triệu</p>
                                <a href="#" class="btn-shop mt-2 inline-flex items-center gap-2">
                                    <i class="bi bi-cart"></i>
                                    <span>MUA NGAY</span>
                                </a>
                            </div>
                        </div>
                        <div class="banner p-4">
                            <div class="relative rounded-lg overflow-hidden">
                                <img src="https://storage.googleapis.com/a1aa/image/4mfzN0cU_NtUNSLyVTaK53w7XBz0ax3NHTyQHEb29x8.jpg" alt="iMac" class="w-full product-image">
                            </div>
                            <div class="mt-3 text-white">
                                <h2 class="text-lg font-bold">iMac M3</h2>
                                <p class="text-sm">Sáng tạo. Đầy màu sắc.</p>
                                <a href="#" class="btn-shop mt-2 inline-flex items-center gap-2">
                                    <i class="bi bi-cart"></i>
                                    <span>MUA NGAY</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hot Products -->
                <section class="bg-white p-6 rounded-lg shadow-sm mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Sản Phẩm Nổi Bật</h2>
                        <a href="#" class="text-orange-500 font-medium hover:underline flex items-center gap-1">
                            Xem tất cả
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="grid grid-cols-3 gap-6">
                        <!-- Featured Product -->
                        @if (isset($featuredProduct))
                            <div class="col-span-2 product-card p-6 relative">
                                <a href="{{ route('products.show', $featuredProduct->slug) }}">
                                    <div class="product-image-container">
                                        <img src="{{ $featuredProduct->main_image ? asset('storage/' . $featuredProduct->main_image) : asset('default-product.jpg') }}" alt="{{ $featuredProduct->title }}" class="product-image">
                                        @if ($featuredProduct->is_new)
                                            <span class="absolute top-3 right-3 badge">Mới</span>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <h3 class="text-2xl font-bold text-gray-800">{{ $featuredProduct->title }}</h3>
                                        <p class="text-lg text-orange-500 font-semibold mt-2">{{ number_format($featuredProduct->price, 0, ',', '.') }}đ</p>
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-3">{{ $featuredProduct->description }}</p>
                                    </div>
                                </a>
                            </div>
                        @endif
                        <!-- Smaller Products -->
                        @foreach ($hotProducts as $product)
                            <div class="product-card">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <div class="product-image-container">
                                        <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('default-product.jpg') }}" alt="{{ $product->title }}" class="product-image">
                                        @if ($product->is_new)
                                            <span class="absolute top-3 right-3 badge">Mới</span>
                                        @endif
                                    </div>
                                    <div class="p-4 px-6">
                                        <h3 class="text-sm text-gray-800 font-medium line-clamp-2">{{ $product->title }}</h3>
                                        <p class="text-lg text-orange-500 font-semibold mt-1">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                                        <div class="flex items-center mt-2">
                                            @if ($product->average_rating)
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fa-solid fa-star {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                                @endfor
                                                <span class="text-xs text-gray-500 ml-2">Đã bán {{ $product->sold_quantity ?? 0 }}</span>
                                            @else
                                                <span class="text-xs text-gray-500">Chưa có đánh giá</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <form action="{{ route('user.cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-center bg-orange-500 text-white py-2 hover:bg-orange-600 transition-all font-medium text-sm">
                                        <i class="bi bi-cart mr-1"></i> MUA NGAY
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Bottom Navigation -->
                <div class="bottom-nav p-4 flex justify-around">
                    <a href="#" class="bottom-nav-item text-gray-700 font-medium flex items-center gap-2">
                        <span>MACBOOK PRO M4</span>
                        <span class="text-red-500 text-xs">Tặng Chuột Magic</span>
                    </a>
                    <a href="#" class="bottom-nav-item text-gray-700 font-medium flex items-center gap-2">
                        <span>ASUS ROG ZEPHYRUS</span>
                        <span class="text-red-500 text-xs">Đặt trước ngay</span>
                    </a>
                    <a href="#" class="bottom-nav-item text-gray-700 font-medium flex items-center gap-2">
                        <span>DELL ALIENWARE</span>
                        <span class="text-red-500 text-xs">Giá tốt chốt ngay</span>
                    </a>
                    <a href="#" class="bottom-nav-item active font-medium flex items-center gap-2">
                        <span>LAPTOP RTX 4060</span>
                        <span class="text-red-500 text-xs">Ưu đãi tốt</span>
                    </a>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleCategory(element) {
            const chevron = element.querySelector('.fa-chevron-down');
            const subMenu = element.nextElementSibling;
            if (subMenu) {
                subMenu.classList.toggle('hidden');
                chevron.classList.toggle('rotate-180');
            }
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endsection