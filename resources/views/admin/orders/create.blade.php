@extends('layouts.admin')

@section('title', 'Create New Order')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Order</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Add a new order manually.</p>
            </div>
            <x-admin.actions.button href="{{ route('admin.orders.index') }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Orders
            </x-admin.actions.button>
        </div>

        <form action="{{ route('admin.orders.store') }}" method="POST" x-data="orderForm()" @click.outside="closeDropdowns()">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Left Column (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Products Section -->
                     <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Order Items</h3>
                            <div class="flex gap-4 relative">
                                <!-- Product Search -->
                                <div class="flex-1 relative">
                                    <input 
                                        type="text" 
                                        x-model="productSearch" 
                                        @input.debounce.300ms="searchProducts()" 
                                        @keydown.enter.prevent="selectBestMatch()"
                                        @focus="productDropdownOpen = true"
                                        placeholder="Scan SKU or Search by Name..."
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm"
                                    >
                                    
                                    <!-- Dropdown -->
                                    <div x-show="productDropdownOpen && (productResults.length > 0 || productSearch.length > 0)" 
                                         class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg max-h-96 overflow-y-auto"
                                         style="display: none;"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95">
                                        
                                        <!-- Loading State -->
                                        <div x-show="isLoadingProducts" class="px-4 py-3 text-sm text-gray-500 flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Searching products...
                                        </div>

                                        <ul x-show="!isLoadingProducts">
                                            <template x-for="product in productResults" :key="product.id">
                                                <li @click="selectProduct(product)" class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer border-b border-gray-100 dark:border-gray-700/50 last:border-0 transition-colors">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <div class="font-semibold text-gray-900 dark:text-gray-100" x-text="product.name"></div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-2">
                                                                <span class="bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded text-gray-600 dark:text-gray-300 font-mono" x-text="product.product_code"></span>
                                                                <span x-show="product.category" class="text-gray-400 dark:text-gray-500">•</span>
                                                                <span x-show="product.category" x-text="product.category?.name"></span>
                                                                <span x-show="product.brand" class="text-gray-400 dark:text-gray-500">•</span>
                                                                <span x-show="product.brand" x-text="product.brand?.name"></span>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="font-bold text-primary" x-text="formatMoney(product.price)"></div>
                                                            <div class="text-xs text-gray-400 mt-0.5" x-show="product.unit">
                                                                Per <span x-text="product.unit?.name"></span>
                                                            </div>
                                                            <div class="text-xs text-green-600 mt-0.5" x-show="product.quantity > 0">
                                                                In Stock: <span x-text="product.quantity"></span>
                                                            </div>
                                                            <div class="text-xs text-red-500 mt-0.5" x-show="product.quantity <= 0">
                                                                Out of Stock
                                                            </div>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-4 py-3 min-w-[200px]">Product</th>
                                        <th class="px-4 py-3 text-center w-32">Price</th>
                                        <th class="px-4 py-3 text-center w-40">Quantity</th>
                                        <th class="px-4 py-3 text-right w-32">Total</th>
                                        <th class="px-4 py-3 text-center w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-3 align-top">
                                                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                                <div class="font-bold text-gray-900 dark:text-white" x-text="item.name"></div>
                                                <div class="text-xs text-gray-500 font-mono mt-0.5" x-text="item.code"></div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="relative rounded-md shadow-sm">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <span class="text-gray-500 sm:text-sm">$</span>
                                                    </div>
                                                    <input type="number" 
                                                        :name="'items[' + index + '][unit_price]'" 
                                                        x-model="item.price" 
                                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 pl-7 pr-3 focus:border-primary focus:ring-primary sm:text-sm text-right font-mono no-spinners"
                                                        min="0" step="0.01">
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="flex items-center justify-center">
                                                    <button type="button" @click="if(item.quantity > 1) item.quantity--" class="w-8 h-8 flex items-center justify-center rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors focus:outline-none focus:ring-1 focus:ring-primary focus:z-10">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                    </button>
                                                    <input type="number" 
                                                        :name="'items[' + index + '][quantity]'" 
                                                        x-model="item.quantity" 
                                                        class="w-16 text-center border-gray-300 dark:border-gray-600 dark:bg-gray-900 py-1 text-sm focus:ring-primary focus:border-primary z-0 no-spinners font-semibold"
                                                        min="1">
                                                    <button type="button" @click="item.quantity++" class="w-8 h-8 flex items-center justify-center rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors focus:outline-none focus:ring-1 focus:ring-primary focus:z-10">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-middle text-right font-bold text-gray-900 dark:text-white text-base">
                                                <span x-text="formatMoney(item.price * item.quantity)"></span>
                                            </td>
                                            <td class="px-4 py-3 align-middle text-center">
                                                <button type="button" @click="items.splice(index, 1)" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    
                                    <tr x-show="items.length === 0">
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-b-lg border-t border-gray-100 dark:border-gray-700">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                                </div>
                                                <p class="font-medium text-gray-900 dark:text-white">Your cart is empty</p>
                                                <p class="text-sm mt-1">Search and select products above to create an order.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                         @error('items') <div class="bg-red-50 text-red-600 p-4 text-sm border-t border-red-100">{{ $message }}</div> @enderror
                    </x-admin.ui.card>

                    <!-- Addresses -->
                     <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Shipping Address</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                                <input type="text" name="shipping_address[name]" x-model="shipping.name" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="shipping_address[email]" x-model="shipping.email" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone (Optional)</label>
                                <input type="text" name="shipping_address[phone]" x-model="shipping.phone" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                                <input type="text" name="shipping_address[address]" x-model="shipping.address" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">City</label>
                                <input type="text" name="shipping_address[city]" x-model="shipping.city" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Postal Code</label>
                                <input type="text" name="shipping_address[postal_code]" x-model="shipping.postal_code" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country</label>
                                <input type="text" name="shipping_address[country]" x-model="shipping.country" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                        </div>
                    </x-admin.ui.card>
                    
                    <!-- Notes -->
                     <x-admin.ui.card>
                         <div class="p-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order Notes</label>
                             <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"></textarea>
                         </div>
                    </x-admin.ui.card>

                </div>

                <!-- Right Column (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Customer Select -->
                    <x-admin.ui.card>
                        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Customer</h3>
                        </div>
                        <div class="p-6">
                            <input type="hidden" name="user_id" x-model="selectedUserId">
                            
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="customerSearch" 
                                    @input.debounce.300ms="searchCustomers()"
                                    @focus="customerDropdownOpen = true"
                                    placeholder="Search Customer..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                >
                                @error('user_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

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
                            
                            <!-- Selected Customer Info -->
                            <div x-show="selectedUserId" class="mt-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-md border border-gray-100 dark:border-gray-700">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Selected:</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400" x-text="customerSearch"></div>
                            </div>

                        </div>
                    </x-admin.ui.card>

                    <!-- Order Settings -->
                    <x-admin.ui.card>
                         <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Settings</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status</label>
                                <select name="payment_status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="failed">Failed</option>
                                </select>
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
                                <span>Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white" x-text="formatMoney(calculateSubtotal())">$0.00</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-3 mt-2">
                                <span class="text-gray-900 dark:text-white">Total</span>
                                <span class="text-primary" x-text="formatMoney(calculateSubtotal())">$0.00</span>
                            </div>
                            
                            <button type="submit" class="w-full mt-6 py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                                Create Order
                            </button>
                        </div>
                    </x-admin.ui.card>

                </div>
            </div>
        </form>
    </div>

    <script>
        function orderForm() {
            return {
                // Product Search
                productSearch: '',
                productDropdownOpen: false,
                productResults: [],
                isLoadingProducts: false,

                // Customer Search
                selectedUserId: '',
                customerSearch: '', // Displays name in input
                customerDropdownOpen: false,
                customerResults: [],
                isLoadingCustomers: false,
                
                items: [],
                shipping: {
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    city: '',
                    postal_code: '',
                    country: ''
                },

                // Logic
                async searchProducts() {
                    if (this.productSearch.length < 2) {
                        this.productResults = [];
                        return;
                    }
                    this.isLoadingProducts = true;
                    try {
                        const response = await fetch(`{{ route('admin.api.search.products') }}?q=${this.productSearch}`);
                        this.productResults = await response.json();
                    } catch (e) {
                        console.error('Error searching products:', e);
                    } finally {
                        this.isLoadingProducts = false;
                    }
                },

                selectProduct(product) {
                    this.items.push({
                        product_id: product.id,
                        name: product.name,
                        code: product.product_code,
                        price: parseFloat(product.price), // Correctly parsing price
                        quantity: 1
                    });
                    
                    // Reset
                    this.productSearch = '';
                    this.productResults = [];
                    this.productDropdownOpen = false;
                },
                
                async selectBestMatch() {
                    // If no results yet, wait for search
                    if (this.productResults.length === 0 && this.productSearch.length > 0) {
                         await this.searchProducts();
                    }

                    if (this.productResults.length === 1) {
                         this.selectProduct(this.productResults[0]);
                    } else if (this.productResults.length > 1) {
                        // Find exact SKU match
                        const exactMatch = this.productResults.find(p => p.product_code === this.productSearch);
                        if (exactMatch) {
                            this.selectProduct(exactMatch);
                        } else {
                             // Optional: Select first if no exact match? 
                             // For safety, let's select the first one if it's a very strong match or just leave it open
                             this.selectProduct(this.productResults[0]);
                        }
                    }
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
                    this.customerSearch = user.name; // Display name
                    this.customerDropdownOpen = false;

                    // Auto-fill shipping details
                    this.shipping.name = user.name;
                    this.shipping.email = user.email;
                },

                closeDropdowns() {
                    this.productDropdownOpen = false;
                    this.customerDropdownOpen = false;
                },

                calculateSubtotal() {
                    return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                formatMoney(amount) {
                    return '$' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
    <style>
        /* Chrome, Safari, Edge, Opera */
        .no-spinners::-webkit-outer-spin-button,
        .no-spinners::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .no-spinners {
            -moz-appearance: textfield;
        }
    </style>
@endsection
