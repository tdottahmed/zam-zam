@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->id }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                        {{ $order->payment_status === 'paid' ? 'border-green-200 bg-green-50 text-green-700' : 'border-gray-200 bg-gray-50 text-gray-700' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                </p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <x-admin.actions.button href="{{ route('admin.orders.index') }}" variant="secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to List
                </x-admin.actions.button>
                <x-admin.actions.button href="{{ route('admin.orders.invoice.create', $order) }}" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Create Invoice
                </x-admin.actions.button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Order Items & Payment -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Items -->
                <x-admin.ui.card>
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Order Items</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Product</th>
                                    <th class="px-6 py-3 text-center">Qty</th>
                                    <th class="px-6 py-3 text-right">Unit Price</th>
                                    <th class="px-6 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($order->items as $item)
                                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</div>
                                            @if($item->product && $item->product->product_code)
                                                <div class="text-xs text-gray-500">SKU: {{ $item->product->product_code }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                            ${{ number_format($item->total_price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right font-medium">Subtotal</td>
                                    <td class="px-6 py-3 text-right font-medium">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right font-medium">Shipping</td>
                                    <td class="px-6 py-3 text-right text-green-600">Free</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-lg text-gray-900 dark:text-white">Total</td>
                                    <td class="px-6 py-4 text-right font-bold text-lg text-primary dark:text-white">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </x-admin.ui.card>
                
                <!-- Payment Info -->
                <x-admin.ui.card>
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                         <h3 class="font-semibold text-gray-900 dark:text-white">Payment Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gray-100 rounded-lg dark:bg-gray-700">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white uppercase">{{ $order->payment_method }}</p>
                            </div>
                            <div class="ml-auto">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium border
                                {{ $order->payment_status === 'paid' ? 'border-green-200 bg-green-50 text-green-700' : 'border-gray-200 bg-gray-50 text-gray-700' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-admin.ui.card>
            </div>

            <!-- Right Column: Customer & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Management -->
                <x-admin.ui.card>
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Order Status</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fulfillment Status</label>
                                    <x-admin.form.select name="status" class="w-full" :selected="$order->status" :options="[
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled'
                                    ]" />
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status</label>
                                    <x-admin.form.select name="payment_status" class="w-full" :selected="$order->payment_status" :options="[
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed'
                                    ]" />
                                </div>

                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Update Order
                                </button>
                            </div>
                        </form>
                    </div>
                </x-admin.ui.card>

                <!-- Customer Details -->
                <x-admin.ui.card>
                     <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Customer</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <span class="inline-block h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden text-gray-400">
                                    <svg class="h-full w-full" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </span>
                            </div>
                             <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $order->shipping_address['name'] ?? 'N/A' }}
                                </p>
                                @if($order->user)
                                    <a href="#" class="text-xs text-primary hover:underline">View Profile</a>
                                @else
                                    <span class="text-xs text-gray-500">Guest Checkout</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-3">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Contact Info</p>
                                <div class="flex items-center gap-2 text-sm text-gray-900 dark:text-white">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $order->user->email ?? ($order->shipping_address['email'] ?? 'N/A') }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-900 dark:text-white mt-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $order->user->phone ?? ($order->shipping_address['phone'] ?? 'N/A') }}
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Shipping Address</p>
                                <p class="text-sm text-gray-900 dark:text-white leading-snug">
                                    {{ $order->shipping_address['address'] ?? '' }}<br>
                                    {{ $order->shipping_address['city'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}<br>
                                    {{ $order->shipping_address['country'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </x-admin.ui.card>
            </div>
        </div>
    </div>
@endsection
