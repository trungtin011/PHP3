@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Edit Category</h2>
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $category->slug }}" required>
        </div>
        <div class="mb-3">
            <label for="icon" class="form-label">Icon</label>
            <input type="hidden" id="icon" name="icon" value="{{ $category->icon }}">
            <div class="d-flex flex-wrap">
                <div class="p-2">
                    <i class="fas fa-mobile-alt text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-mobile-alt' ? 'selected text-warning' : '' }}" data-icon="fas fa-mobile-alt"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-laptop text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-laptop' ? 'selected text-warning' : '' }}" data-icon="fas fa-laptop"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-headphones-alt text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-headphones-alt' ? 'selected text-warning' : '' }}" data-icon="fas fa-headphones-alt"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-camera text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-camera' ? 'selected text-warning' : '' }}" data-icon="fas fa-camera"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-blender text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-blender' ? 'selected text-warning' : '' }}" data-icon="fas fa-blender"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-plug text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-plug' ? 'selected text-warning' : '' }}" data-icon="fas fa-plug"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-desktop text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-desktop' ? 'selected text-warning' : '' }}" data-icon="fas fa-desktop"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-tv text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-tv' ? 'selected text-warning' : '' }}" data-icon="fas fa-tv"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-sync-alt text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-sync-alt' ? 'selected text-warning' : '' }}" data-icon="fas fa-sync-alt"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-box-open text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-box-open' ? 'selected text-warning' : '' }}" data-icon="fas fa-box-open"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-tags text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-tags' ? 'selected text-warning' : '' }}" data-icon="fas fa-tags"></i>
                </div>
                <div class="p-2">
                    <i class="fas fa-newspaper text-blue-500 text-2xl icon-option {{ $category->icon == 'fas fa-newspaper' ? 'selected text-warning' : '' }}" data-icon="fas fa-newspaper"></i>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Category</label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">None</option> <!-- For top-level categories -->
                @foreach ($categories as $parent)
                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Category</button>
    </form>
</div>
<script>
    document.querySelectorAll('.icon-option').forEach(icon => {
        icon.addEventListener('click', function () {
            document.getElementById('icon').value = this.getAttribute('data-icon');
            document.querySelectorAll('.icon-option').forEach(i => i.classList.remove('selected', 'text-warning'));
            this.classList.add('selected', 'text-warning');
        });
    });
</script>
<style>
    .icon-option {
        cursor: pointer;
        transition: transform 0.2s ease, color 0.2s ease;
    }
    .icon-option:hover {
        transform: scale(1.2);
    }
    .icon-option.selected {
        transform: scale(1.5);
    }
</style>
@endsection
