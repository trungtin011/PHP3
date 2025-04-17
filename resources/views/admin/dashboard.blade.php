@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển')

@section('content')
<div class="container py-5">
    <!-- Tiêu đề -->
    <header class="mb-5">
        <h1 class="fs-2 fw-bold text-dark">Bảng Điều Khiển</h1>
        <p class="text-secondary mt-1">Tổng quan hoạt động kinh doanh của bạn</p>
    </header>

    <!-- Thống kê nhanh -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-white border-0 shadow-sm rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-seam text-primary me-3"></i>
                    <div>
                        <span class="text-muted small">Sản Phẩm</span>
                        <h5 class="mt-1 mb-0 fw-bold">{{ $totalProducts }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-white border-0 shadow-sm rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-cart4 text-success me-3"></i>
                    <div>
                        <span class="text-muted small">Đơn Hàng</span>
                        <h5 class="mt-1 mb-0 fw-bold">{{ $totalOrders }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-white border-0 shadow-sm rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people text-info me-3"></i>
                    <div>
                        <span class="text-muted small">Người Dùng</span>
                        <h5 class="mt-1 mb-0 fw-bold">{{ $totalUsers }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-white border-0 shadow-sm rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-currency-dollar text-warning me-3"></i>
                    <div>
                        <span class="text-muted small">Doanh Thu</span>
                        <h5 class="mt-1 mb-0 fw-bold">{{ number_format($revenue, 0, ',', '.') }} đ</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ và đơn hàng -->
    <div class="row g-4">
        <!-- Biểu đồ doanh thu -->
        <div class="col-lg-8">
            <div class="card bg-white border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 1.3rem;">Doanh Thu Theo Tháng</h6>
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        <div class="col-lg-4">
            <div class="card bg-white border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 1.3rem;">Đơn Hàng Gần Đây</h6>
                <div class="order-list">
                    @foreach ($recentOrders as $order)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="fw-medium text-dark">#{{ $order->id }}</span>
                                <small class="d-block text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <span class="badge {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning' : 'bg-danger') }} text-white">
                                {{ $order->status == 'completed' ? 'Hoàn thành' : ($order->status == 'pending' ? 'Đang xử lý' : 'Thất bại') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Doanh Thu (đ)',
                data: @json($revenueData),
                backgroundColor: 'rgba(99, 102, 241, 0.3)',
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 10,
                barThickness: 28
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
                    backgroundColor: '#111827',
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            return 'Doanh Thu: ' + context.parsed.y.toLocaleString() + ' đ';
                        }
                    }
                }
            },
            animation: {
                duration: 800,
                easing: 'easeOutCubic'
            }
        }
    });
</script>

<style>
    body {
        background-color: #f8fafc;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .container {
        max-width: 1440px;
    }

    .card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
    }

    h1 {
        font-size: 2.5rem;
    }

    .fs-2 {
        font-size: 2rem !important;
    }

    h5 {
        font-size: 1.75rem;
        font-weight: 700;
    }

    h6 {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .badge {
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    i[class^="bi"] {
        font-size: 2.2rem !important;
    }

    .order-list {
        max-height: 300px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f8fafc;
    }

    .order-list::-webkit-scrollbar {
        width: 6px;
    }

    .order-list::-webkit-scrollbar-track {
        background: #f8fafc;
    }

    .order-list::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .text-primary { color: #6366f1 !important; }
    .text-success { color: #22c55e !important; }
    .text-info { color: #0ea5e9 !important; }
    .text-warning { color: #f59e0b !important; }
    .bg-success { background-color: #22c55e !important; }
    .bg-warning { background-color: #f59e0b !important; }
    .bg-danger { background-color: #ef4444 !important; }

    @media (max-width: 768px) {
        .fs-2 {
            font-size: 1.5rem !important;
        }

        .card {
            padding: 1rem;
        }

        h1 {
            font-size: 2rem;
        }

        h5 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
