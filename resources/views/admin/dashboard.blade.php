@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-5" style="color: #1E3A8A; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase;">Admin Dashboard</h2>

    <!-- Thống kê nhanh -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4 animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #FBBF24, #FEF08A); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-seam" style="font-size: 2.8rem; color: #1E3A8A; margin-right: 20px;"></i>
                    <div>
                        <h5 class="mb-1" style="color: #1E3A8A; font-weight: 600; text-transform: uppercase;">Total Products</h5>
                        <p class="mb-0" style="font-size: 1.8rem; color: #1E3A8A; font-weight: 700;">{{ $totalProducts ?? 150 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4 animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #10B981, #6EE7B7); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-cart4" style="font-size: 2.8rem; color: #1E3A8A; margin-right: 20px;"></i>
                    <div>
                        <h5 class="mb-1" style="color: #1E3A8A; font-weight: 600; text-transform: uppercase;">Total Orders</h5>
                        <p class="mb-0" style="font-size: 1.8rem; color: #1E3A8A; font-weight: 700;">{{ $totalOrders ?? 75 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4 animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #3B82F6, #93C5FD); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people" style="font-size: 2.8rem; color: #1E3A8A; margin-right: 20px;"></i>
                    <div>
                        <h5 class="mb-1" style="color: #1E3A8A; font-weight: 600; text-transform: uppercase;">Total Users</h5>
                        <p class="mb-0" style="font-size: 1.8rem; color: #1E3A8A; font-weight: 700;">{{ $totalUsers ?? 200 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4 animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #EF4444, #FCA5A5); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-currency-dollar" style="font-size: 2.8rem; color: #1E3A8A; margin-right: 20px;"></i>
                    <div>
                        <h5 class="mb-1" style="color: #1E3A8A; font-weight: 600; text-transform: uppercase;">Revenue</h5>
                        <p class="mb-0" style="font-size: 1.8rem; color: #1E3A8A; font-weight: 700;">{{ $revenue ?? '5,000,000' }} đ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ và danh sách -->
    <div class="row g-4">
        <!-- Biểu đồ doanh thu -->
        <div class="col-md-8">
            <div class="card shadow-lg rounded-3 p-4 animate__animated animate__fadeInUp" style="background: #FFFFFF; border: none;">
                <h5 class="mb-4" style="color: #1E3A8A; font-weight: 600; letter-spacing: 0.5px;">Revenue Overview</h5>
                <canvas id="revenueChart" height="350"></canvas>
            </div>
        </div>

        <!-- Danh sách đơn hàng gần đây -->
        <div class="col-md-4">
            <div class="card shadow-lg rounded-3 p-4 animate__animated animate__fadeInUp" style="background: #FFFFFF; border: none; height: 100%;">
                <h5 class="mb-4" style="color: #1E3A8A; font-weight: 600; letter-spacing: 0.5px;">Recent Orders</h5>
                <ul class="list-unstyled" style="max-height: 350px; overflow-y: auto;">
                    @for ($i = 1; $i <= 5; $i++)
                        <li class="d-flex justify-content-between align-items-center p-3 mb-2 rounded-3" style="background: #F9FAFB; transition: all 0.3s ease;">
                            <div>
                                <strong style="color: #1E3A8A; font-weight: 600;">Order #{{ 1000 + $i }}</strong><br>
                                <small style="color: #6B7280;">{{ now()->subDays($i)->format('d/m/Y') }}</small>
                            </div>
                            <span class="badge" style="background: #10B981; padding: 8px 12px; border-radius: 20px; color: #fff; font-weight: 500;">Completed</span>
                        </li>
                    @endfor
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ doanh thu
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue (đ)',
                data: [1200000, 1900000, 3000000, 5000000, 2000000, 3000000],
                borderColor: '#FBBF24',
                backgroundColor: 'rgba(251, 191, 36, 0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 4,
                pointBackgroundColor: '#FBBF24',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#1E3A8A',
                        font: { size: 14 },
                        callback: function(value) {
                            return value.toLocaleString() + ' đ';
                        }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    ticks: { color: '#1E3A8A', font: { size: 14 } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#1E3A8A',
                        font: { size: 14 }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Hover effect cho danh sách đơn hàng
    document.querySelectorAll('.list-unstyled li').forEach(item => {
        item.addEventListener('mouseover', () => {
            item.style.background = 'rgba(251, 191, 36, 0.1)';
            item.style.transform = 'translateX(5px)';
        });
        item.addEventListener('mouseout', () => {
            item.style.background = '#F9FAFB';
            item.style.transform = 'translateX(0)';
        });
    });
</script>

<style>
    .card {
        transition: all 0.4s ease;
        border-radius: 12px;
    }
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
    }
    .list-unstyled li {
        transition: all 0.3s ease;
    }
    .list-unstyled li:hover {
        background: rgba(251, 191, 36, 0.1);
        transform: translateX(5px);
    }
    h2, h5 {
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection