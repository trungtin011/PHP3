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

        $stats = $this->getBasicStats();

    
        [$revenueData, $labels] = $this->getMonthlyRevenueData();


        $recentOrders = $this->getRecentOrders(5);

        return view('admin.dashboard', array_merge($stats, [
            'revenueData' => $revenueData,
            'labels' => $labels,
            'recentOrders' => $recentOrders,
        ]));
    }

    private function getBasicStats(): array
    {
        return [
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'totalUsers' => User::count(),
            'revenue' => Order::where('status', 'completed')->sum('total'),
        ];
    }

 
    private function getMonthlyRevenueData(): array
    {
        $rawRevenue = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->where('status', 'completed')
            ->whereYear('created_at', 2025)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('revenue', 'month');

        $labels = ['Jan', 'Feb', 'Mar', 'Apr'];
        $revenueData = collect(range(1, 4))
            ->map(function ($month) use ($rawRevenue) {
                return $rawRevenue->get($month, 0);
            })
            ->toArray();

        return [$revenueData, $labels];
    }


    private function getRecentOrders(int $limit = 5)
    {
        return Order::with('user')
            ->orderByDesc('created_at')
            ->take($limit)
            ->get();
    }
}
