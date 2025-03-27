@extends('layouts.user')

@section('title', 'Product List')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Product List</h2>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/150' }}" class="card-img-top" alt="{{ $product->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                        <p class="card-text">
                            @if($product->status == 'in_stock')
                                <span class="badge bg-success">In Stock</span>
                            @elseif($product->status == 'out_of_stock')
                                <span class="badge bg-danger">Out of Stock</span>
                            @else
                                <span class="badge bg-secondary">Unknown</span>
                            @endif
                        </p>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
