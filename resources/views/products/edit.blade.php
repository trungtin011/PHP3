<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MagicShop - Sửa sản phẩm</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    @include('layouts.header')
    <!-- Main Content -->
    <main class="container mt-4 flex-grow-1">
        <h2 class="text-center mb-4">Sửa sản phẩm</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Tồn kho</label>
                <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Còn hàng</option>
                    <option value="out_of_stock" {{ $product->status == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Link hình ảnh</label>
                <input type="url" class="form-control" id="image" name="image" value="{{ $product->image }}" placeholder="Nhập URL hình ảnh">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->title }}" class="img-thumbnail mt-2" style="width: 150px;">
                @endif
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ $product->slug }}">
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
        </form>
    </main>
    @include('layouts.footer')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
