@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3" style="background: #fff; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #ee4d2d; font-weight: bold; font-size: 24px; margin: 0;">Quản Lý Sản Phẩm</h2>
            <div>
                <a href="{{ route('admin.products.create') }}" class="btn me-2"
                    style="background: #ee4d2d; color: white; font-weight: 600; padding: 10px 20px; border-radius: 5px;">
                    <i class="bi bi-plus-lg me-2"></i>Thêm Sản Phẩm
                </a>
                <a href="{{ route('admin.products.import') }}" class="btn"
                    style="background: #17a2b8; color: white; font-weight: 600; padding: 10px 20px; border-radius: 5px;">
                    <i class="bi bi-file-earmark-excel me-2"></i>Nhập Excel
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="background: #d4edda; border: none; border-radius: 5px; color: #155724;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Thanh tìm kiếm và lọc -->
        <div class="mb-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6 position-relative">
                    <input type="text" name="search" class="form-control shadow-sm"
                        placeholder="Tìm kiếm sản phẩm..." id="search-input"
                        value="{{ request('search') }}"
                        style="border-radius: 5px; padding: 10px 40px 10px 15px; border: 1px solid #ddd;">
                    <i class="bi bi-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #757575;"></i>
                    <ul class="search-results list-unstyled mt-2 position-absolute bg-white border rounded-3 shadow-sm w-100"
                        id="search-results" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;"></ul>
                </div>
                <div class="col-md-3">
                    <select name="price_filter" class="form-select shadow-sm"
                        style="border-radius: 5px; padding: 10px; border: 1px solid #ddd;">
                        <option value="">Lọc theo giá</option>
                        <option value="low_to_high" {{ request('price_filter') == 'low_to_high' ? 'selected' : '' }}>Thấp đến Cao</option>
                        <option value="high_to_low" {{ request('price_filter') == 'high_to_low' ? 'selected' : '' }}>Cao đến Thấp</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn w-100"
                        style="background: #ee4d2d; color: white; border-radius: 5px; padding: 10px;">Áp dụng</button>
                </div>
            </form>
        </div>

        <!-- Bảng sản phẩm -->
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="border-radius: 5px; overflow: hidden;">
                <thead style="background: #f5f5f5; color: #333;">
                    <tr>
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Hình ảnh</th>
                        <th style="padding: 15px;">Tên sản phẩm</th>
                        <th style="padding: 15px;">Giá</th>
                        <th style="padding: 15px;">Kho</th>
                        <th style="padding: 15px;">Trạng thái</th>
                        <th style="padding: 15px;">Danh mục</th>
                        <th style="padding: 15px;">Thương hiệu</th>
                        <th style="padding: 15px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;">{{ $product->id }}</td>
                        <td style="padding: 15px;">
                            @if($product->main_image)
                            <img src="{{ $product->main_image ? Storage::url($product->main_image) : asset('default-image.jpg') }}" alt="{{ $product->title }}"
                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; border: 1px solid #eee;">
                            @else
                            <span class="text-muted">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td style="padding: 15px; font-weight: 500;">{{ Str::limit($product->title, 30, '...') }}</td>
                        <td style="padding: 15px; color: #ee4d2d;">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                        <td style="padding: 15px;">{{ $product->stock }}</td>
                        <td style="padding: 15px;">
                            @if($product->status == 'in_stock')
                            <span class="badge" style="background: #28a745; color: white;">Còn hàng</span>
                            @elseif($product->status == 'out_of_stock')
                            <span class="badge" style="background: #dc3545; color: white;">Hết hàng</span>
                            @else
                            <span class="badge" style="background: #6c757d; color: white;">Không xác định</span>
                            @endif
                        </td>
                        <td style="padding: 15px;">{{ $product->category->name ?? 'Không có' }}</td>
                        <td style="padding: 15px;">{{ $product->brand->name ?? 'Không có' }}</td>
                        <td style="padding: 15px;">
                            <!-- Nút Edit -->
                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                class="btn btn-sm me-2" 
                                style="background: #ffd700; color: #333; border-radius: 5px;" 
                                title="Sửa">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <!-- Nút Delete -->
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" 
                                    style="background: #dc3545; color: white; border-radius: 5px;" 
                                    title="Xóa">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background: #fafafa;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .pagination .page-link {
        border-radius: 5px;
        color: #ee4d2d;
        margin: 0 5px;
    }

    .pagination .page-item.active .page-link {
        background: #ee4d2d;
        border-color: #ee4d2d;
        color: white;
    }

    .pagination .page-link:hover {
        background: #ee4d2d;
        color: white;
    }

    .search-results li {
        padding: 10px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .search-results li:hover {
        background: #f5f5f5;
    }
</style>

<script>
    // Xử lý tìm kiếm
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length > 0) {
            fetch(`{{ route('admin.products.search') }}?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    searchResults.style.display = 'block';
                    data.forEach(product => {
                        const li = document.createElement('li');
                        li.classList.add('d-flex', 'align-items-center');

                        const img = document.createElement('img');
                        img.src = product.main_image ? `{{ asset('storage') }}/${product.main_image}` : 'https://via.placeholder.com/50';
                        img.alt = product.title;
                        img.style.width = '50px';
                        img.style.height = '50px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '5px';
                        img.classList.add('me-3');

                        const div = document.createElement('div');
                        div.innerHTML = `<strong style="color: #333;">${product.title}</strong><br>
                                        <span style="color: #ee4d2d;">${new Intl.NumberFormat('vi-VN').format(product.price)} đ</span>`;

                        li.appendChild(img);
                        li.appendChild(div);
                        li.addEventListener('click', () => {
                            window.location.href = `{{ url('/admin/products') }}/${product.id}/edit`;
                        });

                        searchResults.appendChild(li);
                    });
                })
                .catch(error => console.error('Lỗi tìm kiếm:', error));
        } else {
            searchResults.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!searchResults.contains(event.target) && event.target !== searchInput) {
            searchResults.style.display = 'none';
        }
    });

    // Xác nhận xóa sản phẩm
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn xóa sản phẩm này không?')) {
                this.submit();
            }
        });
    });
</script>
@endsection