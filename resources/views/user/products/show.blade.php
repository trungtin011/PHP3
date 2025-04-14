@extends('layouts.user')

@section('title', $product->title)

@section('content')
<div class="container mt-5">
    <div class="row bg-white p-4 rounded-3 shadow-sm">
        <div class="col-md-6 mb-4">
            <!-- Hình ảnh chính -->
            <div class="position-relative mb-3" style="height: 400px; background-color: #f5f5f5;">
                <img src="{{ asset('storage/' . $product->main_image) }}" 
                     class="img-fluid rounded" 
                     alt="{{ $product->title }}" 
                     style="object-fit: contain; max-height: 100%;">
            </div>
            
            <!-- Hình ảnh phụ -->
            @if(!empty($product->additional_images))
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->additional_images as $image)
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
        <div class="col-md-6">
            <!-- Tiêu đề -->
            <h2 class="fw-bold" style="color: #333; font-size: 1.8rem; line-height: 1.2;">
                {{ $product->title }}
            </h2>
            
            <!-- Đánh giá và đã bán -->
            <div class="d-flex align-items-center gap-3 my-2" style="font-size: 0.9rem; color: #666;">
                <span>
                    @if ($product->average_rating > 0)
                        <span class="text-danger fw-bold">{{ number_format($product->average_rating, 1) }}</span>
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill text-warning' : '' }}"></i>
                        @endfor
                        <span class="text-muted">({{ $product->reviews->count() }} đánh giá)</span>
                    @else
                        <span class="text-muted">Chưa có đánh giá</span>
                    @endif
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
                <p class="mb-2"><strong>Tồn kho </strong>{{ $product->stock }}</p>

                <p class="mb-2"><strong>Nguồn gốc:</strong> {{ $product->location ?? 'TP. Hồ Chí Minh' }}</p>
            </div>

            <!-- Nút hành động -->
            <div class="d-flex gap-3">
                <a href="{{ route('products.list') }}" 
                   class="btn btn-outline-secondary px-4 py-2" 
                   style="color: #333; border-color: #ccc; background-color: #fff; transition: all 0.3s;">
                    Quay lại
                </a>
                <form action="{{ route('user.cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button class="btn text-white px-4 py-2" 
                            style="background-color: #ee4d2d; border: none;"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock <= 0 ? 'Hết hàng' : 'Thêm vào giỏ hàng' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mô tả chi tiết -->
    <div class="bg-white p-4 rounded-3 shadow-sm mt-4">
        <h4 class="fw-bold mb-3" style="color: #ee4d2d;">Chi tiết sản phẩm</h4>
        <p>{{ $product->detailed_description ?? $product->description }}</p>
    </div>

    <!-- Đánh giá sản phẩm -->
    <div class="bg-white p-4 rounded-3 shadow-sm mt-4">
        <h4 class="fw-bold mb-4" style="color: #ee4d2d;">Đánh giá sản phẩm</h4>

        <!-- Form gửi đánh giá -->
        <div class="mb-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @auth
                @if (!\App\Models\Review::where('user_id', Auth::id())->where('product_id', $product->id)->exists())
                    <form action="{{ route('products.review', $product->slug) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Chọn số sao:</label>
                            <div class="star-rating d-flex gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="star-item">
                                        <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}">
                                        <label for="star{{ $i }}" class="bi bi-star-fill" style="font-size: 1.5rem; color: #d1d5db;"></label>
                                    </div>
                                @endfor
                            </div>
                            @error('rating')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="fw-bold mb-2">Nhận xét:</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4" maxlength="500" 
                                      placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." 
                                      style="resize: none;"></textarea>
                            @error('comment')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn text-white px-4 py-2" 
                                style="background-color: #ee4d2d; border: none;">
                            Gửi đánh giá
                        </button>
                    </form>
                @else
                    <p class="text-muted">Bạn đã gửi đánh giá cho sản phẩm này.</p>
                @endif
            @else
                <p>Vui lòng <a href="{{ route('login') }}" class="text-primary">đăng nhập</a> để gửi đánh giá.</p>
            @endif
        </div>

        <!-- Danh sách đánh giá -->
        @if ($product->reviews->isEmpty())
            <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
        @else
            <div class="reviews-list">
                @foreach ($product->reviews as $review)
                    <div class="d-flex gap-3 py-3 border-bottom">
                        <!-- Ảnh đại diện -->
                        <div>
                            <img src="{{ Auth::user()->avatar }}" 
                                 class="rounded-circle" 
                                 style="width: 40px; height: 40px; object-fit: cover;" 
                                 alt="{{ $review->user->name }}">
                        </div>
                        <!-- Nội dung đánh giá -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $review->user->name }}</strong>
                                <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="my-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}" style="font-size: 0.9rem;"></i>
                                @endfor
                            </div>
                            @if ($review->comment)
                                <p class="mb-0">{{ $review->comment }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Sản phẩm liên quan -->
    @if ($relatedProducts->isNotEmpty())
        <div class="bg-white p-4 rounded-3 shadow-sm mt-4">
            <h4 class="fw-bold mb-4" style="color: #ee4d2d;">Sản phẩm liên quan</h4>
            <div class="row row-cols-1 row-cols-md-4 g-3">
                @foreach ($relatedProducts as $related)
                    <div class="col">
                        <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none text-dark">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative" style="height: 150px; background-color: #f5f5f5;">
                                    <img src="{{ asset('storage/' . $related->main_image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $related->title }}" 
                                         style="object-fit: contain; max-height: 100%;">
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title" style="font-size: 0.9rem; line-height: 1.2; height: 2.4rem; overflow: hidden;">
                                        {{ Str::limit($related->title, 50) }}
                                    </h6>
                                    <div class="text-danger fw-bold" style="font-size: 1rem;">
                                        @if($related->discount > 0)
                                            {{ number_format($related->price * (1 - $related->discount/100), 0, ',', '.') }} đ
                                            <small class="text-muted text-decoration-line-through">
                                                {{ number_format($related->price, 0, ',', '.') }} đ
                                            </small>
                                        @else
                                            {{ number_format($related->price, 0, ',', '.') }} đ
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem; color: #666;">
                                        @if ($related->average_rating > 0)
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($related->average_rating) ? '-fill text-warning' : '' }}"></i>
                                            @endfor
                                            <span>({{ $related->reviews->count() }})</span>
                                        @else
                                            <span>Chưa có đánh giá</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
/* CSS cho đánh giá sao */
.star-rating {
    direction: ltr;
}
.star-rating input {
    display: none;
}
.star-rating label {
    display: inline-block;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating input:checked + label,
.star-rating input:checked ~ label {
    color: #f59e0b !important;
}
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #f59e0b !important;
}

/* Hover nút hành động */
.btn-outline-secondary:hover {
    background-color: #f5f5f5 !important;
    color: #333 !important;
    border-color: #999 !important;
}
.btn:not(.btn-outline-secondary):hover {
    background-color: #d43f21 !important;
}

/* Đánh giá giống Shopee */
.reviews-list .border-bottom:last-child {
    border-bottom: none;
}
.alert-dismissible {
    position: relative;
    padding-right: 4rem;
}
.alert-dismissible .btn-close {
    position: absolute;
    top: 0.75rem;
    right: 1rem;
}

/* Sản phẩm liên quan */
.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    transition: box-shadow 0.2s;
}
</style>
@endsection