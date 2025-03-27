<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MagicShop - Danh sách sản phẩm</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-results {
            position: absolute;
            z-index: 1000;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
        }
        .search-results li {
            padding: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .search-results li img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }
        .search-results li:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    @include('layouts.header')
    <!-- Main Content -->
    <main class="container mt-4 flex-grow-1">
        <h2 class="text-center mb-4">Danh sách sản phẩm</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="mb-3 position-relative">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}" id="search-input">
                <button type="submit" class="btn btn-outline-success me-2">Tìm kiếm</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Xóa tìm kiếm</a>
            </form>
            <ul class="search-results list-unstyled mt-2" id="search-results"></ul>
        </div>
        <div class="mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Thêm sản phẩm</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->title }}" class="img-thumbnail" style="width: 100px;">
                                @else
                                    <span>Không có hình ảnh</span>
                                @endif
                            </td>
                            <td class="text-start">
                                <a href="{{ route('products.show', $product->id) }}">{{ $product->title }}</a>
                            </td>
                            <td class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->status == 'available')
                                    <span class="badge bg-success">Còn hàng</span>
                                @elseif($product->status == 'out_of_stock')
                                    <span class="badge bg-danger">Hết hàng</span>
                                @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </main>
    @include('layouts.footer')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('search-input').addEventListener('input', function() {
            const query = this.value;
            if (query.length > 0) {
                fetch(`{{ route('products.search') }}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        const results = document.getElementById('search-results');
                        results.innerHTML = '';
                        data.forEach(product => {
                            const li = document.createElement('li');
                            const img = document.createElement('img');
                            img.src = product.image || 'https://via.placeholder.com/50';
                            const div = document.createElement('div');
                            div.innerHTML = `<strong>${product.title}</strong><br>Giá: ${new Intl.NumberFormat().format(product.price)} đ<br>Tồn kho: ${product.stock}`;
                            li.appendChild(img);
                            li.appendChild(div);
                            li.addEventListener('click', () => {
                                window.location.href = `{{ url('/products') }}/${product.id}`;
                            });
                            results.appendChild(li);
                        });
                    });
            } else {
                document.getElementById('search-results').innerHTML = '';
            }
        });
    </script>
</body>
</html>
