@extends('layouts.admin')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Invoice: {{ $invoice->invoice_number }}</h1>
            <div class="flex gap-3">
                <x-admin.actions.button href="{{ route('admin.orders.show', $invoice->order_id) }}" variant="secondary">
                    Back to Order
                </x-admin.actions.button>
                <x-admin.actions.button href="{{ route('admin.invoices.print', $invoice) }}" variant="primary" target="_blank">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </x-admin.actions.button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <x-admin.ui.card>
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Invoice Items</h3>
                        <span class="text-sm text-gray-500">{{ $invoice->invoice_date->format('F d, Y') }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Product</th>
                                    <th class="px-6 py-3 text-center">Qty</th>
                                    <th class="px-6 py-3 text-right">Unit Price</th>
                                    <th class="px-6 py-3 text-right">Tax</th>
                                    <th class="px-6 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($invoice->items as $item)
                                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $item->product_name }}
                                        </td>
                                        <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="px-6 py-4 text-right">${{ number_format($item->tax_amount, 2) }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                            ${{ number_format($item->total_price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right font-medium">Subtotal</td>
                                    <td class="px-6 py-3 text-right">${{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right font-medium">Tax</td>
                                    <td class="px-6 py-3 text-right">${{ number_format($invoice->tax_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-right font-bold text-lg text-gray-900 dark:text-white">Total</td>
                                    <td class="px-6 py-4 text-right font-bold text-lg text-primary dark:text-white">
                                        ${{ number_format($invoice->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </x-admin.ui.card>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <x-admin.ui.card>
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Details</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 uppercase">
                                {{ $invoice->status }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Due Date</p>
                            <p class="text-gray-900 dark:text-white">{{ $invoice->due_date->format('F d, Y') }}</p>
                        </div>
                        @if($invoice->notes)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Notes</p>
                                <p class="text-gray-900 dark:text-white text-sm">{{ $invoice->notes }}</p>
                            </div>
                        @endif
                    </div>
                </x-admin.ui.card>

                <x-admin.ui.card>
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Customer</h3>
                    </div>
                    <div class="p-6">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $invoice->order->shipping_address['name'] ?? 'Guest' }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $invoice->order->shipping_address['address'] ?? '' }}<br>
                            {{ $invoice->order->shipping_address['city'] ?? '' }} {{ $invoice->order->shipping_address['zip'] ?? '' }}<br>
                            {{ $invoice->order->shipping_address['country'] ?? '' }}
                        </p>
                    </div>
                </x-admin.ui.card>
            </div>
        </div>
    </div>
@endsection
