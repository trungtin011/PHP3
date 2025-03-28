@extends('layouts.user')

@section('title', $product->title)

@section('content')
<div class="container mt-5">
    <div class="row bg-white p-4 rounded-3 shadow-sm">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-6 mb-4">
            <!-- Hình ảnh chính -->
            <div class="position-relative mb-3" style="height: 400px; background-color: #f5f5f5;">
                <img src="{{ asset('storage/' . $product->main_image) }}" 
                     class="img-fluid rounded" 
                     alt="{{ $product->title }}" 
                     style="object-fit: contain; max-height: 100%;">
            </div>
            
            <!-- Hình ảnh phụ -->
            @if($product->additional_images)
                <div class="d-flex flex-wrap gap-2">
                    @foreach(json_decode($product->additional_images, true) as $image)
                        <div class="border rounded" style="width: 80px; height: 80px; cursor: pointer;">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 class="img-fluid w-100 h-100" 
                                 alt="Additional Image" 
                                 style="object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <!-- Tiêu đề -->
            <h2 class="fw-bold" style="color: #333; font-size: 1.8rem; line-height: 1.2;">
                {{ $product->title }}
            </h2>
            
            <!-- Đánh giá và đã bán -->
            <div class="d-flex align-items-center gap-3 my-2" style="font-size: 0.9rem; color: #666;">
                <span>
                    <i class="bi bi-star-fill text-warning"></i>
                    {{ $product->rating ?? '5.0' }} 
                    <span class="text-muted">({{ $product->reviews_count ?? '100' }} đánh giá)</span>
                </span>
                <span>|</span>
                <span>Đã bán {{ $product->sold_count ?? '1k+' }}</span>
            </div>

            <!-- Giá -->
            <div class="bg-light p-3 rounded-2 mb-3">
                @if($product->discount > 0)
                    <span class="text-muted text-decoration-line-through me-2" style="font-size: 1.2rem;">
                        {{ number_format($product->price, 0, ',', '.') }} đ
                    </span>
                    <span class="text-danger fw-bold" style="font-size: 2rem;">
                        {{ number_format($product->price * (1 - $product->discount/100), 0, ',', '.') }} đ
                    </span>
                    <span class="bg-danger text-white px-2 py-1 ms-2 rounded" style="font-size: 0.9rem;">
                        -{{ $product->discount }}%
                    </span>
                @else
                    <span class="text-danger fw-bold" style="font-size: 2rem;">
                        {{ number_format($product->price, 0, ',', '.') }} đ
                    </span>
                @endif
            </div>

            <!-- Thông tin chi tiết -->
            <div class="mb-4">
                <p class="mb-2"><strong>Mô tả:</strong> {{ $product->description }}</p>
                <p class="mb-2"><strong>Tình trạng:</strong> 
                    <span class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                    </span>
                </p>
                <p class="mb-2"><strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Nguồn gốc:</strong> {{ $product->location ?? 'TP. Hồ Chí Minh' }}</p>
            </div>

            <!-- Nút hành động -->
            <div class="d-flex gap-3">
                <!-- Sửa nút Quay lại -->
                <a href="{{ route('products.list') }}" 
                   class="btn btn-outline-secondary px-4 py-2" 
                   style="color: #333; border-color: #ccc; background-color: #fff; transition: all 0.3s;">
                    Back to Products
                </a>
                <button class="btn text-white px-4 py-2" 
                        style="background-color: #ee4d2d; border: none;"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    Thêm vào giỏ
                </button>
            </div>
        </div>
    </div>

    <!-- Mô tả chi tiết hoặc nội dung bổ sung -->
    <div class="bg-white p-4 rounded-3 shadow-sm mt-4">
        <h4 class="fw-bold mb-3" style="color: #ee4d2d;">Chi tiết sản phẩm</h4>
        <p>{{ $product->detailed_description ?? $product->description }}</p>
    </div>
</div>

<style>
.btn-outline-secondary:hover {
    background-color: #f5f5f5 !important;
    color: #333 !important;
    border-color: #999 !important;
}

.btn:hover:not(.btn-outline-secondary) {
    background-color: #d43f21 !important;
}
</style>
@endsection