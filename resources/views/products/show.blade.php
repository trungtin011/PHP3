<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MagicShop - Chi tiết sản phẩm</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    @include('layouts.header')
    <!-- Main Content -->
    <main class="container mt-4 flex-grow-1">
        <h2 class="text-center mb-4">{{ $product->title }}</h2>
        <div class="row">
            <div class="col-md-6">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->title }}" class="img-fluid">
                @else
                    <span>Không có hình ảnh</span>
                @endif
            </div>
            <div class="col-md-6">
                <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</p>
                <p><strong>Tồn kho:</strong> {{ $product->stock }}</p>
                <p><strong>Trạng thái:</strong> 
                    @if($product->status == 'available')
                        <span class="badge bg-success">Còn hàng</span>
                    @elseif($product->status == 'out_of_stock')
                        <span class="badge bg-danger">Hết hàng</span>
                    @else
                        <span class="badge bg-secondary">Không xác định</span>
                    @endif
                </p>
                <p><strong>Mô tả:</strong> {{ $product->description }}</p>
                <p><strong>Slug:</strong> {{ $product->slug }}</p>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Sửa</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</button>
                </form>
            </div>
        </div>
    </main>
    @include('layouts.footer')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
