@extends('layouts.admin')

@section('title', 'Edit Credit Note')

@section('content')
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Credit Note {{ $creditNote->credit_note_number }}
        </h1>
        <p class="mt-2 border-l-2 border-[#C41E3A] pl-3 text-sm font-medium text-gray-600 dark:text-gray-400">
          Modifying draft requested on {{ $creditNote->created_at->format('M d, Y') }}.
        </p>
      </div>
      <x-admin.actions.button href="{{ route('admin.credit-notes.show', $creditNote) }}" variant="secondary">
        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Cancel Edit
      </x-admin.actions.button>
    </div>

    @if ($errors->any())
      <div class="rounded-lg border border-red-100 bg-red-50 p-4 text-sm text-red-600">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.credit-notes.update', $creditNote) }}" method="POST" x-data="creditNoteEditForm()"
          @click.outside="closeDropdowns()">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Left Column (8 cols) -->
        <div class="space-y-6 lg:col-span-8">

          <!-- Items Section -->
          <x-admin.ui.card>
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
              <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Returned Items</h3>
              <div class="relative flex gap-4">
                <!-- Product Search -->
                <div class="relative flex-1">
                  <input type="text" x-model="productSearch" @input.debounce.300ms="searchProducts()"
                         @keydown.enter.prevent="selectBestMatch()" @focus="productDropdownOpen = true"
                         placeholder="Search for internal products to refund..."
                         class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800">

                  <!-- Dropdown -->
                  <div x-show="productDropdownOpen && (productResults.length > 0 || productSearch.length > 0)"
                       class="absolute z-10 mt-1 max-h-96 w-full overflow-y-auto rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                       style="display: none;" x-transition:enter="transition ease-out duration-100"
                       x-transition:enter-start="transform opacity-0 scale-95"
                       x-transition:enter-end="transform opacity-100 scale-100"
                       x-transition:leave="transition ease-in duration-75"
                       x-transition:leave-start="transform opacity-100 scale-100"
                       x-transition:leave-end="transform opacity-0 scale-95">

                    <!-- Loading State -->
                    <div x-show="isLoadingProducts"
                         class="flex items-center justify-center px-4 py-3 text-sm text-gray-500">
                      <svg class="-ml-1 mr-3 h-5 w-5 animate-spin text-primary" xmlns="http://www.w3.org/2000/svg"
                           fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                      Searching products...
                    </div>

                    <ul x-show="!isLoadingProducts">
                      <template x-for="product in productResults" :key="product.id">
                        <li class="cursor-pointer border-b border-gray-100 px-4 py-3 transition-colors last:border-0 hover:bg-gray-50 dark:border-gray-700/50 dark:hover:bg-gray-700/50"
                            @click="selectProduct(product)">
                          <div class="mb-1 flex items-start justify-between">
                            <div>
                              <div class="font-semibold text-gray-900 dark:text-gray-100" x-text="product.name"></div>
                              <div class="mt-0.5 font-mono text-xs text-gray-500 dark:text-gray-400"
                                   x-text="product.product_code"></div>
                            </div>
                            <div class="font-bold text-gray-900 dark:text-white" x-text="formatMoney(product.price)">
                            </div>
                          </div>
                        </li>
                      </template>
                      <li x-show="productResults.length === 0 && productSearch.length > 0"
                          class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                          <svg class="mb-2 h-10 w-10 text-gray-300" fill="none" stroke="currentColor"
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                          </svg>
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
                    <th class="min-w-[200px] px-4 py-3">Product / Source</th>
                    <th class="w-32 px-4 py-3 text-center">Price</th>
                    <th class="w-32 px-4 py-3 text-center">Credit Qty</th>
                    <th class="w-40 px-4 py-3">Reason</th>
                    <th class="w-24 px-4 py-3 text-right">Total</th>
                    <th class="w-10 px-4 py-3 text-center"></th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                  <template x-for="(item, index) in items" :key="item.order_item_id">
                    <tr class="bg-white transition-colors hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50">
                      <td class="px-4 py-3 align-top">
                        <input type="hidden" :name="'items[' + index + '][order_item_id]'"
                               :value="item.order_item_id || ''">
                        <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                        <input type="hidden" :name="'items[' + index + '][unit_price]'"
                               :value="typeof item.price === 'number' ? item.price : (parseFloat(item.price) || 0)">
                        <div class="font-bold text-gray-900 dark:text-white" x-text="item.name"></div>
                        <div x-show="item.order_id"
                             class="mt-1 inline-block rounded bg-red-50 px-1.5 py-0.5 text-[11px] font-semibold text-[#C41E3A] dark:bg-red-900/20">
                          Order #<span x-text="item.order_id"></span> (<span x-text="item.order_date"></span>)
                        </div>
                        <div x-show="!item.order_id"
                             class="mt-1 inline-block rounded bg-primary/10 px-1.5 py-0.5 text-[11px] font-bold text-primary">
                          Independent Return
                        </div>
                      </td>
                      <td class="px-4 py-3 align-top">
                        <div class="flex items-center justify-center gap-1">
                          <template x-if="!item.price_editing">
                            <div class="flex items-center justify-center gap-1.5">
                              <span class="font-medium text-gray-900 dark:text-white"
                                    x-text="formatMoney(typeof item.price === 'number' ? item.price : (parseFloat(item.price) || 0))"></span>
                              <button type="button"
                                      @click="item.price_editing = true; $nextTick(() => { const r = $refs['price-input-'+index]; if (r) r.focus(); })"
                                      class="rounded p-1 text-gray-400 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20"
                                      title="Edit price">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                  </path>
                                </svg>
                              </button>
                            </div>
                          </template>
                          <template x-if="!!item.price_editing">
                            <div class="flex items-center justify-center gap-1">
                              <span class="text-xs text-gray-500 dark:text-gray-400">$</span>
                              <input type="number" step="0.01" min="0" :ref="'price-input-' + index"
                                     x-model="item.price"
                                     @input="item.price = (parseFloat(item.price) || 0) >= 0 ? (parseFloat(item.price) || 0) : 0"
                                     @blur="item.price = Math.max(0, parseFloat(item.price) || 0); item.price_editing = false"
                                     @keydown.enter="$event.target.blur()"
                                     class="no-spinners w-20 rounded border border-gray-300 py-1 text-center text-sm font-medium dark:border-gray-600 dark:bg-gray-900">
                              <button type="button"
                                      @click="item.price = Math.max(0, parseFloat(item.price) || 0); item.price_editing = false"
                                      class="rounded p-1 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20"
                                      title="Apply">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                              </button>
                            </div>
                          </template>
                        </div>
                      </td>
                      <td class="px-4 py-3 align-top">
                        <div class="flex items-center justify-center">
                          <button type="button" @click="if(item.quantity > 1) item.quantity--"
                                  class="z-10 flex h-7 w-7 items-center justify-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-600 transition-colors hover:bg-gray-100 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            -
                          </button>
                          <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity"
                                 @input="if(item.quantity > item.max_quantity) item.quantity = item.max_quantity; if(item.quantity < 1) item.quantity = 1"
                                 class="no-spinners z-0 h-7 w-12 border-gray-300 py-0.5 text-center text-sm font-semibold dark:border-gray-600 dark:bg-gray-900"
                                 min="1" :max="item.max_quantity">
                          <button type="button" @click="if(item.quantity < item.max_quantity) item.quantity++"
                                  class="z-10 flex h-7 w-7 items-center justify-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-600 transition-colors hover:bg-gray-100 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            +
                          </button>
                        </div>
                        <div class="mt-1 text-center text-[10px] text-gray-500" x-show="item.max_quantity < 9999">Max:
                          <span x-text="item.max_quantity"></span>
                        </div>
                      </td>
                      <td class="px-4 py-3 align-top">
                        <select :name="'items[' + index + '][reason]'" x-model="item.reason"
                                class="w-full rounded border-gray-300 py-1 pl-2 pr-6 text-xs dark:border-gray-700 dark:bg-gray-900">
                          <option value="">Same as general...</option>
                          <option value="Damaged / Broken">Damaged / Broken</option>
                          <option value="Item Expired">Item Expired</option>
                          <option value="Wrong Item Received">Wrong Item Received</option>
                          <option value="Defective">Defective</option>
                          <option value="Not as Described">Not as Described</option>
                          <option value="Other">Other</option>
                        </select>
                      </td>
                      <td class="px-4 py-3 text-right align-top">
                        <div class="flex items-center justify-end gap-1">
                          <template x-if="!item.total_editing">
                            <div class="flex items-center justify-end gap-1.5">
                              <span class="font-bold text-gray-900 dark:text-white"
                                    x-text="formatMoney(getLineTotal(item))"></span>
                              <button type="button"
                                      @click="item.total_editing = true; $nextTick(() => { const r = $refs['total-input-'+index]; if (r) r.focus(); })"
                                      class="rounded p-1 text-gray-400 hover:bg-primary/10 hover:text-primary dark:hover:bg-primary/20"
                                      title="Edit total">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                  </path>
                                </svg>
                              </button>
                            </div>
                          </template>
                          <template x-if="!!item.total_editing">
                            <div class="flex items-center justify-end gap-1">
                              <span class="text-xs text-gray-500 dark:text-gray-400">$</span>
                              <input type="number" step="0.01" min="0" :ref="'total-input-' + index"
                                     :value="getLineTotal(item)" @input="applyTotalFromInput(item, $event.target.value)"
                                     @blur="applyTotalFromInput(item, $event.target.value); item.total_editing = false"
                                     @keydown.enter="$event.target.blur()"
                                     class="no-spinners w-20 rounded border border-gray-300 py-1 text-right text-sm font-bold dark:border-gray-600 dark:bg-gray-900">
                              <button type="button"
                                      @click="const el = $refs['total-input-'+index]; if (el) { applyTotalFromInput(item, el.value); } item.total_editing = false"
                                      class="rounded p-1 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20"
                                      title="Apply">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                              </button>
                            </div>
                          </template>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-center align-top">
                        <button type="button" @click="removeItem(index)"
                                class="rounded-full p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20">
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </template>

                  <tr x-show="items.length === 0">
                    <td colspan="6"
                        class="rounded-b-lg border-t border-gray-100 bg-gray-50 px-6 py-12 text-center text-gray-500 dark:border-gray-700 dark:bg-gray-800/50 dark:text-gray-400">
                      <div class="flex flex-col items-center justify-center">
                        <div class="mb-3 rounded-full bg-gray-100 p-3 dark:bg-gray-700">
                          <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                          </svg>
                        </div>
                        <p class="font-medium text-gray-900 dark:text-white">No items added</p>
                        <p class="mt-1 text-sm">Search and select items to refund.</p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </x-admin.ui.card>
        </div>

        <!-- Right Column (4 cols) -->
        <div class="space-y-6 lg:col-span-4">

          <!-- Customer Context -->
          <x-admin.ui.card>
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
              <h3 class="font-semibold text-gray-900 dark:text-white">Customer</h3>
              <div class="rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-500 dark:bg-gray-800">Locked
              </div>
            </div>
            <div class="p-6">
              <input type="hidden" name="user_id" x-model="selectedUserId">

              <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                  <span
                        class="inline-block flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-lg font-bold text-primary">
                    {{ substr($creditNote->user->name, 0, 1) }}
                  </span>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="truncate text-base font-semibold text-gray-900 dark:text-white">
                    {{ $creditNote->user->name }}
                  </p>
                  <p class="truncate text-sm text-gray-500">
                    {{ $creditNote->user->email }}
                  </p>
                </div>
              </div>
              <div
                   class="mt-4 rounded border border-gray-100 bg-gray-50 p-2 text-xs text-gray-500 dark:border-gray-700 dark:bg-gray-800/50">
                Customer cannot be changed during an edit. Create a new request instead.
              </div>
            </div>
          </x-admin.ui.card>

          <!-- Settings -->
          <x-admin.ui.card>
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
              <h3 class="font-semibold text-gray-900 dark:text-white">Settings</h3>
            </div>
            <div class="space-y-4 p-6">
              <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">General Context</label>
                <select name="reason"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] dark:border-gray-700 dark:bg-gray-800">
                  <option value="Damaged / Broken" {{ $creditNote->reason === 'Damaged / Broken' ? 'selected' : '' }}>
                    Damaged / Broken</option>
                  <option value="Wrong Item Received"
                          {{ $creditNote->reason === 'Wrong Item Received' ? 'selected' : '' }}>Wrong Item Received
                  </option>
                  <option value="Item Expired" {{ $creditNote->reason === 'Item Expired' ? 'selected' : '' }}>Item
                    Expired</option>
                  <option value="Defective" {{ $creditNote->reason === 'Defective' ? 'selected' : '' }}>Defective
                  </option>
                  <option value="Not as Described" {{ $creditNote->reason === 'Not as Described' ? 'selected' : '' }}>
                    Not as Described</option>
                  <option value="Other" {{ $creditNote->reason === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>
              <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status Action</label>
                <select name="status"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary dark:border-gray-700 dark:bg-gray-800">
                  <option value="draft" {{ $creditNote->status === 'draft' ? 'selected' : '' }}>Save as Draft</option>
                  <option value="pending" {{ $creditNote->status === 'pending' ? 'selected' : '' }}>Save as Pending
                  </option>
                  <option value="approved" {{ $creditNote->status === 'approved' ? 'selected' : '' }}>Approve Request
                  </option>
                  <option value="rejected" {{ $creditNote->status === 'rejected' ? 'selected' : '' }}>Reject Request
                  </option>
                  <option value="refunded" {{ $creditNote->status === 'refunded' ? 'selected' : '' }}>Mark as Refunded
                  </option>
                </select>
              </div>
              <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Internal
                  Notes</label>
                <textarea name="admin_notes" rows="3"
                          class="w-full rounded-md border-gray-300 placeholder-gray-400 shadow-sm focus:border-primary dark:border-gray-700 dark:bg-gray-800"
                          placeholder="Optional notes...">{{ $creditNote->admin_notes }}</textarea>
              </div>
            </div>
          </x-admin.ui.card>

          <!-- Summary -->
          <x-admin.ui.card>
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/50">
              <h3 class="font-semibold text-gray-900 dark:text-white">Summary</h3>
            </div>
            <div class="space-y-3 p-6">
              <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                <span>Total Refund Items</span>
                <span class="font-medium text-gray-900 dark:text-white" x-text="items.length"></span>
              </div>
              <div
                   class="mt-2 flex items-center justify-between border-t border-gray-200 pt-3 text-lg font-bold dark:border-gray-700">
                <span class="text-gray-900 dark:text-white">Expected Refund</span>
                <span class="text-[#C41E3A]" x-text="formatMoney(calculateSubtotal())">$0.00</span>
              </div>

              <button type="submit" :disabled="items.length === 0"
                      class="mt-6 w-full rounded-lg border border-transparent bg-[#C41E3A] px-4 py-2.5 font-medium text-white shadow-sm transition-all hover:bg-[#a01830] focus:outline-none disabled:cursor-not-allowed disabled:opacity-50">
                Update Credit Note
              </button>
            </div>
          </x-admin.ui.card>

        </div>
      </div>
    </form>
  </div>

  @php
    // Prepare initial items for Alpine
    $initialItems = $creditNote->items->map(function ($cnItem) {
        $orderDate =
            $cnItem->orderItem && $cnItem->orderItem->order
                ? $cnItem->orderItem->order->created_at->format('M d, Y')
                : 'Unknown';
        $orderId = $cnItem->orderItem ? $cnItem->orderItem->order_id : null;
        $maxQty = $cnItem->orderItem ? $cnItem->orderItem->quantity : 9999;

        return [
            'order_item_id' => $cnItem->order_item_id,
            'order_id' => $orderId,
            'order_date' => $orderDate,
            'product_id' => $cnItem->product_id,
            'name' => $cnItem->product ? $cnItem->product->name : 'Unknown Product',
            'code' => $cnItem->product ? $cnItem->product->product_code : '',
            'price' => (float) $cnItem->unit_price,
            'price_editing' => false,
            'total_editing' => false,
            'quantity' => (int) $cnItem->credit_quantity,
            'max_quantity' => (int) $maxQty,
            'reason' => $cnItem->reason ?? '',
        ];
    });
  @endphp

  <script>
    function creditNoteEditForm() {
      return {
        // Product Search
        productSearch: '',
        productDropdownOpen: false,
        productResults: [],
        isLoadingProducts: false,

        // User Context
        selectedUserId: '{{ $creditNote->user_id }}',

        // Prefilled Items
        items: @json($initialItems),

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

        selectProduct(product) {
          const existingIndex = this.items.findIndex(i => i.product_id === product.id && !i.order_item_id);
          if (existingIndex >= 0) {
            this.items[existingIndex].quantity++;
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
              max_quantity: 9999,
              reason: ''
            });
          }

          this.productSearch = '';
          this.productResults = [];
          this.productDropdownOpen = false;
        },

        async selectBestMatch() {
          if (this.productResults.length > 0) {
            this.selectProduct(this.productResults[0]);
          }
        },

        removeItem(index) {
          this.items.splice(index, 1);
        },

        closeDropdowns() {
          this.productDropdownOpen = false;
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
          return '$' + Number(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          });
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
