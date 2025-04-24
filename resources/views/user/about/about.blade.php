@extends('layouts.user')

@section('content')
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #ea580c;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #c2410c;
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .hero-banner:hover {
            transform: translateY(-8px);
        }
        .hero-image {
            object-fit: cover;
            height: 400px;
            width: 100%;
            transition: transform 0.5s ease;
        }
        .hero-banner:hover .hero-image {
            transform: scale(1.05);
        }

        /* Section Card */
        .section-card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .section-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        .section-image {
            object-fit: cover;
            height: 200px;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .section-card:hover .section-image {
            transform: scale(1.03);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-image {
                height: 250px;
            }
            .section-image {
                height: 150px;
            }
        }
    </style>

    <div class="min-h-screen p-6 bg-gray-50">
        <!-- Hero Banner -->
        <div class="hero-banner mb-10 animate-fadeIn">
            <div class="relative">
                <img src="{{ $aboutData['banner'] ?? 'https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fGJhbm5lcnxlbnwwfHx8fDE2OTI3NTY1MjM&ixlib=rb-4.0.3&q=80&w=1080' }}" alt="Hero Banner" class="hero-image w-full rounded-t-lg">
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-white text-center">{{ $aboutData['title'] }}</h1>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div class="space-y-12">
            <!-- Introduction -->
            <div class="section-card p-8 animate-fadeIn" style="animation-delay: 0.2s;">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Giới Thiệu</h2>
                        <p class="text-lg text-gray-600 leading-relaxed">{{ $aboutData['description'] ?? 'MagicShop là nền tảng thương mại điện tử hàng đầu, mang đến trải nghiệm mua sắm tiện lợi, an toàn và đa dạng. Chúng tôi cam kết cung cấp sản phẩm chất lượng cao với giá cả cạnh tranh.' }}</p>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://cdn.tgdd.vn/Files/2020/10/12/1298194/kinh-nghiem-hay-chon-mua-laptop-dung-chuan-phu-ho-35.jpg" alt="Introduction" class="section-image rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Mission -->
            <div class="section-card p-8 animate-fadeIn" style="animation-delay: 0.4s;">
                <div class="flex flex-col md:flex-row-reverse gap-6">
                    <div class="md:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <i class="fa-solid fa-rocket text-orange-500 text-2xl"></i>
                            Sứ Mệnh
                        </h2>
                        <p class="text-lg text-gray-600 leading-relaxed">{{ $aboutData['mission'] ?? 'Chúng tôi hướng đến việc mang lại giá trị vượt trội cho khách hàng thông qua các sản phẩm chất lượng, dịch vụ tận tâm và công nghệ tiên tiến.' }}</p>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1516321318423-8c8a944a1843?q=80&w=2070&auto=format&fit=crop" alt="Mission" class="section-image rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Vision -->
            <div class="section-card p-8 animate-fadeIn" style="animation-delay: 0.6s;">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <i class="fa-solid fa-eye text-orange-500 text-2xl"></i>
                            Tầm Nhìn
                        </h2>
                        <p class="text-lg text-gray-600 leading-relaxed">{{ $aboutData['vision'] ?? 'MagicShop phấn đấu trở thành nền tảng thương mại điện tử được yêu thích nhất khu vực, tiên phong trong đổi mới và bền vững.' }}</p>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070&auto=format&fit=crop" alt="Vision" class="section-image rounded-lg">
                    </div>
                </div>
            </div>

            <!-- History -->
            <div class="section-card p-8 animate-fadeIn" style="animation-delay: 0.8s;">
                <div class="flex flex-col md:flex-row-reverse gap-6">
                    <div class="md:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <i class="fa-solid fa-clock-rotate-left text-orange-500 text-2xl"></i>
                            Lịch Sử
                        </h2>
                        <p class="text-lg text-gray-600 leading-relaxed">{{ $aboutData['history'] ?? 'Thành lập vào năm 2020, MagicShop đã nhanh chóng phát triển từ một cửa hàng trực tuyến nhỏ thành một nền tảng thương mại điện tử lớn, phục vụ hàng triệu khách hàng với hàng nghìn sản phẩm đa dạng.' }}</p>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop" alt="History" class="section-image rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Values -->
            <div class="section-card p-8 animate-fadeIn" style="animation-delay: 1s;">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-heart text-orange-500 text-2xl"></i>
                        Giá Trị Cốt Lõi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 rounded-lg bg-orange-50">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chất Lượng</h3>
                            <p class="text-gray-600">{{ $aboutData['values']['quality'] ?? 'Cam kết cung cấp sản phẩm chất lượng cao, đáp ứng tiêu chuẩn khắt khe.' }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-orange-50">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Khách Hàng</h3>
                            <p class="text-gray-600">{{ $aboutData['values']['customer'] ?? 'Đặt khách hàng làm trung tâm, mang đến trải nghiệm mua sắm tuyệt vời.' }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-orange-50">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Đổi Mới</h3>
                            <p class="text-gray-600">{{ $aboutData['values']['innovation'] ?? 'Luôn tiên phong trong công nghệ và cải tiến dịch vụ.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection