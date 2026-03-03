<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang thống kê
     * @return \Illuminate\Contracts\View\View
     */
    function index()
    {
        $dashboard = [];

        $dashboard['totalOrders'] = Order::count();
        $dashboard['totalUsers'] = User::where('status', '!=', 'đã xóa')->count();
        $dashboard['totalProducts'] = Product::where('status', '!=', 'đã xóa')->count();
        $dashboard['totalRevenue'] = Order::getTotalAmount();

        $monthlyRevenue = Order::selectRaw('YEAR(order_date) as year, MONTH(order_date) as month, SUM(total_amount) as revenue')
            ->where('status', 'đã nhận hàng')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $monthlyComparison = $this->getMonthlyComparison();

        $dashboard['orders'] = Order::with('user')->orderBy('order_date', 'desc')->limit(5)->get();

        // $monthRevenue = Order::getTotalAmount(now()->startOfMonth(), now()->endOfMonth());

        return view('admin.dashboard.index', compact('dashboard', 'monthlyRevenue', 'monthlyComparison'));
    }

        /**
     * lấy doanh số tháng hiện tại và tháng trước để so sánh
     * @return array{current_month: mixed, growth: float|int, last_month: mixed}
     */
    public function getMonthlyComparison()
    {
        // Tháng hiện tại
        $currentMonthRevenue = Order::where('status', 'đã nhận hàng')
            ->whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->sum('total_amount');

        // Tháng trước
        $lastMonthRevenue = Order::where('status', 'đã nhận hàng')
            ->whereMonth('order_date', now()->subMonth()->month)
            ->whereYear('order_date', now()->subMonth()->year)
            ->sum('total_amount');

        return [
            'current_month' => $currentMonthRevenue,
            'last_month' => $lastMonthRevenue,
            'growth' => $lastMonthRevenue > 0 ?
                (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0
        ];
    }
}
