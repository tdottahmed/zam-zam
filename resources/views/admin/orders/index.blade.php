@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <!-- Stats Section -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6 xl:grid-cols-3 2xl:gap-7.5 mb-8">
        <!-- Total Orders -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 transition-all hover:shadow-md dark:bg-boxdark dark:ring-white/10">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50/50 transition-all group-hover:bg-blue-50 dark:bg-blue-900/10"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                    <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</h4>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                 <span class="flex items-center gap-1 font-medium">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +{{ $pendingOrders }} New
                </span>
                <span class="ml-2 text-gray-400 font-normal">this week</span>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 transition-all hover:shadow-md dark:bg-boxdark dark:ring-white/10">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-orange-50/50 transition-all group-hover:bg-orange-50 dark:bg-orange-900/10"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                    <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</h4>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-sm text-orange-600">
                 <span class="flex items-center gap-1 font-medium">
                    Needs Attention
                </span>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 transition-all hover:shadow-md dark:bg-boxdark dark:ring-white/10">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-green-50/50 transition-all group-hover:bg-green-50 dark:bg-green-900/10"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalRevenue, 2) }}</h4>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <span class="font-normal">From paid orders</span>
            </div>
        </div>
    </div>


    <x-admin.ui.section-header>
        Order Management
        <x-slot:description>
            Track and manage your customer orders.
        </x-slot:description>
    </x-admin.ui.section-header>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <div class="w-full space-y-4">
                    <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                            <!-- Status Filter -->
                             <div class="w-full sm:w-48">
                                <x-admin.form.select name="status" :options="[
                                    'pending' => 'Pending',
                                    'processing' => 'Processing',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ]" placeholder="Status" :selected="request('status')" />
                            </div>
                            <!-- Search Input -->
                            <div class="w-full sm:w-64 relative">
                                <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search ID or Customer..." />
                                <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-500 hover:text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </div>
                        </form>

                         <div class="flex gap-2 w-full lg:w-auto justify-end">
                            <x-admin.actions.button variant="secondary">
                                Export CSV
                            </x-admin.actions.button>
                        </div>
                    </div>
                </div>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th>Order ID</x-admin.ui.th>
                <x-admin.ui.th>Customer</x-admin.ui.th>
                <x-admin.ui.th>Amount</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th>Payment</x-admin.ui.th>
                <x-admin.ui.th>Date</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td class="font-mono text-xs font-bold text-gray-900">
                            #{{ $order->id }}
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $order->user ? $order->user->name : ($order->shipping_address['name'] ?? 'Guest') }}</span>
                                <span class="text-xs text-gray-500">{{ $order->user ? $order->user->email : ($order->shipping_address['email'] ?? '-') }}</span>
                            </div>
                        </x-admin.ui.td>
                        <x-admin.ui.td class="font-medium">
                            ${{ number_format($order->total_amount, 2) }}
                        </x-admin.ui.td>
                        
                        <x-admin.ui.td>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium 
                                {{ $order->status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                {{ $order->status === 'pending' ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                {{ $order->status === 'processing' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                        </x-admin.ui.td>

                        <x-admin.ui.td>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border
                                {{ $order->payment_status === 'paid' ? 'border-green-100 bg-green-50 text-green-700' : 'border-gray-100 bg-gray-50 text-gray-600' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </x-admin.ui.td>

                         <x-admin.ui.td class="text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </x-admin.ui.td>

                        <x-admin.ui.td class="text-right">
                             <x-admin.actions.icon-button href="{{ route('admin.orders.show', $order) }}" variant="secondary" size="sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </x-admin.actions.icon-button>
                        </x-admin.ui.td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span>No orders found matching your criteria.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>
        
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </x-admin.ui.card>
@endsection
