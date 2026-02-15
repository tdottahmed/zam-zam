<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $brands = Brand::where('status', true)->take(6)->get();
       
        $categories = Category::where('status', true)->take(6)->get();

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        // Stats
        $totalOrders = \App\Models\Order::where('user_id', $user->id)->count();
        $activeOrders = \App\Models\Order::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled', 'refunded'])
            ->count();
        $wishlistCount = \App\Models\Wishlist::where('user_id', $user->id)->count();
        
        // Recent Orders
        $recentOrders = \App\Models\Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'created_at' => $order->created_at->format('M d, Y'),
                    'status' => $order->status,
                    'total_amount' => $order->total_amount,
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_orders' => $totalOrders,
                'active_orders' => $activeOrders,
                'wishlist_count' => $wishlistCount,
            ],
            'recent_orders' => $recentOrders
        ]);
    }
}
