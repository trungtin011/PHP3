@extends('layouts.admin')

@section('title', 'Edit Brand')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Edit Brand</h2>
    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $brand->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Brand</button>
    </form>
</div>
@endsection
