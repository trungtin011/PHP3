@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="container-fluid">
    <div class="card shadow-lg border-0 rounded-3" style="background: #ffffff; padding: 30px;">
        <h2 class="mb-4 text-center" style="color: #1f2a44; font-weight: 600; letter-spacing: 1px;">Manage Products</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-4 position-relative">
            <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2 shadow-sm" 
                    placeholder="Search products..." id="search-input"
                    value="{{ request('search') }}" {{-- Preserve search query --}}
                    style="border-radius: 10px; padding: 12px; border: 1px solid #e0e4e9;">
                <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 10px;">Search</button>
            </form>
            <ul class="search-results list-unstyled mt-2 position-absolute bg-white border rounded-3 shadow-lg w-100" 
                id="search-results" style="z-index: 1000; display: none; max-height: 400px; overflow-y: auto;">
            </ul>
        </div>

        <div class="mb-4">
            <a href="{{ route('admin.products.create') }}" class="btn shadow-sm" 
                style="background: linear-gradient(45deg, #ffd700, #ffea80); color: #1f2a44; font-weight: 600; padding: 12px 25px; border-radius: 10px; transition: all 0.3s ease;">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center" style="border-radius: 10px; overflow: hidden;">
                <thead style="background: linear-gradient(135deg, #1f2a44 0%, #141b2d 100%); color: white;">
                    <tr>
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Main Image</th>
                        <th style="padding: 15px;">Title</th>
                        <th style="padding: 15px;">Description</th>
                        <th style="padding: 15px;">Price</th>
                        <th style="padding: 15px;">Stock</th>
                        <th style="padding: 15px;">Status</th>
                        <th style="padding: 15px;">Category</th>
                        <th style="padding: 15px;">Brand</th>
                        <th style="padding: 15px;">Additional Images</th>
                        <th style="padding: 15px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr style="transition: all 0.3s ease;" class="shadow-sm-on-hover">
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->title }}" 
                                        class="img-thumbnail rounded" style="width: 80px; height: 80px; object-fit: cover; border: none;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td style="font-weight: 500;">{{ $product->title }}</td>
                            <td>{{ Str::limit($product->description, 50, '...') }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->status == 'in_stock')
                                    <span class="badge bg-success">In Stock</span>
                                @elseif($product->status == 'out_of_stock')
                                    <span class="badge bg-danger">Out of Stock</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>{{ $product->category->name ?? 'No Category' }}</td>
                            <td>{{ $product->brand->name ?? 'No Brand' }}</td>
                            <td>
                                @if($product->additional_images)
                                    @foreach(json_decode($product->additional_images, true) as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Additional Image" 
                                            class="img-thumbnail mt-2" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endforeach
                                @else
                                    <span class="text-muted">No Additional Images</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                    class="btn btn-sm shadow-sm me-2" 
                                    style="background: #ffd700; color: #1f2a44; border-radius: 8px; transition: all 0.3s ease;">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm shadow-sm" 
                                        style="background: #dc3545; color: white; border-radius: 8px; transition: all 0.3s ease;" 
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    .shadow-sm-on-hover:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .search-results li:hover {
        background: linear-gradient(90deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 215, 0, 0) 100%);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 5px;
        color: #1f2a44;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background: #ffd700;
        border-color: #ffd700;
        color: #1f2a44;
    }

    .pagination .page-link:hover {
        background: #ffd700;
        color: #1f2a44;
    }
</style>

<script>
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length > 0) {
            fetch(`{{ route('admin.products.search') }}?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    searchResults.style.display = 'block';
                    data.forEach(product => {
                        const li = document.createElement('li');
                        li.classList.add('p-3', 'border-bottom', 'd-flex', 'align-items-center');
                        li.style.cursor = 'pointer';
                        li.style.transition = 'all 0.3s ease';

                        const img = document.createElement('img');
                        img.src = product.main_image ? `{{ asset('storage') }}/${product.main_image}` : 'https://via.placeholder.com/50';
                        img.alt = product.title;
                        img.style.width = '60px';
                        img.style.height = '60px';
                        img.style.objectFit = 'cover';
                        img.classList.add('me-3', 'rounded');

                        const div = document.createElement('div');
                        div.innerHTML = `<strong style="color: #1f2a44;">${product.title}</strong><br>
                                        <span style="color: #666;">Price: ${new Intl.NumberFormat().format(product.price)} đ</span><br>
                                        <span style="color: #666;">Stock: ${product.stock}</span>`;

                        li.appendChild(img);
                        li.appendChild(div);

                        li.addEventListener('click', () => {
                            window.location.href = `{{ url('/admin/products') }}/${product.id}/edit`;
                        });

                        searchResults.appendChild(li);
                    });
                });
        } else {
            searchResults.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!searchResults.contains(event.target) && event.target !== searchInput) {
            searchResults.style.display = 'none';
        }
    });
</script>
@endsection