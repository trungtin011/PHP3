@extends('layouts.admin')

@section('title', 'Quản Lý Mã Giảm Giá')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3" style="background: #fff; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #ee4d2d; font-weight: bold; font-size: 24px; margin: 0;">Quản Lý Mã Giảm Giá</h2>
            <a href="{{ route('coupons.create') }}" class="btn"
                style="background: #ee4d2d; color: white; font-weight: 600; padding: 10px 20px; border-radius: 5px;">
                <i class="bi bi-plus-lg me-2"></i>Thêm Mã Giảm Giá
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="background: #d4edda; border: none; border-radius: 5px; color: #155724;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Bảng mã giảm giá -->
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="border-radius: 5px; overflow: hidden;">
                <thead style="background: #f5f5f5; color: #333;">
                    <tr>
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Mã</th>
                        <th style="padding: 15px;">Loại</th>
                        <th style="padding: 15px;">Giá trị</th>
                        <th style="padding: 15px;">Giới hạn sử dụng</th>
                        <th style="padding: 15px;">Đã sử dụng</th>
                        <th style="padding: 15px;">Ngày hết hạn</th>
                        <th style="padding: 15px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;">{{ $coupon->id }}</td>
                        <td style="padding: 15px; font-weight: 500;">{{ $coupon->code }}</td>
                        <td style="padding: 15px;">{{ ucfirst($coupon->discount_type) }}</td>
                        <td style="padding: 15px;">{{ $coupon->discount_value }}</td>
                        <td style="padding: 15px;">{{ $coupon->usage_limit }}</td>
                        <td style="padding: 15px;">{{ $coupon->used_count }}</td>
                        <td style="padding: 15px;">{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : 'Không giới hạn' }}</td>
                        <td style="padding: 15px;">
                            <!-- Nút Edit -->
                            <a href="{{ route('coupons.edit', $coupon->id) }}" 
                                class="btn btn-sm me-2" 
                                style="background: #ffd700; color: #333; border-radius: 5px;" 
                                title="Sửa">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <!-- Nút Delete -->
                            <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="d-inline delete-form">
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
            {{ $coupons->links('pagination::bootstrap-5') }} <!-- Ensure this is used with paginated data -->
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
    // Xác nhận xóa mã giảm giá
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn xóa mã giảm giá này không?')) {
                this.submit();
            }
        });
    });
</script>
@endsection
