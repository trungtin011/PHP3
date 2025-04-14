@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển')

@section('content')
<div class="container py-4">
    <!-- Tiêu đề -->
    <div class="mb-4">
        <h1 class="fs-3 fw-bold text-dark">Bảng Điều Khiển</h1>
        <p class="text-muted small">Tổng quan hoạt động kinh doanh</p>
    </div>

    <!-- Thống kê nhanh -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-3 p-3 bg-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-seam fs-3 text-primary me-3"></i>
                    <div>
                        <p class="mb-0 text-muted small">Sản Phẩm</p>
                        <h5 class="mb-0 fw-bold">{{ $totalProducts }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-3 p-3 bg-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="bi bi-cart4 fs-3 text-success me-3"></i>
                    <div>
                        <p class="mb-0 text-muted small">Đơn Hàng</p>
                        <h5 class="mb-0 fw-bold">{{ $totalOrders }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-3 p-3 bg-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people fs-3 text-info me-3"></i>
                    <div>
                        <p class="mb-0 text-muted small">Người Dùng</p>
                        <h5 class="mb-0 fw-bold">{{ $totalUsers }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-3 p-3 bg-white shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="bi bi-currency-dollar fs-3 text-warning me-3"></i>
                    <div>
                        <p class="mb-0 text-muted small">Doanh Thu</p>
                        <h5 class="mb-0 fw-bold">{{ number_format($revenue, 0, ',', '.') }} đ</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ và đơn hàng -->
    <div class="row g-3">
        <!-- Biểu đồ doanh thu -->
        <div class="col-md-8">
            <div class="card border-0 rounded-3 p-4 bg-white shadow-sm">
                <h6 class="fw-bold mb-3">Doanh Thu Theo Tháng</h6>
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        <div class="col-md-4">
            <div class="card border-0 rounded-3 p-4 bg-white shadow-sm">
                <h6 class="fw-bold mb-3">Đơn Hàng Gần Đây</h6>
                <ul class="list-group list-group-flush">
                    @foreach ($recentOrders as $order)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <div>
                                <strong>#{{ $order->id }}</strong>
                                <small class="d-block text-muted">{{ $order->created_at->format('d/m/Y') }}</small>
                            </div>
                            <span class="badge rounded-pill {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $order->status == 'completed' ? 'Hoàn thành' : ($order->status == 'pending' ? 'Đang xử lý' : 'Thất bại') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ doanh thu
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Doanh Thu (đ)',
                data: @json($revenueData),
                backgroundColor: '#bfdbfe',
                borderColor: '#3b82f6',
                borderWidth: 1,
                borderRadius: 4,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#6b7280',
                        callback: function(value) {
                            return value.toLocaleString() + ' đ';
                        }
                    },
                    grid: { color: '#e5e7eb' }
                },
                x: {
                    ticks: { color: '#6b7280' },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString() + ' đ';
                        }
                    }
                }
            }
        }
    });

    // Hiệu ứng hover cho thẻ
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseover', () => {
            card.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        });
        card.addEventListener('mouseout', () => {
            card.style.boxShadow = '0 2px 6px rgba(0, 0, 0, 0.1)';
        });
    });
</script>

<style>
    /* Tùy chỉnh giao diện */
    body {
        background-color: #f9fafb;
    }
    .card {
        transition: box-shadow 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .list-group-item {
        border: none;
        background: transparent;
    }
    .badge {
        padding: 5px 10px;
        font-size: 0.8rem;
    }
    .text-primary {
        color: #3b82f6 !important;
    }
    .text-success {
        color: #22c55e !important;
    }
    .text-info {
        color: #0ea5e9 !important;
    }
    .text-warning {
        color: #f59e0b !important;
    }
    .bg-success {
        background-color: #22c55e !important;
    }
    .bg-warning {
        background-color: #f59e0b !important;
    }
    .bg-danger {
        background-color: #ef4444 !important;
    }
</style>
@endsection