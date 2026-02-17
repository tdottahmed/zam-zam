@extends('layouts.admin')

@section('title', 'Create Invoice - Order #' . $order->id)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Invoice</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Order #{{ $order->id }} • {{ $order->user->name ?? 'Guest' }}</p>
            </div>
            <x-admin.actions.button href="{{ route('admin.orders.show', $order) }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Order
            </x-admin.actions.button>
        </div>

        <form action="{{ route('admin.orders.invoice.store', $order) }}" method="POST" x-data="invoiceItems()">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Column: Items (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Invoice Items
                            </h3>
                            <div class="text-sm">
                                <span x-text="getSelectedCount()" class="font-bold text-gray-900 dark:text-white">0</span> items selected
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 text-center w-10">
                                            <input type="checkbox" @change="toggleAll($event)" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                        </th>
                                        <th class="px-4 py-3 min-w-[200px]">Product / Details</th>
                                        <th class="px-4 py-3 text-center w-28">Highlight</th>
                                        <th class="px-4 py-3 text-center w-28">Qty</th>
                                        <th class="px-4 py-3 text-right w-36">Price ($)</th>
                                        <th class="px-4 py-3 text-right w-48">Discount</th>
                                        <th class="px-4 py-3 text-right w-32">Total</th>
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
                                        <tr class="transition-colors" 
                                            :class="{'bg-blue-50/50 dark:bg-blue-900/20': items['{{ $itemId }}'].selected && !items['{{ $itemId }}'].is_highlighted}"
                                            :style="items['{{ $itemId }}'].is_highlighted && items['{{ $itemId }}'].selected ? `background-color: ${items['{{ $itemId }}'].highlight_color}40` : ''">
                                            
                                            <!-- Checkbox -->
                                            <td class="px-4 py-4 text-center align-top">
                                                <input type="checkbox" name="items[{{ $itemId }}][selected]" x-model="items['{{ $itemId }}'].selected" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 cursor-pointer mt-1">
                                            </td>
                                            
                                            <!-- Product Info -->
                                            <td class="px-4 py-4 align-top">
                                                <div class="font-medium text-gray-900 dark:text-white text-base">{{ $item->product_name }}</div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                        Stock: {{ $stock }}
                                                    </span>
                                                    @if($status === 'insufficient')
                                                        <span class="text-[10px] text-red-600 bg-red-100 px-2 py-0.5 rounded-full font-bold">Insufficient</span>
                                                    @endif
                                                </div>
                                                <!-- Hidden inputs for form submission -->
                                                <template x-if="items['{{ $itemId }}'].selected">
                                                    <div>
                                                        <input type="hidden" name="items[{{ $itemId }}][quantity]" :value="items['{{ $itemId }}'].quantity">
                                                        <input type="hidden" name="items[{{ $itemId }}][price]" :value="items['{{ $itemId }}'].price">
                                                        <input type="hidden" name="items[{{ $itemId }}][discount]" :value="calculateItemDiscount(items['{{ $itemId }}'])">
                                                    </div>
                                                </template>
                                            </td>
                                            
                                            <!-- Highlight -->
                                            <td class="px-4 py-4 text-center align-top">
                                                <div class="flex flex-col items-center gap-2" x-show="items['{{ $itemId }}'].selected">
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" 
                                                               x-model="items['{{ $itemId }}'].is_highlighted" 
                                                               class="sr-only peer">
                                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                                                    </label>
                                                    <div x-show="items['{{ $itemId }}'].is_highlighted" x-transition>
                                                        <input type="color" 
                                                               x-model="items['{{ $itemId }}'].highlight_color"
                                                               class="h-6 w-8 p-0 border-0 rounded cursor-pointer">
                                                    </div>
                                                    <!-- Hidden input for submission -->
                                                    <input type="hidden" name="items[{{ $itemId }}][highlight_color]" :value="items['{{ $itemId }}'].is_highlighted ? items['{{ $itemId }}'].highlight_color : null">
                                                </div>
                                            </td>

                                            <!-- Quantity -->
                                            <td class="px-4 py-4 align-top text-center" x-data="{ editing: false }">
                                                <div class="relative group" @click.away="editing = false">
                                                    <!-- View Mode -->
                                                    <div x-show="!editing" class="flex items-center justify-center gap-2 cursor-pointer py-1" @click="if(items['{{ $itemId }}'].selected) editing = true">
                                                        <span class="font-medium text-gray-900 dark:text-white" x-text="items['{{ $itemId }}'].quantity"></span>
                                                        <svg class="w-3 h-3 text-gray-400 group-hover:text-primary transition-colors opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </div>
                                                    <!-- Edit Mode -->
                                                    <div x-show="editing" class="flex items-center justify-center">
                                                        <input type="number" 
                                                            x-model.number="items['{{ $itemId }}'].quantity"
                                                            min="0.01" 
                                                            step="0.01"
                                                            class="w-20 text-center rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-1"
                                                            x-effect="if(editing) $el.focus()"
                                                            @keydown.enter="editing = false"
                                                        >
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Price -->
                                            <td class="px-4 py-4 align-top text-right" x-data="{ editing: false }">
                                                <div class="relative group" @click.away="editing = false">
                                                    <!-- View Mode -->
                                                    <div x-show="!editing" class="flex items-center justify-end gap-2 cursor-pointer py-1" @click="if(items['{{ $itemId }}'].selected) editing = true">
                                                        <span class="font-medium text-gray-900 dark:text-white" x-text="formatMoney(items['{{ $itemId }}'].price)"></span>
                                                        <svg class="w-3 h-3 text-gray-400 group-hover:text-primary transition-colors opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </div>
                                                    <!-- Edit Mode -->
                                                    <div x-show="editing" class="flex items-center justify-end">
                                                        <input type="number" 
                                                            x-model.number="items['{{ $itemId }}'].price"
                                                            min="0" 
                                                            step="0.01"
                                                            class="w-24 text-right rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-1"
                                                            x-effect="if(editing) $el.focus()"
                                                            @keydown.enter="editing = false"
                                                        >
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Discount -->
                                            <td class="px-4 py-4 align-top text-right">
                                                <div class="flex items-center gap-1 justify-end">
                                                    <input type="number" 
                                                        x-model.number="items['{{ $itemId }}'].discountValue"
                                                        min="0" 
                                                        step="0.01"
                                                        class="w-20 text-right rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-1 text-red-600"
                                                        placeholder="0"
                                                        :disabled="!items['{{ $itemId }}'].selected"
                                                        :class="{'opacity-50': !items['{{ $itemId }}'].selected}"
                                                    >
                                                    <select 
                                                        x-model="items['{{ $itemId }}'].discountType" 
                                                        class="w-16 rounded-r-md border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm py-1 px-1 focus:ring-primary focus:border-primary"
                                                        :disabled="!items['{{ $itemId }}'].selected"
                                                    >
                                                        <option value="fixed">$</option>
                                                        <option value="percent">%</option>
                                                    </select>
                                                </div>
                                                <div class="text-[10px] text-gray-500 mt-1" x-show="items['{{ $itemId }}'].discountType === 'percent' && items['{{ $itemId }}'].discountValue > 0">
                                                    -<span x-text="formatMoney(calculateItemDiscount(items['{{ $itemId }}']))"></span>
                                                </div>
                                            </td>

                                            <!-- Total -->
                                            <td class="px-4 py-4 align-top text-right font-bold text-gray-900 dark:text-white">
                                                <span x-text="formatMoney(calculateLineTotal(items['{{ $itemId }}']))"></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @error('items') <div class="bg-red-50 text-red-600 p-4 text-sm border-t border-red-100">{{ $message }}</div> @enderror
                    </x-admin.ui.card>

                    <!-- Notes Section -->
                     <x-admin.ui.card>
                        <div class="p-6">
                             <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Internal Notes / Comments</label>
                             <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 placeholder-gray-400" placeholder="Add any visible notes for the invoice here...">{{ old('notes') }}</textarea>
                        </div>
                    </x-admin.ui.card>
                </div>

                <!-- Right Column: Settings & Totals (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Settings Card -->
                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Invoice Settings
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Number</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">#</span>
                                    </div>
                                    <input type="text" name="invoice_number" value="{{ old('invoice_number', $nextInvoiceNumber) }}" class="pl-7 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                </div>
                                @error('invoice_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Date</label>
                                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
                                    @error('invoice_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
                                    @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <div class="w-full py-2 px-3 bg-gray-100 dark:bg-gray-700 rounded-md text-gray-500 dark:text-gray-400 text-sm flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span> Draft
                                </div>
                            </div>
                        </div>
                    </x-admin.ui.card>

                    <!-- Summary Card -->
                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-800/50">
                            <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Payment Summary
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="formatMoney(calculateSubtotal())">$0.00</span>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                                <span>Tax</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="formatMoney(calculateTax())">$0.00</span>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                <div class="w-32">
                                    <input type="number" 
                                           x-model.number="shippingAmount"
                                           name="shipping_amount"
                                           min="0" 
                                           step="0.01"
                                           class="w-full text-right rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-1 px-2"
                                           placeholder="0.00">
                                </div>
                            </div>
                            
                            <div class="border-t border-dashed border-gray-200 dark:border-gray-700 my-2"></div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Total Discount</span>
                                <div class="flex w-32">
                                    <input type="number" 
                                           x-model.number="discountTotalValue"
                                           min="0" 
                                           step="0.01"
                                           class="w-full text-right rounded-l border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-1 px-2 text-red-600"
                                           placeholder="0.00">
                                    <select 
                                        x-model="discountTotalType" 
                                        class="w-14 rounded-r border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm py-1 px-1 focus:ring-primary focus:border-primary"
                                    >
                                        <option value="fixed">$</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                                <!-- Hidden input for actual calculated discount total -->
                                <input type="hidden" name="discount_total" :value="calculateGlobalDiscountAmount()">
                            </div>
                            <div class="text-right text-[10px] text-gray-400" x-show="discountTotalType === 'percent' && discountTotalValue > 0">
                                -<span x-text="formatMoney(calculateGlobalDiscountAmount())"></span>
                            </div>

                            <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-3 mt-2">
                                <span class="text-gray-900 dark:text-white">Grand Total</span>
                                <span class="text-primary" x-text="formatMoney(calculateGrandTotal())">$0.00</span>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all transform hover:scale-[1.02]">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Generate Invoice
                                </button>
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
                shippingAmount: 0,
                discountTotalValue: 0,
                discountTotalType: 'fixed', // 'fixed' or 'percent'
                items: {
                    @foreach($items as $itemData)
                        '{{ $itemData['item']->id }}': {
                            selected: true,
                            quantity: {{ $itemData['item']->quantity }},
                            price: {{ $itemData['item']->unit_price }},
                            discountValue: 0,
                            discountType: 'fixed',
                            tax_rate: {{ $itemData['item']->product && $itemData['item']->product->tax ? $itemData['item']->product->tax->value : 0 }},
                            is_highlighted: false,
                            highlight_color: '#FFFF00'
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
                calculateItemDiscount(item) {
                     if (item.discountType === 'percent') {
                         return (item.quantity * item.price) * (item.discountValue / 100);
                     }
                     return item.discountValue;
                },
                calculateLineTotal(item) {
                     if (!item.selected) return 0;
                     const discount = this.calculateItemDiscount(item);
                     const total = (item.quantity * item.price) - (discount || 0);
                     return Math.max(0, total);
                },
                calculateSubtotal() {
                    return Object.values(this.items)
                        .filter(i => i.selected)
                        .reduce((sum, item) => sum + this.calculateLineTotal(item), 0);
                },
                calculateTax() {
                    return Object.values(this.items)
                        .filter(i => i.selected)
                        .reduce((sum, item) => {
                            const taxableAmount = this.calculateLineTotal(item);
                            return sum + (taxableAmount * (item.tax_rate / 100));
                        }, 0);
                },
                calculateGlobalDiscountAmount() {
                    const subtotal = this.calculateSubtotal();
                    const tax = this.calculateTax();
                    const preDiscountTotal = subtotal + tax;
                    
                    if (this.discountTotalType === 'percent') {
                        return preDiscountTotal * (this.discountTotalValue / 100);
                    }
                    return this.discountTotalValue;
                },
                calculateGrandTotal() {
                    const subtotal = this.calculateSubtotal();
                    const tax = this.calculateTax();
                    const globalDiscount = this.calculateGlobalDiscountAmount();
                    
                    return Math.max(0, subtotal + tax - globalDiscount + this.shippingAmount);
                },
                formatMoney(amount) {
                    return '$' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
    <style>
        /* Hide number input spinners */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
