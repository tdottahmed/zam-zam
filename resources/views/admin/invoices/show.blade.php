@extends('layouts.admin')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Invoice #{{ $invoice->invoice_number }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Order #{{ $invoice->order_id }} • 
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $invoice->order->user->name ?? 'Guest' }}</span>
                </p>
            </div>
            <div class="flex gap-3">
                 <x-admin.actions.button href="{{ route('admin.invoices.index') }}" variant="secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back
                </x-admin.actions.button>
                <x-admin.actions.button href="{{ route('admin.invoices.print', $invoice) }}" target="_blank" variant="secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print PDF
                </x-admin.actions.button>
                <x-admin.actions.button href="{{ route('admin.invoices.edit', $invoice) }}" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Invoice
                </x-admin.actions.button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Invoice Details -->
                <x-admin.ui.card>
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row justify-between mb-8">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wide">Invoice</h1>
                                <div class="mt-2 text-gray-500 dark:text-gray-400">
                                    #{{ $invoice->invoice_number }}
                                </div>
                            </div>
                            <div class="mt-4 sm:mt-0 text-right">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                                @php
                                    $statusClasses = [
                                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'sent' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'cancelled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    ];
                                    $class = $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded-full {{ $class }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Bill To</h3>
                                <div class="text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $invoice->order->user->name ?? 'Guest' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $invoice->order->user->email ?? 'no-email' }}<br>
                                    @if($invoice->order->shipping_address)
                                        {{ $invoice->order->shipping_address['address'] ?? '' }}<br>
                                        {{ $invoice->order->shipping_address['city'] ?? '' }} {{ $invoice->order->shipping_address['postal_code'] ?? '' }}<br>
                                        {{ $invoice->order->shipping_address['country'] ?? '' }}
                                    @endif
                                </div>
                            </div>
                            <div class="sm:text-right">
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Invoice Date:</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_date->format('M d, Y') }}</div>
                                    
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Due Date:</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->due_date->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3 text-center">Qty</th>
                                        <th class="px-4 py-3 text-right">Tax</th>
                                        <th class="px-4 py-3 text-right">Price</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($invoice->items as $item)
                                        <tr class="dark:text-white">
                                            <td class="px-4 py-3">
                                                <div class="font-medium">{{ $item->product_name }}</div>
                                                @if($item->discount_amount > 0)
                                                    <div class="text-xs text-red-500">Discount: -${{ number_format($item->discount_amount, 2) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-400">
                                                @if($item->tax_amount > 0)
                                                    ${{ number_format($item->tax_amount, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-4 py-3 text-right font-medium">${{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">
                            <div class="flex justify-end">
                                <div class="w-full sm:w-1/2 lg:w-1/3">
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Subtotal</span>
                                            <span class="font-medium text-gray-900 dark:text-white">${{ number_format($invoice->subtotal, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                            <span>Tax</span>
                                            <span class="font-medium text-gray-900 dark:text-white">${{ number_format($invoice->tax_total, 2) }}</span>
                                        </div>
                                        @if($invoice->discount_total > 0)
                                            <div class="flex justify-between text-sm text-red-600">
                                                <span>Discount</span>
                                                <span>-${{ number_format($invoice->discount_total, 2) }}</span>
                                            </div>
                                        @endif
                                         @if($invoice->shipping_amount > 0)
                                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                                <span>Shipping</span>
                                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($invoice->shipping_amount, 2) }}</span>
                                            </div>
                                        @endif
                                        <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between items-center">
                                            <span class="text-base font-bold text-gray-900 dark:text-white">Total</span>
                                            <span class="text-xl font-bold text-primary">${{ number_format($invoice->total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         @if($invoice->notes)
                            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Notes</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice->notes }}</p>
                            </div>
                        @endif
                    </div>
                </x-admin.ui.card>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <x-admin.ui.card>
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Related Order</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Order #{{ $invoice->order_id }}</div>
                                <div class="text-xs text-gray-500">{{ $invoice->order->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.orders.show', $invoice->order) }}" class="text-sm text-primary hover:underline">View Order Details &rarr;</a>
                    </div>
                </x-admin.ui.card>

                 <x-admin.ui.card>
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Activity</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <p class="mb-2">Created: {{ $invoice->created_at->format('M d, Y H:i') }}</p>
                            <p>Last Updated: {{ $invoice->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </x-admin.ui.card>
            </div>
        </div>
    </div>
@endsection
