@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->id }}</h1>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800',
                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800',
                    ];
                    $paymentColors = [
                        'paid' => 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800',
                        'pending' => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700',
                        'failed' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <x-admin.actions.button href="{{ route('admin.orders.index') }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </x-admin.actions.button>
            <x-admin.actions.button href="{{ route('admin.orders.invoice.create', $order) }}" variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Create Invoice
            </x-admin.actions.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Order Items & Summary (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items -->
            <x-admin.ui.card>
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Order Items
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $order->items->count() }} items</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Product</th>
                                <th class="px-6 py-3 text-center">Qty</th>
                                <th class="px-6 py-3 text-right">Unit Price</th>
                                <th class="px-6 py-3 text-right">Discount</th>
                                <th class="px-6 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->items as $item)
                                <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</div>
                                                @if($item->product && $item->product->product_code)
                                                    <div class="text-xs text-gray-500">SKU: {{ $item->product->product_code }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            x{{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right text-red-600">
                                        @if($item->discount_amount > 0)
                                            -${{ number_format($item->discount_amount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                        ${{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Summary -->
                <div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col items-end gap-2">
                         <div class="w-full sm:w-64 space-y-2">
                             @php
                                 $subtotal = $order->items->sum('total_price'); // Assuming total_price is stored post-discount. If pre-discount, adjust logic.
                                 // Usually total_price in DB is final line total.
                                 $tax = $order->tax_amount ?? 0;
                                 $shipping = $order->shipping_amount ?? 0;
                                 // Use order total_amount directly for consistency
                             @endphp
                             
                             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                 <span>Subtotal</span>
                                 <span class="font-medium text-gray-900 dark:text-white">${{ number_format($subtotal, 2) }}</span>
                             </div>
                             
                             @if($tax > 0)
                                 <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                     <span>Tax</span>
                                     <span class="font-medium text-gray-900 dark:text-white">${{ number_format($tax, 2) }}</span>
                                 </div>
                             @endif
                             
                             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                 <span>Shipping</span>
                                 <span class="font-medium text-gray-900 dark:text-white">{{ $shipping > 0 ? '$'.number_format($shipping, 2) : 'Free' }}</span>
                             </div>
                             
                             <div class="border-t border-gray-200 dark:border-gray-700 my-2 pt-2">
                                 <div class="flex justify-between text-lg font-bold">
                                     <span class="text-gray-900 dark:text-white">Total</span>
                                     <span class="text-primary">${{ number_format($order->total_amount, 2) }}</span>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
            </x-admin.ui.card>
        </div>

        <!-- Right Column: Customer & Actions (1 col) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Card -->
            <x-admin.ui.card>
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Status Update
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Status</label>
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

                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            Update Order
                        </button>
                    </form>
                </div>
            </x-admin.ui.card>

            <!-- Customer Card -->
             <x-admin.ui.card>
                 <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Customer
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <span class="inline-block h-12 w-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-lg font-bold">
                                {{ substr($order->shipping_address['name'] ?? 'G', 0, 1) }}
                            </span>
                        </div>
                         <div>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $order->shipping_address['name'] ?? 'Guest User' }}
                            </p>
                            @if($order->user)
                                <a href="#" class="text-xs text-primary hover:underline">View Full Profile</a>
                            @else
                                <span class="text-xs text-gray-500">Guest Checkout</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Contact Info</p>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $order->user->email ?? ($order->shipping_address['email'] ?? 'N/A') }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $order->user->phone ?? ($order->shipping_address['phone'] ?? 'N/A') }}
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Shipping Address</p>
                            <div class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <p class="leading-snug">
                                    {{ $order->shipping_address['address'] ?? '' }}<br>
                                    {{ $order->shipping_address['city'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}<br>
                                    {{ $order->shipping_address['country'] ?? '' }}
                                </p>
                            </div>
                        </div>
                        
                        @if($order->payment_method)
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Payment</p>
                                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <span class="uppercase font-medium">{{ $order->payment_method }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </x-admin.ui.card>
        </div>
    </div>
</div>
@endsection
