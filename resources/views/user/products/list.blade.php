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
    @if($products->count() > 0)
        <div class="row g-3">
            @foreach ($products as $product)
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
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
                                    <i class="bi bi-star-fill text-warning"></i>
                                    {{ $product->rating ?? '5.0' }} ({{ $product->reviews_count ?? '100' }})
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
                                <button type="submit" class="btn btn-danger btn-sm w-70 mt-2" 
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
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
    background-color: #d43f21 !important;
}
</style>
@endsection