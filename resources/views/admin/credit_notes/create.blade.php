@extends('layouts.admin')

@section('title', 'Create Credit Note')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Credit Note</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Process a return or refund for a customer.</p>
            </div>
            <x-admin.actions.button href="{{ route('admin.credit-notes.index') }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Credit Notes
            </x-admin.actions.button>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm border border-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.credit-notes.store') }}" method="POST" x-data="creditNoteForm()" @click.outside="closeDropdowns()">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left Column (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Items Section -->
                     <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Returned Items</h3>
                            <div class="flex gap-4 relative">
                                <!-- Product Search -->
                                <div class="flex-1 relative">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input 
                                            x-ref="productSearchInput"
                                            type="text" 
                                            x-model="productSearch" 
                                            @input.debounce.300ms="searchProducts()" 
                                            @keydown.enter.prevent="selectBestMatch()"
                                            @focus="productDropdownOpen = true"
                                            placeholder="Search and select multiple products..."
                                            class="w-full pl-10 pr-10 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm disabled:opacity-50"
                                        >
                                        <button type="button" x-show="productSearch.length > 0" @click="clearProductSearch()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Dropdown -->
                                    <div x-show="productDropdownOpen && (productResults.length > 0 || productSearch.length > 0)" 
                                         class="absolute z-20 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg overflow-hidden flex flex-col"
                                         style="display: none; max-height: 24rem;"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95">
                                        
                                        <!-- Loading State -->
                                        <div x-show="isLoadingProducts" class="px-4 py-8 text-sm text-gray-500 flex flex-col items-center justify-center">
                                            <svg class="animate-spin mb-3 h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Searching products...
                                        </div>

                                        <ul x-show="!isLoadingProducts" class="overflow-y-auto flex-1">
                                            <template x-for="product in productResults" :key="product.id">
                                                <li class="px-4 py-3 border-b border-gray-100 dark:border-gray-700/50 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors flex items-center gap-3" 
                                                    :class="{'bg-primary/5 dark:bg-primary/10': isProductAdded(product.id)}"
                                                    @click="toggleProduct(product)">
                                                    
                                                    <!-- Checkbox for visual multi-select feedback -->
                                                    <div class="flex-shrink-0">
                                                        <div class="w-5 h-5 rounded border flex items-center justify-center transition-colors"
                                                             :class="isProductAdded(product.id) ? 'bg-primary border-primary text-white' : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800'">
                                                            <svg x-show="isProductAdded(product.id)" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        </div>
                                                    </div>

                                                    <div class="flex-1 flex justify-between items-start">
                                                        <div>
                                                            <div class="font-semibold text-gray-900 dark:text-gray-100" x-text="product.name"></div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-mono" x-text="product.product_code"></div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="font-bold text-gray-900 dark:text-white" x-text="formatMoney(product.price)"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </template>
                                            <li x-show="productResults.length === 0 && productSearch.length > 0" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                    <p>No products found matching "<span x-text="productSearch" class="font-medium"></span>"</p>
                                                </div>
                                            </li>
                                        </ul>
                                        
                                        <!-- Footer / Done Button -->
                                        <div x-show="!isLoadingProducts && productResults.length > 0" class="p-3 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center shrink-0">
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400"><span x-text="items.filter(i => !i.order_item_id).length"></span> items selected</span>
                                            <button type="button" @click="productDropdownOpen = false" class="px-4 py-1.5 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 text-xs font-semibold rounded shadow hover:bg-gray-800 dark:hover:bg-white transition-colors">
                                                Done
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 min-w-[200px]">Product / Source</th>
                                        <th class="px-4 py-3 text-center w-32">Price</th>
                                        <th class="px-4 py-3 text-center w-32">Credit Qty</th>
                                        <th class="px-4 py-3 w-40">Reason</th>
                                        <th class="px-4 py-3 text-right w-24">Total</th>
                                        <th class="px-4 py-3 text-center w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(item, index) in items" :key="item.order_item_id ? 'oi-'+item.order_item_id : 'p-'+item.product_id+'-'+index">
                                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-3 align-top">
                                                <input type="hidden" :name="'items[' + index + '][order_item_id]'" :value="item.order_item_id || ''">
                                                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                                <input type="hidden" :name="'items[' + index + '][unit_price]'" :value="typeof item.price === 'number' ? item.price : (parseFloat(item.price) || 0)">
                                                <div class="font-bold text-gray-900 dark:text-white" x-text="item.name"></div>
                                                <div x-show="item.order_id" class="text-[11px] font-semibold text-[#C41E3A] mt-1 bg-red-50 dark:bg-red-900/20 inline-block px-1.5 py-0.5 rounded">
                                                    Order #<span x-text="item.order_id"></span> (<span x-text="item.order_date"></span>)
                                                </div>
                                                <div x-show="!item.order_id" class="text-[11px] font-bold text-primary mt-1 bg-primary/10 inline-block px-1.5 py-0.5 rounded">
                                                    Independent Return
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="flex items-center justify-center gap-1">
                                                    <template x-if="!item.price_editing">
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            <span class="font-medium text-gray-900 dark:text-white" x-text="formatMoney(typeof item.price === 'number' ? item.price : (parseFloat(item.price) || 0))"></span>
                                                            <button type="button" @click="item.price_editing = true; $nextTick(() => { const r = $refs['price-input-'+index]; if (r) r.focus(); })" class="p-1 rounded text-gray-400 hover:text-primary hover:bg-primary/10 dark:hover:bg-primary/20" title="Edit price">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <template x-if="!!item.price_editing">
                                                        <div class="flex items-center justify-center gap-1">
                                                            <span class="text-gray-500 dark:text-gray-400 text-xs">$</span>
                                                            <input type="number" step="0.01" min="0"
                                                                :ref="'price-input-'+index"
                                                                x-model="item.price"
                                                                @input="item.price = (parseFloat(item.price) || 0) >= 0 ? (parseFloat(item.price) || 0) : 0"
                                                                @blur="item.price = Math.max(0, parseFloat(item.price) || 0); item.price_editing = false"
                                                                @keydown.enter="$event.target.blur()"
                                                                class="w-20 text-center text-sm font-medium rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-900 py-1 no-spinners">
                                                            <button type="button" @click="item.price = Math.max(0, parseFloat(item.price) || 0); item.price_editing = false" class="p-1 rounded text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20" title="Apply">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="flex items-center justify-center">
                                                    <button type="button" @click="if(item.quantity > 1) item.quantity--" class="w-7 h-7 flex items-center justify-center rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors focus:outline-none z-10">
                                                        -
                                                    </button>
                                                    <input type="number" 
                                                        :name="'items[' + index + '][quantity]'" 
                                                        x-model="item.quantity" 
                                                        @input="if(item.quantity > item.max_quantity) item.quantity = item.max_quantity; if(item.quantity < 1) item.quantity = 1"
                                                        class="w-12 text-center border-gray-300 dark:border-gray-600 dark:bg-gray-900 py-0.5 text-sm z-0 no-spinners font-semibold h-7"
                                                        min="1" :max="item.max_quantity">
                                                    <button type="button" @click="if(item.quantity < item.max_quantity) item.quantity++" class="w-7 h-7 flex items-center justify-center rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors focus:outline-none z-10">
                                                        +
                                                    </button>
                                                </div>
                                                <div class="text-[10px] text-center text-gray-500 mt-1" x-show="item.max_quantity < 9999">Max: <span x-text="item.max_quantity"></span></div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <select :name="'items[' + index + '][reason]'" x-model="item.reason" class="w-full text-xs rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 py-1 pl-2 pr-6">
                                                    <option value="">Same as general...</option>
                                                    <option value="Damaged / Broken">Damaged / Broken</option>
                                                    <option value="Wrong Item Received">Wrong Item Received</option>
                                                    <option value="Defective">Defective</option>
                                                    <option value="Not as Described">Not as Described</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 align-top text-right">
                                                <div class="flex items-center justify-end gap-1">
                                                    <template x-if="!item.total_editing">
                                                        <div class="flex items-center justify-end gap-1.5">
                                                            <span class="font-bold text-gray-900 dark:text-white" x-text="formatMoney(getLineTotal(item))"></span>
                                                            <button type="button" @click="item.total_editing = true; $nextTick(() => { const r = $refs['total-input-'+index]; if (r) r.focus(); })" class="p-1 rounded text-gray-400 hover:text-primary hover:bg-primary/10 dark:hover:bg-primary/20" title="Edit total">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                    <template x-if="!!item.total_editing">
                                                        <div class="flex items-center justify-end gap-1">
                                                            <span class="text-gray-500 dark:text-gray-400 text-xs">$</span>
                                                            <input type="number" step="0.01" min="0"
                                                                :ref="'total-input-'+index"
                                                                :value="getLineTotal(item)"
                                                                @input="applyTotalFromInput(item, $event.target.value)"
                                                                @blur="applyTotalFromInput(item, $event.target.value); item.total_editing = false"
                                                                @keydown.enter="$event.target.blur()"
                                                                class="w-20 text-right text-sm font-bold rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-900 py-1 no-spinners">
                                                            <button type="button" @click="const el = $refs['total-input-'+index]; if (el) { applyTotalFromInput(item, el.value); } item.total_editing = false" class="p-1 rounded text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20" title="Apply">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top text-center">
                                                <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500 p-1.5 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    
                                    <tr x-show="items.length === 0">
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg border-t border-gray-100 dark:border-gray-700">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                </div>
                                                <p class="font-medium text-gray-900 dark:text-white">No items added</p>
                                                <p class="text-sm mt-1">Search and select items to refund.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </x-admin.ui.card>
                </div>

                <!-- Right Column (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Customer Select -->
                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Customer</h3>
                            <div x-show="selectedUserId" class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Selected</div>
                        </div>
                        <div class="p-6">
                            <input type="hidden" name="user_id" x-model="selectedUserId">
                            
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="customerSearch" 
                                    @input.debounce.300ms="searchCustomers()"
                                    @focus="customerDropdownOpen = true"
                                    placeholder="Search Customer by Name/Email..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                >

                                <!-- Customer Dropdown -->
                                <div x-show="customerDropdownOpen && (customerResults.length > 0 || customerSearch.length > 0)" 
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-y-auto"
                                    style="display: none;">
                                    <ul>
                                        <template x-for="user in customerResults" :key="user.id">
                                            <li @click="selectCustomer(user)" class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-sm">
                                                <div class="font-medium text-gray-900 dark:text-white" x-text="user.name"></div>
                                                <div class="text-xs text-gray-500" x-text="user.email"></div>
                                            </li>
                                        </template>
                                        <li x-show="customerResults.length === 0 && customerSearch.length > 0 && !isLoadingCustomers" class="px-4 py-2 text-sm text-gray-500">
                                            No customers found.
                                        </li>
                                        <li x-show="isLoadingCustomers" class="px-4 py-2 text-sm text-gray-500">
                                            Searching...
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </x-admin.ui.card>

                    <!-- Settings -->
                    <x-admin.ui.card>
                         <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Settings</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">General Context</label>
                                <select name="reason" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-[#C41E3A]">
                                    <option value="Damaged / Broken">Damaged / Broken</option>
                                    <option value="Wrong Item Received">Wrong Item Received</option>
                                    <option value="Defective">Defective</option>
                                    <option value="Not as Described">Not as Described</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Action</label>
                                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary">
                                    <option value="draft">Save as Draft / Pending</option>
                                    <option value="approved">Approve Immediately</option>
                                    <option value="refunded">Mark as Refunded</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Status determines the workflow state.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin Internal Notes</label>
                                <textarea name="admin_notes" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary placeholder-gray-400" placeholder="Optional notes..."></textarea>
                            </div>
                        </div>
                    </x-admin.ui.card>

                    <!-- Summary -->
                     <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-800/50">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Summary</h3>
                        </div>
                         <div class="p-6 space-y-3">
                             <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Total Refund Items</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="items.length"></span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-3 mt-2">
                                <span class="text-gray-900 dark:text-white">Expected Refund</span>
                                <span class="text-[#C41E3A]" x-text="formatMoney(calculateSubtotal())">$0.00</span>
                            </div>
                            
                            <button type="submit" :disabled="items.length === 0" class="w-full mt-6 py-2.5 px-4 border border-transparent rounded-lg shadow-sm font-medium text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                Create Credit Note
                            </button>
                        </div>
                    </x-admin.ui.card>

                </div>
            </div>
        </form>
    </div>

    <script>
        function creditNoteForm() {
            return {
                // Product Search
                productSearch: '',
                productDropdownOpen: false,
                productResults: [],
                isLoadingProducts: false,

                // Customer Search
                selectedUserId: '',
                customerSearch: '', 
                customerDropdownOpen: false,
                customerResults: [],
                isLoadingCustomers: false,
                
                items: [],

                // Logic
                async searchProducts() {
                    if (this.productSearch.length < 2) {
                        this.productResults = [];
                        return;
                    }
                    this.isLoadingProducts = true;
                    try {
                        const response = await fetch(`{{ route('admin.api.search.products') }}?q=${this.productSearch}`);
                        const data = await response.json();
                        this.productResults = data.data || [];
                    } catch (e) {
                        console.error('Error searching products:', e);
                    } finally {
                        this.isLoadingProducts = false;
                    }
                },

                clearProductSearch() {
                    this.productSearch = '';
                    this.productResults = [];
                    this.$refs.productSearchInput?.focus();
                },

                toggleProduct(product) {
                    // Check if exists
                    const existingIndex = this.items.findIndex(i => i.product_id === product.id && !i.order_item_id);
                    if (existingIndex >= 0) {
                        this.items.splice(existingIndex, 1);
                    } else {
                        this.items.push({
                            order_item_id: null,
                            order_id: null,
                            order_date: null,
                            product_id: product.id,
                            name: product.name,
                            code: product.product_code,
                            price: parseFloat(product.price),
                            price_editing: false,
                            total_editing: false,
                            quantity: 1,
                            max_quantity: 9999, // Allow any quantity for independent items
                            reason: ''
                        });
                    }
                },
                
                isProductAdded(id) {
                    return this.items.some(i => i.product_id === id && !i.order_item_id);
                },
                
                async selectBestMatch() {
                    if (this.productResults.length > 0) {
                        this.toggleProduct(this.productResults[0]);
                    }
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                async searchCustomers() {
                    if (this.customerSearch.length < 2) {
                        this.customerResults = [];
                        return;
                    }
                    this.isLoadingCustomers = true;
                    try {
                        const response = await fetch(`{{ route('admin.api.search.users') }}?q=${this.customerSearch}`);
                        this.customerResults = await response.json();
                    } catch (e) {
                        console.error('Error searching customers:', e);
                    } finally {
                        this.isLoadingCustomers = false;
                    }
                },

                selectCustomer(user) {
                    this.selectedUserId = user.id;
                    this.customerSearch = user.name;
                    this.customerDropdownOpen = false;
                },

                closeDropdowns() {
                    this.productDropdownOpen = false;
                    this.customerDropdownOpen = false;
                },

                getLineTotal(item) {
                    const p = typeof item.price === 'number' ? item.price : (parseFloat(item.price) || 0);
                    return p * item.quantity;
                },

                applyTotalFromInput(item, value) {
                    const total = Math.max(0, parseFloat(value) || 0);
                    const qty = Math.max(1, item.quantity);
                    item.price = Math.round((total / qty) * 100) / 100;
                },

                calculateSubtotal() {
                    return this.items.reduce((sum, item) => sum + this.getLineTotal(item), 0);
                },
                
                formatMoney(amount) {
                    return '$' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
    <style>
        .no-spinners::-webkit-outer-spin-button,
        .no-spinners::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .no-spinners {
            -moz-appearance: textfield;
        }
    </style>
@endsection
