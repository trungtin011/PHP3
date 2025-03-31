@extends('layouts.admin')

@section('title', 'Quản Lý Thương Hiệu')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3" style="background: #fff; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #ee4d2d; font-weight: bold; font-size: 24px; margin: 0;">Quản Lý Thương Hiệu</h2>
            <a href="{{ route('admin.brands.create') }}" class="btn"
                style="background: #ee4d2d; color: white; font-weight: 600; padding: 10px 20px; border-radius: 5px;">
                <i class="bi bi-plus-lg me-2"></i>Thêm Thương Hiệu
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="background: #d4edda; border: none; border-radius: 5px; color: #155724;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Bảng thương hiệu -->
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="border-radius: 5px; overflow: hidden;">
                <thead style="background: #f5f5f5; color: #333;">
                    <tr>
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Tên thương hiệu</th>
                        <th style="padding: 15px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;">{{ $brand->id }}</td>
                        <td style="padding: 15px; font-weight: 500;">{{ Str::limit($brand->name, 30, '...') }}</td>
                        <td style="padding: 15px;">
                            <!-- Nút Edit -->
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                class="btn btn-sm me-2" 
                                style="background: #ffd700; color: #333; border-radius: 5px;" 
                                title="Sửa">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <!-- Nút Delete -->
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" 
                                    style="background: #dc3545; color: white; border-radius: 5px;" 
                                    title="Xóa">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-4">
            {{ $brands->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background: #fafafa;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .pagination .page-link {
        border-radius: 5px;
        color: #ee4d2d;
        margin: 0 5px;
    }

    .pagination .page-item.active .page-link {
        background: #ee4d2d;
        border-color: #ee4d2d;
        color: white;
    }

    .pagination .page-link:hover {
        background: #ee4d2d;
        color: white;
    }
</style>

<script>
    // Xác nhận xóa thương hiệu
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn xóa thương hiệu này không?')) {
                this.submit();
            }
        });
    });
</script>
@endsection