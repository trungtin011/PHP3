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
        <button type="submit" class="btn btn-primary">Update Category</button>
    </form>
</div>
@endsection
