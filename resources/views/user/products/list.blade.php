@extends('layouts.user')

@section('title', 'Danh Sách Sản Phẩm')

@section('content')
<div class="container mt-4">
    <!-- Banner giống Shopee -->
    <div class="mb-4">
        <div class="position-relative rounded-3 overflow-hidden" style="height: 300px; background-color: #f5f5f5;">
            <img src="https://no1computer.vn/upload_images/images/2024/04/13/No1computer%20Sale%2030-4%2C%201-5%20trangchu%202-min.png" 
                 class="w-100 h-100" 
                 alt="Banner Promotion" 
                 style="object-fit: cover;">
        </div>
    </div>

    <div class="row">
        <!-- Sidebar lọc (bên trái) -->
        <div class="col-lg-3 col-md-4 d-none d-md-block">
            <div class="bg-white rounded-3 shadow-sm p-4" style="border: 1px solid #f0f0f0;">
                <h5 class="fw-bold mb-4" style="color: #ee4d2d;">Bộ lọc tìm kiếm</h5>

                <!-- Lọc danh mục -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3" style="color: #333;">Danh mục</h6>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('products.list') }}" 
                           class="list-group-item list-group-item-action border-0 py-1 {{ !request('category') ? 'fw-bold text-primary' : '' }}"
                           style="font-size: 0.9rem; color: #333;">
                            Tất cả
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products.list', array_merge(request()->query(), ['category' => $category->id])) }}" 
                               class="list-group-item list-group-item-action border-0 py-1 {{ request('category') == $category->id ? 'fw-bold text-primary' : '' }}"
                               style="font-size: 0.9rem; color: #333;">
                                {{ $category->name }}
                            </a>
                            @if($category->children->count())
                                @foreach($category->children as $child)
                                    <a href="{{ route('products.list', array_merge(request()->query(), ['category' => $child->id])) }}" 
                                       class="list-group-item list-group-item-action border-0 py-1 ps-4 {{ request('category') == $child->id ? 'fw-bold text-primary' : '' }}"
                                       style="font-size: 0.85rem; color: #555;">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Lọc thương hiệu -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3" style="color: #333;">Thương hiệu</h6>
                    <form method="GET" action="{{ route('products.list') }}">
                        @foreach (request()->except('brand') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $val)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <div class="overflow-auto" style="max-height: 200px;">
                            @foreach ($brands as $brand)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="brand[]" 
                                           value="{{ $brand->id }}" 
                                           id="brand-{{ $brand->id }}"
                                           {{ is_array(request('brand')) && in_array($brand->id, request('brand')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="brand-{{ $brand->id }}" style="font-size: 0.9rem; color: #333;">
                                        {{ $brand->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" 
                                class="btn btn-sm w-100 text-white mt-3" 
                                style="background-color: #ee4d2d; font-size: 0.9rem;">
                            Áp dụng
                        </button>
                    </form>
                </div>

                <!-- Lọc giá -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3" style="color: #333;">Khoảng giá</h6>
                    <form method="GET" action="{{ route('products.list') }}">
                        @foreach (request()->except(['price_min', 'price_max']) as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $val)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <div class="mb-3">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="price_min" 
                                   placeholder="Từ" 
                                   value="{{ request('price_min') }}" 
                                   style="font-size: 0.9rem;">
                        </div>
                        <div class="mb-3">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="price_max" 
                                   placeholder="Đến" 
                                   value="{{ request('price_max') }}" 
                                   style="font-size: 0.9rem;">
                        </div>
                        <button type="submit" 
                                class="btn btn-sm w-100 text-white" 
                                style="background-color: #ee4d2d; font-size: 0.9rem;">
                            Áp dụng
                        </button>
                    </form>
                    <!-- Checkbox khoảng giá phổ biến -->
                    <div class="mt-3">
                        @php
                            $priceRanges = [
                                ['min' => 0, 'max' => 500000, 'label' => 'Dưới 500K'],
                                ['min' => 500000, 'max' => 1000000, 'label' => '500K - 1M'],
                                ['min' => 1000000, 'max' => 2000000, 'label' => '1M - 2M'],
                                ['min' => 2000000, 'max' => 999999999, 'label' => 'Trên 2M'],
                            ];
                        @endphp
                        @foreach ($priceRanges as $range)
                            <a href="{{ route('products.list', array_merge(request()->except(['price_min', 'price_max']), ['price_min' => $range['min'], 'price_max' => $range['max']])) }}"
                               class="d-block mb-2 text-decoration-none {{ request('price_min') == $range['min'] && request('price_max') == $range['max'] ? 'fw-bold text-primary' : '' }}"
                               style="font-size: 0.9rem; color: #333;">
                                {{ $range['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-9 col-md-8">
            @if($products->count() > 0)
                <div class="row g-3">
                    @foreach ($products as $product)
                        <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                            <div class="card h-100 border-0 shadow-sm product-card" style="transition: all 0.2s ease;">
                                <!-- Hình ảnh sản phẩm -->
                                <div class="position-relative" style="height: 180px; background-color: #f5f5f5;">
                                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                                         class="card-img-top img-fluid" 
                                         alt="{{ $product->title }}" 
                                         style="object-fit: cover; height: 100%; width: 100%;">
                                    @if($product->discount > 0)
                                        <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1" 
                                              style="font-size: 0.8rem;">
                                            -{{ $product->discount }}%
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Nội dung -->
                                <div class="card-body p-2 d-flex flex-column">
                                    <!-- Tên sản phẩm -->
                                    <h6 class="card-title mb-2" 
                                        style="font-size: 0.95rem; line-height: 1.2; height: 2.4rem; overflow: hidden; color: #333;">
                                        {{ Str::limit($product->title, 50) }}
                                    </h6>
                                    
                                    <!-- Giá -->
                                    <div class="mb-2">
                                        @if($product->discount > 0)
                                            <span class="text-muted text-decoration-line-through me-1" 
                                                  style="font-size: 0.85rem;">
                                                {{ number_format($product->price, 0, ',', '.') }} đ
                                            </span>
                                            <span class="text-danger fw-bold" 
                                                  style="font-size: 1.1rem;">
                                                {{ number_format($product->price * (1 - $product->discount/100), 0, ',', '.') }} đ
                                            </span>
                                        @else
                                            <span class="text-danger fw-bold" 
                                                  style="font-size: 1.1rem;">
                                                {{ number_format($product->price, 0, ',', '.') }} đ
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Thông tin bổ sung -->
                                    <div class="d-flex justify-content-between align-items-center" style="font-size: 0.8rem; color: #666;">
                                        <span>
                                            @if($product->average_rating > 0)
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill text-warning' : '' }}"></i>
                                                @endfor
                                                <span>{{ number_format($product->average_rating, 1) }} ({{ $product->reviews->count() }})</span>
                                            @else
                                                <span>Chưa có đánh giá</span>
                                            @endif
                                        </span>
                                        <span>Đã bán {{ $product->sold_count ?? '1k+' }}</span>
                                    </div>
                                    
                                    <!-- Nguồn -->
                                    <p class="mt-1 mb-0 text-muted" style="font-size: 0.75rem;">
                                        {{ $product->location ?? 'TP. Hồ Chí Minh' }}
                                    </p>

                                    <!-- Add to Cart Button -->
                                    <form action="{{ route('user.cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm w-100 mt-2" 
                                                {{ $product->stock <= 0 ? 'disabled' : '' }}
                                                style="background-color: #ee4d2d; border-color: #ee4d2d;">
                                            {{ $product->stock <= 0 ? 'Hết hàng' : 'Thêm vào giỏ hàng' }}
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Hover effect -->
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   class="stretched-link" 
                                   style="text-decoration: none;"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                    <p class="mt-3 fs-4 text-muted">Hiện tại chưa có sản phẩm nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Sidebar lọc trên mobile (modal) -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="filterModalLabel" style="color: #ee4d2d;">Bộ lọc tìm kiếm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Lọc danh mục -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3" style="color: #333;">Danh mục</h6>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('products.list') }}" 
                           class="list-group-item list-group-item-action border-0 py-1 {{ !request('category') ? 'fw-bold text-primary' : '' }}"
                           style="font-size: 0.9rem; color: #333;">
                            Tất cả
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products.list', array_merge(request()->query(), ['category' => $category->id])) }}" 
                               class="list-group-item list-group-item-action border-0 py-1 {{ request('category') == $category->id ? 'fw-bold text-primary' : '' }}"
                               style="font-size: 0.9rem; color: #333;">
                                {{ $category->name }}
                            </a>
                            @if($category->children->count())
                                @foreach($category->children as $child)
                                    <a href="{{ route('products.list', array_merge(request()->query(), ['category' => $child->id])) }}" 
                                       class="list-group-item list-group-item-action border-0 py-1 ps-4 {{ request('category') == $child->id ? 'fw-bold text-primary' : '' }}"
                                       style="font-size: 0.85rem; color: #555;">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Lọc thương hiệu -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3" style="color: #333;">Thương hiệu</h6>
                    <form method="GET" action="{{ route('products.list') }}">
                        @foreach (request()->except('brand') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $val)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        @foreach ($brands as $brand)
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="brand[]" 
                                       value="{{ $brand->id }}" 
                                       id="mobile-brand-{{ $brand->id }}"
                                       {{ is_array(request('brand')) && in_array($brand->id, request('brand')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="mobile-brand-{{ $brand->id }}" style="font-size: 0.9rem; color: #333;">
                                    {{ $brand->name }}
                                </label>
                            </div>
                        @endforeach
                        <button type="submit" 
                                class="btn btn-sm w-100 text-white mt-3" 
                                style="background-color: #ee4d2d; font-size: 0.9rem;">
                            Áp dụng
                        </button>
                    </form>
                </div>

                <!-- Lọc giá -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3" style="color: #333;">Khoảng giá</h6>
                    <form method="GET" action="{{ route('products.list') }}">
                        @foreach (request()->except(['price_min', 'price_max']) as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $val)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <div class="mb-3">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="price_min" 
                                   placeholder="Từ" 
                                   value="{{ request('price_min') }}" 
                                   style="font-size: 0.9rem;">
                        </div>
                        <div class="mb-3">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="price_max" 
                                   placeholder="Đến" 
                                   value="{{ request('price_max') }}" 
                                   style="font-size: 0.9rem;">
                        </div>
                        <button type="submit" 
                                class="btn btn-sm w-100 text-white" 
                                style="background-color: #ee4d2d; font-size: 0.9rem;">
                            Áp dụng
                        </button>
                    </form>
                    <div class="mt-3">
                        @foreach ($priceRanges as $range)
                            <a href="{{ route('products.list', array_merge(request()->except(['price_min', 'price_max']), ['price_min' => $range['min'], 'price_max' => $range['max']])) }}"
                               class="d-block mb-2 text-decoration-none {{ request('price_min') == $range['min'] && request('price_max') == $range['max'] ? 'fw-bold text-primary' : '' }}"
                               style="font-size: 0.9rem; color: #333;">
                                {{ $range['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-md-none fixed-bottom bg-white border-top p-2">
    <button class="btn w-100 text-white" 
            data-bs-toggle="modal" 
            data-bs-target="#filterModal"
            style="background-color: #ee4d2d; font-size: 0.9rem;">
        <i class="bi bi-filter me-2"></i>Bộ lọc
    </button>
</div>

<style>
.product-card:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-3px);
}

.product-card {
    background-color: #fff;
    border-radius: 4px;
    overflow: hidden;
}

.btn:hover {
    background-color: #f26a2e !important;
}

.list-group-item:hover {
    background-color: #f8f8f8;
}

.form-check-input:checked {
    background-color: #ee4d2d;
    border-color: #ee4d2d;
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(238, 77, 45, 0.25);
}

.bi-star, .bi-star-fill {
    font-size: 0.8rem;
}
</style>

<!-- Bootstrap Icons và Bootstrap JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
