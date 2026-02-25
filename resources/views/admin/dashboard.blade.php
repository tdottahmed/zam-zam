@extends('layouts.admin')

@section('header')
    Dashboard
@endsection

@section('content')
    
    <!-- Filter Toolbar -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Dashboard Overview</h2>
            <p class="text-sm text-gray-500">Analytics and data for your selected period.</p>
        </div>
        
        <form id="date-filter-form" method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-3">
            <select name="date_filter" id="date_filter" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 shadow-sm transition-colors cursor-pointer outline-none">
                <option value="today" {{ (isset($dateFilter) && $dateFilter === 'today') ? 'selected' : '' }}>Today</option>
                <option value="last_3_days" {{ (isset($dateFilter) && $dateFilter === 'last_3_days') ? 'selected' : '' }}>Last 3 Days</option>
                <option value="week" {{ (isset($dateFilter) && $dateFilter === 'week') ? 'selected' : '' }}>Last 7 Days</option>
                <option value="month" {{ (isset($dateFilter) && $dateFilter === 'month') ? 'selected' : '' }}>This Month</option>
                <option value="last_30_days" {{ (!isset($dateFilter) || $dateFilter === 'last_30_days') ? 'selected' : '' }}>Last 30 Days</option>
                <option value="last_50_days" {{ (isset($dateFilter) && $dateFilter === 'last_50_days') ? 'selected' : '' }}>Last 50 Days</option>
                <option value="year" {{ (isset($dateFilter) && $dateFilter === 'year') ? 'selected' : '' }}>This Year</option>
                <option value="all_time" {{ (isset($dateFilter) && $dateFilter === 'all_time') ? 'selected' : '' }}>All Time</option>
                <option value="custom" {{ (isset($dateFilter) && $dateFilter === 'custom') ? 'selected' : '' }}>Custom Range</option>
            </select>

            <div id="custom-date-range" class="flex items-center gap-2 transition-all duration-300" style="{{ (isset($dateFilter) && $dateFilter === 'custom') ? '' : 'display: none;' }}">
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 shadow-sm outline-none">
                <span class="text-gray-500 text-sm font-medium">to</span>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 shadow-sm outline-none">
                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-4 py-2.5 focus:outline-none shadow-sm transition-colors">
                    Apply
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-50 text-green-600 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+12.5%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">${{ number_format($totalRevenue, 2) }}</h3>
            <p class="text-sm text-gray-500">Total Revenue</p>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-50 text-blue-600 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">+5.2%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalOrders) }}</h3>
            <p class="text-sm text-gray-500">Total Orders</p>
        </div>

        <!-- Products Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-50 text-purple-600 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Active</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalProducts) }}</h3>
            <p class="text-sm text-gray-500">Total Products</p>
        </div>

        <!-- Customers Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-orange-50 text-orange-600 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">Avg</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalCustomers) }}</h3>
            <p class="text-sm text-gray-500">Total Customers</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Sales Overview</h3>
            <div class="relative h-80 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions & Low Stock -->
        <div class="space-y-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-gray-100 group">
                        <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-600">Add Product</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-gray-100 group">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-600">View Orders</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-gray-100 group">
                        <div class="w-10 h-10 bg-pink-100 text-pink-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-600">Categories</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-gray-100 group">
                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-600">Customers</span>
                    </a>
                </div>
            </div>

            <!-- Low Stock Alerts -->
             <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Low Stock Alerts</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $product->name }}</h4>
                                <p class="text-xs text-red-600">Only {{ $product->quantity }} left</p>
                            </div>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-1 text-gray-400 hover:text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">All stock levels are healthy.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">View All Orders &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-l-lg">Order ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider rounded-r-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->user ? $order->user->name : ($order->shipping_address['name'] ?? 'Guest') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status === 'completed') bg-green-100 text-green-800 
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">No recent orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter Logic
            const dateFilterSelect = document.getElementById('date_filter');
            const customDateRange = document.getElementById('custom-date-range');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const filterForm = document.getElementById('date-filter-form');

            if (dateFilterSelect) {
                dateFilterSelect.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customDateRange.style.display = 'flex';
                        startDateInput.required = true;
                        endDateInput.required = true;
                    } else {
                        customDateRange.style.display = 'none';
                        startDateInput.required = false;
                        endDateInput.required = false;
                        filterForm.submit();
                    }
                });
            }

            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Gradient
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)'); // Indigo
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Sales ($)',
                        data: @json($chartValues),
                        borderColor: '#4f46e5', // Indigo 600
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        tension: 0.4, // Smooth curves
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return '$' + value;
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>

@endsection
