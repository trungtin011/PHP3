@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3" style="background: #fff; padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #ee4d2d; font-weight: bold; font-size: 24px; margin: 0;">Quản Lý Đơn Hàng</h2>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="background: #d4edda; border: none; border-radius: 5px; color: #155724;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle" style="border-radius: 5px; overflow: hidden;">
                <thead style="background: #f5f5f5; color: #333;">
                    <tr>
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Khách Hàng</th>
                    
                        <th style="padding: 15px;">Tổng Tiền</th>
                        <th style="padding: 15px;">Trạng Thái</th>
                        <th style="padding: 15px;">Ngày Tạo</th>
                        <th style="padding: 15px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;">{{ $order->id }}</td>
                        <td style="padding: 15px;">{{ $order->user->name }}</td>
                        <td style="padding: 15px;">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                        <td style="padding: 15px;">
                            @php
                                $statusTranslations = [
                                    'pending' => 'Chờ xử lý',
                                    'processing' => 'Đang xử lý',
                                    'completed' => 'Hoàn thành',
                                    'canceled' => 'Đã hủy',
                                ];
                            @endphp
                            {{ $statusTranslations[$order->status] ?? $order->status }}
                        </td>
                        <td style="padding: 15px;">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td style="padding: 15px;">
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                class="btn btn-sm" 
                                style="background: #ffd700; color: #333; border-radius: 5px;" 
                                title="Xem">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline delete-form">
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

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn xóa đơn hàng này không?')) {
                this.submit();
            }
        });
    });
</script>
@endsection
