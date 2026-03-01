@props(['order' => null])

@php
  $isEdit = $order !== null;
  $statusOptions = [
      'pending' => 'Pending',
      'processing' => 'Processing',
      'completed' => 'Completed',
      'cancelled' => 'Cancelled',
  ];
  $paymentOptions = ['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed'];
  $selectedStatus = $isEdit ? $order->status : 'pending';
  $selectedPayment = $isEdit ? $order->payment_status : 'pending';
  $inputClass =
      'w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:ring focus:ring-primary/20 transition duration-200';
@endphp

<div class="grid min-h-0 flex-grow grid-cols-1 gap-6 lg:grid-cols-12">
  {{-- Left column: Products, Shipping --}}
  <div class="flex flex-col gap-6 overflow-y-auto lg:col-span-7">
    {{-- Products (POS) --}}
    <x-admin.ui.card class="shrink-0">
      <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
              </svg>
            </span>
            Products
          </h2>
          <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
            <div class="flex flex-wrap gap-2">
              <select x-model="posCategoryId" @change="fetchPosProducts(true)"
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:ring-primary sm:w-36">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
              <select x-model="posBrandId" @change="fetchPosProducts(true)"
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:ring-primary sm:w-36">
                <option value="">All brands</option>
                @foreach ($brands as $brand)
                  <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="relative flex-1 sm:w-56">
              <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </span>
              <input type="text" x-model="posSearch" @input.debounce.500ms="fetchPosProducts(true)"
                     placeholder="Search by name or SKU…"
                     class="w-full rounded-lg border border-gray-300 px-3 py-2 pl-9 text-sm focus:border-primary focus:ring-primary">
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50/50 p-4 sm:p-6">
        <div class="grid max-h-[min(70vh,680px)] grid-cols-2 gap-3 overflow-y-auto scroll-smooth py-1 sm:grid-cols-3 sm:gap-4 xl:grid-cols-4"
             @scroll="handlePosScroll($event)">
          <template x-for="product in posProducts" :key="product.id">
            <button type="button" @click="selectProduct(product)"
                    class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white text-left shadow-sm transition-all hover:border-primary hover:shadow-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
              <div class="relative flex aspect-[4/3] items-center justify-center overflow-hidden bg-gray-100">
                <template x-if="product.image">
                  <img :src="'/storage/' + product.image" :alt="product.name"
                       class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                </template>
                <template x-if="!product.image">
                  <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                  </svg>
                </template>
                <span class="absolute right-1.5 top-1.5 rounded-full px-1.5 py-0.5 text-[10px] font-semibold shadow-sm"
                      :class="product.quantity > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                      x-text="product.quantity > 0 ? product.quantity + ' in stock' : 'Out of stock'"></span>
                <span
                      class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                  <span
                        class="scale-75 rounded-full bg-white p-2 text-gray-900 shadow-lg transition-transform duration-200 group-hover:scale-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                  </span>
                </span>
              </div>
              <div class="flex min-w-0 flex-grow flex-col p-3">
                <span class="mb-0.5 truncate font-mono text-[10px] text-gray-400 sm:text-xs"
                      x-text="product.product_code"></span>
                <span class="mb-2 line-clamp-2 text-sm font-semibold leading-snug text-gray-900"
                      x-text="product.name"></span>
                <div class="mt-auto flex items-baseline justify-between gap-1">
                  <span class="text-base font-bold text-primary" x-text="formatMoney(product.price)"></span>
                  <span class="text-[10px] text-gray-500" x-show="product.unit"
                        x-text="'/ ' + (product.unit?.name || '')"></span>
                </div>
              </div>
            </button>
          </template>
          <div x-show="isLoadingPos"
               class="col-span-full flex flex-col items-center justify-center py-12 text-gray-500">
            <svg class="mb-2 h-8 w-8 animate-spin text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
              </circle>
              <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            <span class="text-sm">Loading products…</span>
          </div>
          <div x-show="!isLoadingPos && posProducts.length === 0"
               class="col-span-full flex flex-col items-center justify-center py-12 text-gray-500">
            <div class="mb-3 rounded-full bg-gray-100 p-4">
              <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
            <p class="font-medium text-gray-900">No products found</p>
            <p class="mt-0.5 text-sm">Try different filters or search terms.</p>
          </div>
        </div>
      </div>
    </x-admin.ui.card>

    {{-- Shipping address --}}
    <x-admin.ui.card class="shrink-0">
      <div
           class="flex flex-col gap-3 border-b border-gray-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
          <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </span>
            Shipping address
          </h2>
          <p class="mt-1 text-xs text-gray-500">Select a customer in the panel on the right to load saved addresses.
          </p>
        </div>
        <div x-show="userAddresses.length > 0" class="relative shrink-0">
          <button type="button" @click="addressDropdownOpen = !addressDropdownOpen"
                  class="flex items-center gap-1 text-sm font-medium text-primary hover:underline">
            Use saved address
            <svg class="h-4 w-4 transition-transform" :class="addressDropdownOpen && 'rotate-180'" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div x-show="addressDropdownOpen" x-transition @click.outside="addressDropdownOpen = false"
               class="absolute right-0 z-20 mt-2 max-h-48 w-72 overflow-y-auto rounded-lg border border-gray-200 bg-white py-1 shadow-lg"
               style="display: none;">
            <template x-for="address in userAddresses" :key="address.id">
              <button type="button" @click="fillAddress(address)"
                      class="block w-full border-b border-gray-100 px-4 py-2.5 text-left text-sm transition-colors last:border-0 hover:bg-gray-50">
                <span class="font-medium text-gray-900" x-text="address.type || 'Address'"></span>
                <span class="mt-0.5 block truncate text-xs text-gray-500"
                      x-text="(address.address_line_1 || '') + ', ' + (address.city || '')"></span>
              </button>
            </template>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2 sm:p-6">
        <div class="sm:col-span-2">
          <x-admin.form.label value="Full name" />
          <input type="text" name="shipping_address[name]" x-model="shipping.name" class="{{ $inputClass }}"
                 required>
        </div>
        <div>
          <x-admin.form.label value="Email" />
          <input type="email" name="shipping_address[email]" x-model="shipping.email" class="{{ $inputClass }}"
                 required>
        </div>
        <div>
          <x-admin.form.label value="Phone (optional)" />
          <input type="text" name="shipping_address[phone]" x-model="shipping.phone" class="{{ $inputClass }}">
        </div>
        <div class="sm:col-span-2">
          <x-admin.form.label value="Address" />
          <input type="text" name="shipping_address[address]" x-model="shipping.address"
                 class="{{ $inputClass }}" required>
        </div>
        <div>
          <x-admin.form.label value="City" />
          <input type="text" name="shipping_address[city]" x-model="shipping.city" class="{{ $inputClass }}"
                 required>
        </div>
        <div>
          <x-admin.form.label value="Postal code" />
          <input type="text" name="shipping_address[postal_code]" x-model="shipping.postal_code"
                 class="{{ $inputClass }}" required>
        </div>
        <div class="sm:col-span-2">
          <x-admin.form.label value="Country" />
          <input type="text" name="shipping_address[country]" x-model="shipping.country"
                 class="{{ $inputClass }}" required>
        </div>
      </div>
    </x-admin.ui.card>
  </div>

  {{-- Right column: Customer, Order details, Cart, Summary (sticky sidebar) --}}
  <div class="flex flex-col lg:sticky lg:top-6 lg:col-span-5 lg:max-h-[calc(100vh-8rem)] lg:self-start">
    <div
         class="flex min-h-0 flex-1 flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm lg:flex-initial">
      {{-- Customer --}}
      <div class="shrink-0 border-b border-gray-200 px-4 py-3">
        <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-900">
          <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          Customer
        </h2>
        <div class="relative">
          <input type="text" x-model="customerSearch" @input.debounce.300ms="searchCustomers()"
                 @focus="customerDropdownOpen = true" placeholder="Search by name or email…"
                 class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-8 text-sm focus:border-primary focus:ring-primary">
          <button type="button" x-show="selectedUserId" @click="clearCustomer()"
                  class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md p-1 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                  title="Clear customer">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
          @error('user_id')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
          <div x-show="customerDropdownOpen && (customerResults.length > 0 || customerSearch.length >= 2)" x-transition
               class="absolute z-20 mt-1 max-h-52 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white py-1 shadow-lg"
               style="display: none;">
            <template x-for="user in customerResults" :key="user.id">
              <button type="button" @click="selectCustomer(user)"
                      class="block w-full border-b border-gray-100 px-4 py-2.5 text-left text-sm transition-colors last:border-0 hover:bg-gray-50">
                <span class="font-medium text-gray-900" x-text="user.name"></span>
                <span class="block text-xs text-gray-500" x-text="user.email"></span>
              </button>
            </template>
            <div x-show="customerResults.length === 0 && customerSearch.length >= 2 && !isLoadingCustomers"
                 class="px-4 py-3 text-sm text-gray-500">No customers found.</div>
            <div x-show="isLoadingCustomers" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-500">
              <svg class="h-4 w-4 animate-spin text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                   viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              Searching…
            </div>
          </div>
        </div>
        <p class="mt-1.5 text-xs text-gray-500" x-show="!selectedUserId">Type at least 2 characters to search.</p>
        <div x-show="selectedUserId" class="mt-3 rounded-lg border border-primary/20 bg-primary/5 p-3">
          <div class="text-xs font-medium uppercase tracking-wide text-gray-500">Selected customer</div>
          <div class="mt-0.5 text-sm font-semibold text-gray-900" x-text="customerSearch"></div>
          <div class="mt-0.5 text-xs text-gray-500" x-text="selectedUserEmail"></div>
        </div>
      </div>
      {{-- Cart --}}
      <div class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-gray-50/50 px-4 py-3">
        <h2 class="flex items-center gap-2 text-sm font-semibold text-gray-900">
          <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
          </svg>
          Cart
        </h2>
        <span class="rounded-full bg-gray-200 px-2.5 py-1 text-xs font-medium text-gray-700"
              x-text="items.length + ' item(s)'"></span>
      </div>

      <div class="min-h-[150px] flex-1 space-y-3 overflow-y-auto p-4">
        <template x-for="(item, index) in items" :key="index">
          <div class="relative flex flex-col gap-2 rounded-lg border border-gray-200 bg-gray-50 p-3">
            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
            <div class="flex items-start justify-between pr-8">
              <div class="min-w-0">
                <div class="line-clamp-2 text-sm font-semibold leading-tight text-gray-900" x-text="item.name"></div>
                <div class="mt-0.5 font-mono text-[10px] text-gray-500" x-text="item.code"></div>
              </div>
              <button type="button" @click="items.splice(index, 1)"
                      class="absolute right-2.5 top-2.5 rounded-md p-1 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                      title="Remove">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                  </path>
                </svg>
              </button>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-2">
              <div class="relative w-20">
                <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-xs text-gray-500">$</span>
                <input type="number" :name="'items[' + index + '][unit_price]'" x-model="item.price" min="0"
                       step="0.01"
                       class="no-spinners w-full rounded-lg border border-gray-300 py-1.5 pl-5 pr-1.5 text-right font-mono text-xs focus:border-primary focus:ring-primary">
              </div>
              <span class="text-xs text-gray-400">×</span>
              <div class="flex items-center overflow-hidden rounded-lg border border-gray-300 bg-white">
                <button type="button" @click="if(item.quantity > 1) item.quantity--"
                        class="flex h-8 w-8 items-center justify-center text-gray-500 transition-colors hover:bg-gray-100">
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                  </svg>
                </button>
                <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity"
                       min="1"
                       class="no-spinners h-8 w-11 border-0 bg-transparent text-center text-sm font-semibold focus:ring-0">
                <button type="button" @click="item.quantity++"
                        class="flex h-8 w-8 items-center justify-center text-gray-500 transition-colors hover:bg-gray-100">
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                </button>
              </div>
              <div class="w-16 text-right text-sm font-bold text-gray-900"
                   x-text="formatMoney(item.price * item.quantity)"></div>
            </div>
          </div>
        </template>
        <div x-show="items.length === 0" class="flex flex-col items-center justify-center py-10 text-gray-400">
          <div class="mb-3 rounded-full bg-gray-100 p-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
              </path>
            </svg>
          </div>
          <p class="text-sm font-medium text-gray-500">Cart is empty</p>
          <p class="mt-0.5 text-xs">Click products on the left to add them.</p>
        </div>
      </div>

      @error('items')
        <div class="shrink-0 border-t border-red-100 bg-red-50 px-4 py-2 text-xs text-red-600">
          {{ $message }}
        </div>
      @enderror
      {{-- Order details: Status, payment, notes --}}
      <div class="shrink-0 border-b border-gray-200 px-4 py-3">
        <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-900">
          <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          Order details
        </h2>
        <div class="space-y-3">
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <x-admin.form.label value="Order status" class="mb-0.5 text-xs font-medium text-gray-500" />
              <x-admin.form.select name="status" :options="$statusOptions" :selected="$selectedStatus" :placeholder="null" />
            </div>
            <div>
              <x-admin.form.label value="Payment status" class="mb-0.5 text-xs font-medium text-gray-500" />
              <x-admin.form.select name="payment_status" :options="$paymentOptions" :selected="$selectedPayment" :placeholder="null" />
            </div>
          </div>
          <div>
            <x-admin.form.label value="Notes (optional)" class="mb-0.5 text-xs font-medium text-gray-500" />
            <x-admin.form.textarea name="notes" rows="2"
                                   placeholder="Internal or customer notes…">{{ old('notes', $isEdit ? $order->notes : '') }}</x-admin.form.textarea>
          </div>
        </div>
      </div>

      {{-- Summary & submit --}}
      <div class="shrink-0 space-y-3 border-t border-gray-200 bg-white p-4">
        <div class="flex justify-between text-sm text-gray-600">
          <span>Subtotal</span>
          <span class="font-semibold text-gray-900" x-text="formatMoney(calculateSubtotal())">$0.00</span>
        </div>
        <div class="flex items-center justify-between border-t border-gray-200 pt-3 text-base font-bold">
          <span class="text-gray-900">Total</span>
          <span class="text-primary" x-text="formatMoney(calculateSubtotal())">$0.00</span>
        </div>
        @if ($isEdit)
          <x-admin.actions.button type="submit" class="w-full rounded-xl py-3">
            Update order
          </x-admin.actions.button>
          <a href="{{ route('admin.orders.invoice.create', $order) }}"
             class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50">
            Generate invoice
          </a>
        @else
          <div x-show="!selectedUserId || items.length === 0"
               class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
            <span x-show="!selectedUserId">Select a customer above.</span>
            <span x-show="selectedUserId && items.length === 0">Add at least one product to the cart.</span>
          </div>
          <button type="submit" :disabled="!selectedUserId || items.length === 0"
                  class="w-full rounded-xl bg-primary px-4 py-3 text-sm font-bold text-white transition-all hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-primary">
            Create order
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
