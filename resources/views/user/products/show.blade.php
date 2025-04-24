@extends('layouts.user')

@section('title', $product->title)

@section('content')
<div class="container mt-5">
    <div class="row bg-white p-4 rounded-3 shadow-sm">
        <div class="col-md-6 mb-4">
            <!-- Hình ảnh chính -->
            <div class="position-relative mb-3" style="height: 400px; background-color: #f5f5f5;">
                <img src="{{ asset('storage/' . $product->main_image) }}" 
                     class="img-fluid rounded main-image" 
                     id="main-image"
                     alt="{{ $product->title }}" 
                     style="object-fit: contain; max-height: 100%;">
            </div>
            
            <!-- Hình ảnh phụ -->
            @if(!empty($product->additional_images))
                <div class="d-flex flex-wrap gap-2">
                @foreach($product->additional_images as $image)
                    <div class="border rounded product-image-thumbnail" style="width: 80px; height: 80px; cursor: pointer; transition: border-color 0.2s;">
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
                <span>Đã bán {{ $product->sold_count ?? '0' }}</span>
            </div>

            <!-- Giá -->
            <div class="bg-light p-3 rounded-2 mb-3">
                <span class="text-danger fw-bold" style="font-size: 2rem;" id="variant-price">
                    {{ number_format($product->price, 0, ',', '.') }} đ
                </span>
                @if($product->discount > 0)
                    <span class="text-muted text-decoration-line-through me-2" style="font-size: 1.2rem;">
                        {{ number_format($product->price, 0, ',', '.') }} đ
                    </span>
                    <span class="bg-danger text-white px-2 py-1 ms-2 rounded" style="font-size: 0.9rem;">
                        -{{ $product->discount }}%
                    </span>
                @endif
            </div>
            <div class="mb-4">
                <p class="mb-2"><strong>Tình trạng:</strong> 
                    <span id="variant-stock" class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                    </span>
                </p>
                <p class="mb-2"><strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'N/A' }}</p>    
                <p class="mb-2"><strong>Tồn kho:</strong> <span id="variant-stock-count">{{ $product->stock }}</span></p>
                <p class="mb-2"><strong>Nguồn gốc:</strong> {{ $product->location ?? 'TP. Hồ Chí Minh' }}</p>
            </div>

            <!-- Form chọn biến thể giống Shopee -->
            <div class="mb-4">
                <div class="variant-selector">
                    @if ($product->variants->isNotEmpty())
                        @php
                            // Nhóm biến thể theo thuộc tính (ví dụ: Màu sắc, Kích thước)
                            $variantGroups = $product->variants->groupBy('name');
                        @endphp
                        @foreach ($variantGroups as $name => $variants)
                            <div class="mb-3">
                                <label class="fw-bold mb-2">{{ $name }}</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($variants as $variant)
                                        <button type="button" 
                                                class="variant-btn btn btn-outline-secondary {{ $variant->stock <= 0 ? 'disabled' : '' }}"
                                                data-variant-id="{{ $variant->id }}"
                                                data-price="{{ $variant->price }}"
                                                data-stock="{{ $variant->stock }}"
                                                data-image="{{ $variant->image ? asset('storage/' . $variant->image) : '' }}"
                                                style="min-width: 80px; text-align: center;">
                                            {{ $variant->value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Không có biến thể nào.</p>
                    @endif
                </div>
                @error('variant_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Form thêm vào giỏ hàng -->
            <div class="d-flex gap-3">
                <a href="{{ route('products.list') }}" 
                   class="px-4 py-1" 
                   style="color: #333; border: 1px solid #000; border-radius: 50px; background-color: #fff; transition: all 0.3s; display: flex; align-items: center; justify-content: center; height: 40px;">
                    Quay lại
                </a>
                <form action="{{ route('user.cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="variant_id" id="selected_variant_id">
                    <button type="submit" 
                            class="btn text-white px-4 py-2" 
                            id="add-to-cart-btn"
                            style="background-color: #ee4d2d; border: none; border-radius: 4px;"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock <= 0 ? 'Hết hàng' : 'Thêm vào giỏ hàng' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mô tả chi tiết -->
    <div class="bg-white p-4 rounded-3 shadow-sm mt-4 product-description">
        <h4 class="fw-bold mb-3" style="color: #ee4d2d;">Chi tiết sản phẩm</h4>
        <div>{!! $product->detailed_description ?? $product->description !!}</div>
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
                                style="background-color: #ee4d2d; border: none; border-radius: 4px;">
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
                        <div>
                            <img src="{{ $review->user->avatar ?? asset('images/default-avatar.png') }}" 
                                 class="rounded-circle" 
                                 style="width: 40px; height: 40px; object-fit: cover;" 
                                 alt="{{ $review->user->name }}">
                        </div>
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
                                         class="card-img-top img-fluid" 
                                         alt="{{ $related->title }}" 
                                         style="object-fit: contain; max-height: 100%;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title" style="font-size: 1rem; color: #333;">
                                        {{ $related->title }}
                                    </h5>
                                    <p class="card-text" style="font-size: 0.875rem; color: #666;">
                                        {{ number_format($related->price, 0, ',', '.') }} đ
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
// Chuyển đổi hình ảnh chính khi nhấp vào hình ảnh phụ
document.querySelectorAll('.product-image-thumbnail').forEach(function(thumbnail) {
    thumbnail.addEventListener('click', function() {
        const mainImage = document.querySelector('.main-image');
        mainImage.src = thumbnail.querySelector('img').src;
    });
});

// Xử lý chọn biến thể
document.querySelectorAll('.variant-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Bỏ chọn các nút khác trong cùng nhóm
        const parent = this.closest('.mb-3');
        parent.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('active'));
        // Đánh dấu nút được chọn
        this.classList.add('active');

        // Cập nhật giá, tồn kho, hình ảnh
        const price = this.dataset.price;
        const stock = parseInt(this.dataset.stock);
        const image = this.dataset.image;
        const variantId = this.dataset.variantId;

        // Cập nhật giá
        const priceElement = document.getElementById('variant-price');
        priceElement.textContent = `${parseInt(price).toLocaleString('vi-VN')} đ`;

        // Cập nhật trạng thái tồn kho
        const stockElement = document.getElementById('variant-stock');
        const stockCountElement = document.getElementById('variant-stock-count');
        stockElement.textContent = stock > 0 ? 'Còn hàng' : 'Hết hàng';
        stockElement.className = stock > 0 ? 'text-success' : 'text-danger';
        stockCountElement.textContent = stock;

        // Cập nhật hình ảnh nếu có
        if (image) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = image;
        }

        // Cập nhật variant_id cho form
        document.getElementById('selected_variant_id').value = variantId;

        // Kích hoạt/vô hiệu hóa nút thêm vào giỏ hàng
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        addToCartBtn.disabled = stock <= 0;
        addToCartBtn.textContent = stock <= 0 ? 'Hết hàng' : 'Thêm vào giỏ hàng';
    });
});
</script>
@endsection

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
.btn:not(.btn-outline-secondary):hover {
    background-color: #f26a2e !important;
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
    background-color: #ee4d2d;
    color: #fff;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

/* Sản phẩm liên quan */
.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    transition: box-shadow 0.2s;
}

/* Mô tả sản phẩm giống Word */
.product-description p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #333;
}
.product-description ul, .product-description ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}
.product-description ul li {
    list-style-type: disc;
    margin-bottom: 0.5rem;
}
.product-description ol li {
    list-style-type: decimal;
    margin-bottom: 0.5rem;
}
.product-description strong {
    font-weight: 700;
}
.product-description em {
    font-style: italic;
}
.product-description img {
    max-width: 100%;
    height: auto;
    margin: 0.5rem 0;
}
.product-description table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}
.product-description table th, .product-description table td {
    border: 1px solid #ddd;
    padding: 0.5rem;
    text-align: left;
}

/* CSS cho biến thể giống Shopee */
.variant-btn {
    border-radius: 4px;
    padding: 6px 12px;
    font-size: 0.9rem;
    transition: all 0.2s;
}
.variant-btn.active {
    background-color: #ee4d2d !important;
    color: #fff !important;
    border-color: #ee4d2d !important;
}
.variant-btn:hover:not(.disabled) {
    border-color: #ee4d2d;
    color: #ee4d2d;
}
.variant-btn.disabled {
    background-color: #f5f5f5;
    color: #999;
    cursor: not-allowed;
}
</style>