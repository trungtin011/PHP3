@extends('layouts.admin')

@section('title', 'Add Brand')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Add Brand</h2>
    <form action="{{ route('admin.brands.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Brand</button>
    </form>
</div>
@endsection
