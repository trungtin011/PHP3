@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Edit Product</h2>
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Basic fields --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $product->title }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $product->slug }}" placeholder="Enter slug (optional)">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
        </div>

    

        <!-- Thêm sau trường price -->
<div class="mb-3">
    <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
    @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="import_price" class="form-label">Giá nhập</label>
    <input type="number" step="0.01" class="form-control @error('import_price') is-invalid @enderror" id="import_price" name="import_price" value="{{ old('import_price', $product->import_price) }}">
    @error('import_price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="in_stock" {{ $product->status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="out_of_stock" {{ $product->status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
        </div>

        {{-- Category & Brand --}}
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="brand_id" class="form-label">Brand</label>
            <select class="form-control" id="brand_id" name="brand_id" required>
                <option value="">Select Brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Main Image --}}
        <div class="mb-3">
            <label for="main_image" class="form-label">Main Image</label>
            <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*" onchange="previewMainImage(event)">
            <div class="mt-2">
                @if($product->main_image)
                    <img id="main_image_preview" src="{{ asset('storage/' . $product->main_image) }}" class="img-thumbnail" style="width: 150px;">
                @else
                    <img id="main_image_preview" class="img-thumbnail" style="width: 150px; display: none;">
                @endif
            </div>
        </div>

        {{-- Additional Images --}}
        <div class="mb-3">
            <label for="additional_images" class="form-label">Additional Images</label>
            <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/*" multiple onchange="previewAdditionalImages(event)">
            <div id="additional_images_preview" class="mt-2">
                @if($product->additional_images && is_array($product->additional_images))
                    @foreach($product->additional_images as $image)
                        <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail me-2 mb-2" style="width: 100px;">
                    @endforeach
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

{{-- JS preview scripts --}}
<script>
    function previewMainImage(event) {
        const preview = document.getElementById('main_image_preview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }

    function previewAdditionalImages(event) {
        const previewContainer = document.getElementById('additional_images_preview');
        previewContainer.innerHTML = '';
        Array.from(event.target.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add('img-thumbnail', 'me-2', 'mb-2');
            img.style.width = '100px';
            previewContainer.appendChild(img);
        });
    }
</script>
@endsection
