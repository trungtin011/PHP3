@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Add Product</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter slug (optional)">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="in_stock">In Stock</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id">
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="brand_id" class="form-label">Brand</label>
            <select class="form-control" id="brand_id" name="brand_id">
                <option value="">Select Brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="main_image" class="form-label">Main Image</label>
            <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*" onchange="previewMainImage(event)">
            <img id="main_image_preview" class="img-thumbnail mt-2" style="width: 150px; display: none;">
        </div>
        <div class="mb-3">
            <label for="additional_images" class="form-label">Additional Images</label>
            <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/*" multiple onchange="previewAdditionalImages(event)">
            <div id="additional_images_preview" class="mt-2"></div>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<script>
    function previewMainImage(event) {
        const preview = document.getElementById('main_image_preview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.style.display = 'block';
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
