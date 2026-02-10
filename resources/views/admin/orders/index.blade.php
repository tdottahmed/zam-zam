@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <!-- Stats Section -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6 xl:grid-cols-3 2xl:gap-7.5 mb-8">
        <!-- Total Orders -->
        <x-admin.ui.card class="group relative overflow-hidden">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50/50 transition-all group-hover:bg-blue-50 dark:bg-blue-900/10"></div>
            <div class="relative flex items-center justify-between p-6">
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
            <div class="px-6 pb-6 mt-[-1rem] flex items-center text-sm text-green-600">
                 <span class="flex items-center gap-1 font-medium">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +{{ $pendingOrders }} New
                </span>
                <span class="ml-2 text-gray-400 font-normal">this week</span>
            </div>
        </x-admin.ui.card>

        <!-- Pending Orders -->
        <x-admin.ui.card class="group relative overflow-hidden">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-orange-50/50 transition-all group-hover:bg-orange-50 dark:bg-orange-900/10"></div>
            <div class="relative flex items-center justify-between p-6">
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
             <div class="px-6 pb-6 mt-[-1rem] flex items-center text-sm text-orange-600">
                 <span class="flex items-center gap-1 font-medium">
                    Needs Attention
                </span>
            </div>
        </x-admin.ui.card>

        <!-- Total Revenue -->
        <x-admin.ui.card class="group relative overflow-hidden">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-green-50/50 transition-all group-hover:bg-green-50 dark:bg-green-900/10"></div>
            <div class="relative flex items-center justify-between p-6">
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
             <div class="px-6 pb-6 mt-[-1rem] flex items-center text-sm text-gray-500 dark:text-gray-400">
                <span class="font-normal">From paid orders</span>
            </div>
        </x-admin.ui.card>
    </div>

    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track and manage your customer orders.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
             <x-admin.actions.button href="{{ route('admin.orders.create') }}" variant="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Order
            </x-admin.actions.button>
        </div>
    </div>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <div class="w-full space-y-4">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 w-full">
                         <!-- Search Input -->
                        <div class="w-full lg:w-96 relative">
                            <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search ID, Customer, Email..." />
                            <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-500 hover:text-primary">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>

                         <!-- Status Filter -->
                         <div class="w-full sm:w-48">
                            <x-admin.form.select name="status" :options="[
                                '' => 'All Statuses',
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ]" :selected="request('status')" />
                        </div>

                        <div class="flex items-center gap-2">
                             <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md transition-colors">
                                Filter
                            </button>
                             @if(request()->anyFilled(['search', 'status']))
                                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
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
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <x-admin.ui.td class="font-mono text-xs font-bold text-gray-900 border-l-4 border-transparent hover:border-primary transition-all">
                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">#{{ $order->id }}</a>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $order->user ? $order->user->name : ($order->shipping_address['name'] ?? 'Guest') }}</span>
                                <span class="text-xs text-gray-500">{{ $order->user ? $order->user->email : ($order->shipping_address['email'] ?? '-') }}</span>
                            </div>
                        </x-admin.ui.td>
                        <x-admin.ui.td class="font-medium text-gray-900 dark:text-white">
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
                                {{ $order->payment_status === 'paid' ? 'border-green-100 bg-green-50 text-green-700 dark:border-green-900/30 dark:bg-green-900/20 dark:text-green-400' : 'border-gray-100 bg-gray-50 text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </x-admin.ui.td>

                         <x-admin.ui.td class="text-gray-500 whitespace-nowrap">
                            {{ $order->created_at->format('M d, Y') }}
                            <div class="text-[10px]">{{ $order->created_at->format('h:i A') }}</div>
                        </x-admin.ui.td>

                        <x-admin.ui.td class="text-right whitespace-nowrap">
                             <div class="flex justify-end items-center gap-2">
                                <!-- View -->
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <!-- Edit -->
                                <a href="{{ route('admin.orders.edit', $order) }}" class="text-gray-400 hover:text-primary transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <!-- Invoice -->
                                @if($order->invoice)
                                     <a href="{{ route('admin.invoices.show', $order->invoice) }}" class="text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="View Invoice">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                @else
                                    <a href="{{ route('admin.orders.invoice.create', $order) }}" class="text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Generate Invoice">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                @endif
                                <!-- Delete -->
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
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
        
        @if($orders->hasPages())
           <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        @endif
    </x-admin.ui.card>
@endsection
