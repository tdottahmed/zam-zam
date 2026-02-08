@extends('layouts.admin')

@section('title', 'Create Invoice - Order #' . $order->id)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Invoice: Order #{{ $order->id }}</h1>
            <x-admin.actions.button href="{{ route('admin.orders.show', $order) }}" variant="secondary">
                Back to Order
            </x-admin.actions.button>
        </div>

        <form action="{{ route('admin.orders.invoice.store', $order) }}" method="POST" x-data="invoiceItems()">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Invoice Details & Items -->
                <div class="lg:col-span-2 space-y-6">
                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Invoice Details</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Number</label>
                                <input type="text" name="invoice_number" value="{{ old('invoice_number', $nextInvoiceNumber) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                @error('invoice_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <div class="py-2 px-3 bg-gray-100 dark:bg-gray-700 rounded-md text-gray-500 dark:text-gray-400 text-sm">Draft (Auto)</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Date</label>
                                <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                @error('invoice_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                                <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                                <textarea name="notes" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </x-admin.ui.card>

                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Items to Invoice</h3>
                            <div class="text-sm font-medium text-primary">
                                Total: <span x-text="formatMoney(calculateGrandTotal())"></span>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 text-center w-10">
                                            <input type="checkbox" @change="toggleAll($event)" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        </th>
                                        <th class="px-4 py-3">Product</th>
                                        <th class="px-4 py-3 text-center">Stock</th>
                                        <th class="px-4 py-3 text-right">Unit Price</th>
                                        <th class="px-4 py-3 text-center w-32">Qty</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($items as $itemData)
                                        @php
                                            $item = $itemData['item'];
                                            $stock = $itemData['stock'];
                                            $status = $itemData['status'];
                                            $itemId = $item->id;
                                        @endphp
                                        <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700" 
                                            :class="{'bg-blue-50 dark:bg-blue-900/10': items['{{ $itemId }}'].selected}">
                                            <td class="px-4 py-4 text-center">
                                                <input type="checkbox" name="items[{{ $itemId }}][selected]" x-model="items['{{ $itemId }}'].selected" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</div>
                                                <div class="text-xs text-gray-500">Ordered: {{ $item->quantity }}</div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="{{ $stock < $item->quantity ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                                    {{ $stock }}
                                                </div>
                                                @if($status === 'insufficient')
                                                    <span class="text-[10px] text-red-500">Low Stock</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                ${{ number_format($item->unit_price, 2) }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <input type="number" 
                                                    name="items[{{ $itemId }}][quantity]" 
                                                    x-model="items['{{ $itemId }}'].quantity"
                                                    min="0.01" 
                                                    step="0.01"
                                                    class="w-full text-center rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-1"
                                                    :disabled="!items['{{ $itemId }}'].selected"
                                                >
                                            </td>
                                            <td class="px-4 py-4 text-right font-medium text-gray-900 dark:text-white">
                                                <span x-text="formatMoney(items['{{ $itemId }}'].quantity * {{ $item->unit_price }})"></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-admin.ui.card>
                    @error('items') <div class="text-red-500 text-sm p-2">{{ $message }}</div> @enderror
                </div>

                <!-- Right Column: Summary & Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Summary</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Items Count</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="getSelectedCount()">0</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="formatMoney(calculateSubtotal())">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Tax</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="formatMoney(calculateTax())">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-base font-bold border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                                <span class="text-gray-900 dark:text-white">Total</span>
                                <span class="text-primary" x-text="formatMoney(calculateGrandTotal())">$0.00</span>
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Generate Invoice
                                </button>
                                <p class="text-xs text-center text-gray-500 mt-2">
                                    This will create an invoice record.
                                </p>
                            </div>
                        </div>
                    </x-admin.ui.card>
                </div>
            </div>
        </form>
    </div>

    <script>
        function invoiceItems() {
            return {
                items: {
                    @foreach($items as $itemData)
                        '{{ $itemData['item']->id }}': {
                            selected: true,
                            quantity: {{ $itemData['item']->quantity }},
                            price: {{ $itemData['item']->unit_price }},
                            tax_rate: {{ $itemData['item']->product && $itemData['item']->product->tax ? $itemData['item']->product->tax->value : 0 }}
                        },
                    @endforeach
                },
                toggleAll(e) {
                    const checked = e.target.checked;
                    Object.keys(this.items).forEach(id => {
                        this.items[id].selected = checked;
                    });
                },
                getSelectedCount() {
                    return Object.values(this.items).filter(i => i.selected).length;
                },
                calculateSubtotal() {
                    return Object.values(this.items)
                        .filter(i => i.selected)
                        .reduce((sum, item) => sum + (item.quantity * item.price), 0);
                },
                calculateTax() {
                    return Object.values(this.items)
                        .filter(i => i.selected)
                        .reduce((sum, item) => sum + (item.quantity * item.price * (item.tax_rate / 100)), 0);
                },
                calculateGrandTotal() {
                    return this.calculateSubtotal() + this.calculateTax();
                },
                formatMoney(amount) {
                    return '$' + Number(amount).toFixed(2);
                }
            }
        }
    </script>
@endsection
