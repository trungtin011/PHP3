@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Manage Categories</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Icon</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        {{ $category->name }}
                        @if ($category->parent)
                            <small>(Child of {{ $category->parent->name }})</small>
                        @endif
                    </td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        @if ($category->icon)
                            <i class="{{ $category->icon }} text-blue-500 text-lg"></i>
                        @else
                            <span class="text-muted">No Icon</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
