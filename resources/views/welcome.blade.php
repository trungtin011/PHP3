

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MagicShop - Gaming Heaven')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .banner {
            background: url('https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/474087fgn/anh-nen-3d-gaming-4k_081446023_thumb.jpg') no-repeat center center;
            background-size: cover;
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .product-card {
            transition: transform 0.3s ease-in-out;
        }
        .product-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @include('layouts.header')
    <!-- Banner -->
    <div class="banner">
        <h1>Chào mừng đến với MagicShop - Thế giới Gaming đỉnh cao!</h1>
    </div>
    <main class="container mt-4 flex-grow-1">
        <h2 class="text-center mb-4">Sản phẩm nổi bật</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card product-card">
                    <img src="https://cdn-media.sforum.vn/storage/app/media/wp-content/uploads/2023/08/hinh-nen-gaming-thumb.jpg" class="card-img-top" alt="Laptop Gaming">
                    <div class="card-body">
                        <h5 class="card-title">Laptop Gaming Siêu Khủng</h5>
                        <p class="card-text">Hiệu suất vượt trội cho game thủ chuyên nghiệp.</p>
                        <a href="#" class="btn btn-primary">Mua ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card product-card">
                    <img src="https://cdn-media.sforum.vn/storage/app/media/wp-content/uploads/2023/08/hinh-nen-gaming-thumb.jpg" class="card-img-top" alt="Bàn phím cơ">
                    <div class="card-body">
                        <h5 class="card-title">Bàn Phím Cơ RGB</h5>
                        <p class="card-text">Cảm giác gõ tuyệt vời, độ bền cao.</p>
                        <a href="#" class="btn btn-primary">Mua ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card product-card">
                    <img src="https://cdn-media.sforum.vn/storage/app/media/wp-content/uploads/2023/08/hinh-nen-gaming-thumb.jpg" class="card-img-top" alt="Chuột Gaming">
                    <div class="card-body">
                        <h5 class="card-title">Chuột Gaming Cao Cấp</h5>
                        <p class="card-text">Độ chính xác tuyệt đối, phù hợp mọi thể loại game.</p>
                        <a href="#" class="btn btn-primary">Mua ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>