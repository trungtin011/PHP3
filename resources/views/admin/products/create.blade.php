@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Thêm Sản Phẩm</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 p-4 rounded-lg bg-green-100 text-green-800 flex items-center gap-3 shadow-sm" role="alert">
            <i class="fa-solid fa-check-circle text-lg"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 p-4 rounded-lg bg-red-100 text-red-800 shadow-sm" role="alert">
            <ul class="mb-0 list-disc list-inside">
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
            <label for="title" class="form-label text-sm font-semibold text-gray-700">Tiêu đề <span class="text-orange-shopee">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label text-sm font-semibold text-gray-700">Slug (tùy chọn)</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Tự động tạo nếu để trống">
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label text-sm font-semibold text-gray-700">Mô tả</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label text-sm font-semibold text-gray-700">Giá bán <span class="text-orange-shopee">*</span></label>
            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="import_price" class="form-label text-sm font-semibold text-gray-700">Giá nhập</label>
            <input type="number" step="0.01" class="form-control @error('import_price') is-invalid @enderror" id="import_price" name="import_price" value="{{ old('import_price') }}">
            @error('import_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label text-sm font-semibold text-gray-700">Tồn kho <span class="text-orange-shopee">*</span></label>
            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label text-sm font-semibold text-gray-700">Trạng thái <span class="text-orange-shopee">*</span></label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="in_stock" {{ old('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label text-sm font-semibold text-gray-700">Danh mục <span class="text-orange-shopee">*</span></label>
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
            <label for="brand_id" class="form-label text-sm font-semibold text-gray-700">Thương hiệu <span class="text-orange-shopee">*</span></label>
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
            <label for="main_image" class="form-label text-sm font-semibold text-gray-700">Hình ảnh chính</label>
            <input type="file" class="form-control @error('main_image') is-invalid @enderror" id="main_image" name="main_image" accept="image/*" onchange="previewMainImage(event)">
            @error('main_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <img id="main_image_preview" class="img-thumbnail mt-2" style="max-width: 150px; display: none;" alt="Ảnh chính">
        </div>

        <div class="mb-3">
            <label for="additional_images" class="form-label text-sm font-semibold text-gray-700">Hình ảnh phụ</label>
            <input type="file" class="form-control @error('additional_images.*') is-invalid @enderror" id="additional_images" name="additional_images[]" accept="image/*" multiple onchange="previewAdditionalImages(event)">
            @error('additional_images.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="additional_images_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
        </div>

        <div id="variants-section" class="mb-3">
            <label class="form-label text-sm font-semibold text-gray-700">Biến thể sản phẩm</label>
            <div id="variant-rows">
                <div class="row mb-2">
                    <div class="col">
                        <input type="text" name="variants[0][name]" class="form-control" placeholder="Tên biến thể (e.g., Size)">
                    </div>
                    <div class="col">
                        <input type="text" name="variants[0][value]" class="form-control" placeholder="Giá trị (e.g., Large)">
                    </div>
                    <div class="col">
                        <input type="number" name="variants[0][price]" class="form-control" placeholder="Giá (tùy chọn)">
                    </div>
                    <div class="col">
                        <input type="number" name="variants[0][stock]" class="form-control" placeholder="Tồn kho">
                    </div>
                </div>
            </div>
            <button type="button" id="add-variant" class="btn btn-sm btn-secondary">Thêm biến thể</button>
        </div>

        <div class="mb-3 flex gap-3 justify-end">
            <button type="submit" class="btn bg-orange-shopee text-white font-semibold hover:bg-orange-shopee-hover shadow-sm">
                <i class="fa-solid fa-save mr-2"></i> Thêm sản phẩm
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 border-orange-shopee shadow-sm">
                <i class="fa-solid fa-times mr-2"></i> Hủy
            </a>
        </div>
    </form>
</div>

<style>
    :root {
        --orange-shopee: #EE4D2D;
        --orange-shopee-hover: #F26A2E;
    }
    .text-orange-shopee {
        color: var(--orange-shopee);
    }
    .bg-orange-shopee {
        background-color: var(--orange-shopee) !important;
    }
    .hover\:bg-orange-shopee-hover:hover {
        background-color: var(--orange-shopee-hover) !important;
    }
    .border-orange-shopee {
        border: 1px solid var(--orange-shopee);
    }
    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
        border-color: var(--orange-shopee);
        outline: none;
        box-shadow: 0 0 0 3px rgba(238, 77, 45, 0.2);
    }
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
    .form-label {
        margin-bottom: 0.25rem;
        display: block;
    }
    .btn {
        padding: 0.5rem 1.5rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s;
    }
    .alert-dismissible .btn-close {
        background: none;
        border: none;
        font-size: 1rem;
        cursor: pointer;
        color: inherit;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
            ],
            image: {
                toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
            },
            height: '400px'
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });

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

    let variantIndex = 1;
    document.getElementById('add-variant').addEventListener('click', function () {
        const row = `
            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="variants[${variantIndex}][name]" class="form-control" placeholder="Tên biến thể (e.g., Size)">
                </div>
                <div class="col">
                    <input type="text" name="variants[${variantIndex}][value]" class="form-control" placeholder="Giá trị (e.g., Large)">
                </div>
                <div class="col">
                    <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Giá (tùy chọn)">
                </div>
                <div class="col">
                    <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Tồn kho">
                </div>
            </div>`;
        document.getElementById('variant-rows').insertAdjacentHTML('beforeend', row);
        variantIndex++;
    });
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
