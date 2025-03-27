@extends('layouts.admin')

@section('title', 'Manage Brands')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Manage Brands</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">Add Brand</a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $brand)
                <tr>
                    <td>{{ $brand->id }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>
                        <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline">
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
        {{ $brands->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
