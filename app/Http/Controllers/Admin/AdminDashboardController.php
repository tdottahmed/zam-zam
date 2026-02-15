<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Key Metrics
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount'); // Assuming 'paid' status or similar. Adjust if needed.
        // If payment_status isn't reliable, maybe use status 'completed' or just sum all non-cancelled?
        // Let's assume 'paid' or completed orders count as revenue. 
        // For now, let's sum total_amount of orders that are NOT cancelled.
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('user_type', 'customer')->count(); // Assuming 'user_type' column exists based on plan

        // 2. Sales Chart Data (Last 30 Days)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(29);

        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('status', '!=', 'cancelled')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('total', 'date');

        // Prepare chart data ensuring all dates are present
        $chartLabels = [];
        $chartValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::now()->subDays($i)->format('M d'); // Format for display
            $chartValues[] = $salesData[$date] ?? 0;
        }

        // 3. Recent Orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 4. Low Stock Products
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'alert_quantity')
            ->take(10) // Limit to top 10
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'chartLabels',
            'chartValues',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
