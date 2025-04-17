@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Thêm Sản Phẩm</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug (tùy chọn)</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Tự động tạo nếu để trống">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="import_price" class="form-label">Giá nhập</label>
            <input type="number" step="0.01" class="form-control @error('import_price') is-invalid @enderror" id="import_price" name="import_price" value="{{ old('import_price') }}">
            @error('import_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Tồn kho <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="in_stock" {{ old('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                <option value="">Chọn danh mục</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @if ($category->children->isNotEmpty())
                        @foreach ($category->children as $child)
                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                  └ {{ $child->name }}
                            </option>
                        @endforeach
                    @endif
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="brand_id" class="form-label">Thương hiệu <span class="text-danger">*</span></label>
            <select class="form-control @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                <option value="">Chọn thương hiệu</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="main_image" class="form-label">Hình ảnh chính</label>
            <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image" accept="image/*" onchange="previewMainImage(event)">
            @error('main_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <img id="main_image_preview" class="img-thumbnail mt-2" style="max-width: 150px; display: none;" alt="Ảnh chính">
        </div>

        <div class="mb-3">
            <label for="additional_images" class="form-label">Hình ảnh phụ</label>
            <input type="file" class="form-control @error('additional_images.*') is-invalid @enderror" id="additional_images" name="additional_images[]" accept="image/*" multiple onchange="previewAdditionalImages(event)">
            @error('additional_images.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="additional_images_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<script>
    function previewMainImage(event) {
        const preview = document.getElementById('main_image_preview');
        if (event.target.files[0]) {
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    function previewAdditionalImages(event) {
        const previewContainer = document.getElementById('additional_images_preview');
        previewContainer.innerHTML = '';
        Array.from(event.target.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add('img-thumbnail');
            img.style.maxWidth = '100px';
            previewContainer.appendChild(img);
        });
    }

    // Auto-generate slug based on title
    document.getElementById('title').addEventListener('input', function() {
        const slugInput = document.getElementById('slug');
        if (!slugInput.value || slugInput.value === slugInput.dataset.original) {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            slugInput.value = slug;
            slugInput.dataset.original = slug;
        }
    });
</script>
@endsection