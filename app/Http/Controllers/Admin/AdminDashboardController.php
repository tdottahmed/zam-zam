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
    public function index(Request $request)
    {
        $dateFilter = $request->query('date_filter', 'last_30_days');
        
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(29)->startOfDay(); // Default

        switch ($dateFilter) {
            case 'today':
                $startDate = Carbon::today();
                break;
            case 'last_3_days':
                $startDate = Carbon::today()->subDays(2);
                break;
            case 'week':
                $startDate = Carbon::today()->subDays(6);
                break;
            case 'month':
                $startDate = Carbon::today()->subDays(29);
                break;
            case 'last_30_days':
                $startDate = Carbon::today()->subDays(29);
                break;
            case 'last_50_days':
                $startDate = Carbon::today()->subDays(49);
                break;
            case 'year':
                $startDate = Carbon::today()->subDays(364);
                break;
            case 'all_time':
                $firstOrder = \App\Models\Order::orderBy('created_at', 'asc')->first();
                $startDate = $firstOrder ? $firstOrder->created_at->startOfDay() : Carbon::create(2000, 1, 1);
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                }
                break;
        }

        // 1. Key Metrics
        $totalRevenue = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalProducts = Product::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalCustomers = User::where('user_type', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])->count(); 

        // 2. Sales Chart Data
        $diffInDays = $startDate->diffInDays($endDate);
        $groupBy = $diffInDays > 60 ? 'month' : 'day';

        $salesQuery = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // SQLite DATE_FORMAT equivalent is strftime, but let's use standard MySQL/SQLite compatible or just pure Laravel pluck.
        // Actually, SQLite doesn't support DATE_FORMAT. MySQL does. PostgreSQL does TO_CHAR.
        // To be safe across DBs, we can fetch all created_at and total_amount, then group in PHP if data isn't huge, 
        // OR use standard generic SQL. In Laravel, it's often safer to just pluck raw date formats if DB is known.
        // Let's group in PHP to be 100% DB agnostic, as dashboard data within a year is usually manageable.
        
        $ordersForChart = $salesQuery->select('created_at', 'total_amount')->get();
        
        $salesData = [];
        foreach ($ordersForChart as $order) {
            $key = $groupBy === 'month' ? $order->created_at->format('Y-m') : $order->created_at->format('Y-m-d');
            if (!isset($salesData[$key])) {
                $salesData[$key] = 0;
            }
            $salesData[$key] += $order->total_amount;
        }

        // Prepare chart data ensuring all dates/months are present
        $chartLabels = [];
        $chartValues = [];

        if ($groupBy === 'month') {
            $currentDate = $startDate->copy()->startOfMonth();
            $end = $endDate->copy()->endOfMonth();
            while ($currentDate <= $end) {
                $monthKey = $currentDate->format('Y-m');
                $chartLabels[] = $currentDate->format('M Y');
                $chartValues[] = $salesData[$monthKey] ?? 0;
                $currentDate->addMonth();
            }
        } else {
            $currentDate = $startDate->copy()->startOfDay();
            $end = $endDate->copy()->startOfDay();
            while ($currentDate <= $end) {
                $dateKey = $currentDate->format('Y-m-d');
                $chartLabels[] = $currentDate->format('M d');
                $chartValues[] = $salesData[$dateKey] ?? 0;
                $currentDate->addDay();
            }
        }

        // 3. Recent Orders
        $recentOrders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();

        // 4. Low Stock Products - Usually independent of date filter
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'alert_quantity')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'chartLabels',
            'chartValues',
            'recentOrders',
            'lowStockProducts',
            'dateFilter',
            'startDate',
            'endDate'
        ));
    }
}
