@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4" style="color: #1f2a44; font-weight: 600; letter-spacing: 1px;">Admin Dashboard</h2>

    <!-- Thống kê nhanh -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4" style="background: linear-gradient(135deg, #ffd700, #ffea80); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-seam" style="font-size: 2.5rem; color: #1f2a44; margin-right: 15px;"></i>
                    <div>
                        <h5 class="mb-0" style="color: #1f2a44; font-weight: 600;">Total Products</h5>
                        <p class="mb-0" style="font-size: 1.5rem; color: #1f2a44; font-weight: 700;">{{ $totalProducts ?? 150 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4" style="background: linear-gradient(135deg, #28a745, #80ffaa); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-cart4" style="font-size: 2.5rem; color: #1f2a44; margin-right: 15px;"></i>
                    <div>
                        <h5 class="mb-0" style="color: #1f2a44; font-weight: 600;">Total Orders</h5>
                        <p class="mb-0" style="font-size: 1.5rem; color: #1f2a44; font-weight: 700;">{{ $totalOrders ?? 75 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4" style="background: linear-gradient(135deg, #007bff, #00c4ff); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people" style="font-size: 2.5rem; color: #1f2a44; margin-right: 15px;"></i>
                    <div>
                        <h5 class="mb-0" style="color: #1f2a44; font-weight: 600;">Total Users</h5>
                        <p class="mb-0" style="font-size: 1.5rem; color: #1f2a44; font-weight: 700;">{{ $totalUsers ?? 200 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg rounded-3 p-4" style="background: linear-gradient(135deg, #dc3545, #ff7582); border: none;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-currency-dollar" style="font-size: 2.5rem; color: #1f2a44; margin-right: 15px;"></i>
                    <div>
                        <h5 class="mb-0" style="color: #1f2a44; font-weight: 600;">Revenue</h5>
                        <p class="mb-0" style="font-size: 1.5rem; color: #1f2a44; font-weight: 700;">{{ $revenue ?? '5,000,000' }} đ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ và danh sách -->
    <div class="row g-4">
        <!-- Biểu đồ doanh thu -->
        <div class="col-md-8">
            <div class="card shadow-lg rounded-3 p-4" style="background: #ffffff; border: none;">
                <h5 class="mb-3" style="color: #1f2a44; font-weight: 600;">Revenue Overview</h5>
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>

        <!-- Danh sách đơn hàng gần đây -->
        <div class="col-md-4">
            <div class="card shadow-lg rounded-3 p-4" style="background: #ffffff; border: none; height: 100%;">
                <h5 class="mb-3" style="color: #1f2a44; font-weight: 600;">Recent Orders</h5>
                <ul class="list-unstyled" style="max-height: 300px; overflow-y: auto;">
                    @for ($i = 1; $i <= 5; $i++)
                        <li class="d-flex justify-content-between align-items-center p-2" style="transition: all 0.3s ease;">
                            <div>
                                <strong style="color: #1f2a44;">Order #{{ 1000 + $i }}</strong><br>
                                <small style="color: #6c757d;">{{ now()->subDays($i)->format('d/m/Y') }}</small>
                            </div>
                            <span class="badge" style="background: #28a745; padding: 6px 10px; border-radius: 20px;">Completed</span>
                        </li>
                    @endfor
                </ul>
            </div>
        </div>
    </div>
</div>

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
                borderColor: '#ffd700',
                backgroundColor: 'rgba(255, 215, 0, 0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' đ';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#1f2a44'
                    }
                }
            }
        }
    });

    // Hover effect cho danh sách đơn hàng
    document.querySelectorAll('.list-unstyled li').forEach(item => {
        item.addEventListener('mouseover', () => {
            item.style.background = 'rgba(255, 215, 0, 0.1)';
        });
        item.addEventListener('mouseout', () => {
            item.style.background = 'transparent';
        });
    });
</script>

<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    .list-unstyled li:hover {
        background: rgba(255, 215, 0, 0.1);
    }
</style>
@endsection