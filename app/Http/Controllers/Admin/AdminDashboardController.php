<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Tổng sản phẩm
        $totalProducts = Product::count();

        // Tổng đơn hàng
        $totalOrders = Order::count();

        // Tổng người dùng
        $totalUsers = User::count();

        // Tổng doanh thu (chỉ tính đơn hàng completed)
        $revenue = Order::where('status', 'completed')
            ->sum('total');

        // Doanh thu theo tháng (từ tháng 1 đến tháng 4 năm 2025)
        $monthlyRevenue = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('status', 'completed')
            ->whereYear('created_at', 2025)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('revenue', 'month')
            ->toArray();

        // Tạo mảng dữ liệu cho biểu đồ (mặc định 0 cho các tháng không có doanh thu)
        $revenueData = [];
        $labels = ['Jan', 'Feb', 'Mar', 'Apr'];
        for ($i = 1; $i <= 4; $i++) {
            $revenueData[] = isset($monthlyRevenue[$i]) ? $monthlyRevenue[$i] : 0;
        }

        // 5 đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'revenue',
            'revenueData',
            'labels',
            'recentOrders'
        ));
    }
}