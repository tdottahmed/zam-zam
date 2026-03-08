@extends('layouts.admin')

@section('header')
  Orders
@endsection

@section('title', 'Create New Order')

@section('content')
  <div class="flex min-h-[calc(100vh-100px)] flex-col">
    {{-- Page header --}}
    <div class="mb-6 flex shrink-0 items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create New Order</h1>
        <p class="mt-0.5 text-sm text-gray-500">Select a customer, add products, and enter shipping details.</p>
      </div>
      <x-admin.actions.button href="{{ route('admin.orders.index') }}" variant="secondary">
        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Orders
      </x-admin.actions.button>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST" x-data="orderForm()"
          @click.outside="closeDropdowns()" class="flex min-h-0 flex-grow flex-col">
      @csrf
      <input type="hidden" name="user_id" :value="selectedUserId || ''">

      @include('admin.orders.partials._form', ['order' => null])
    </form>
  </div>

  <script>
    function orderForm() {
      return {
        posSearch: '',
        posCategoryId: '',
        posBrandId: '',
        posProducts: [],
        posPage: 1,
        posHasMore: true,
        isLoadingPos: false,

        selectedUserId: '',
        selectedUserEmail: '',
        customerSearch: '',
        customerDropdownOpen: false,
        customerResults: [],
        isLoadingCustomers: false,

        userAddresses: [],
        addressDropdownOpen: false,

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

        init() {
          this.fetchPosProducts(true);
        },

        async fetchPosProducts(reset = false) {
          if (this.isLoadingPos) return;
          if (reset) {
            this.posPage = 1;
            this.posProducts = [];
            this.posHasMore = true;
          }
          if (!this.posHasMore && !reset) return;
          this.isLoadingPos = true;
          try {
            const url = new URL(`{{ route('admin.api.search.products') }}`);
            url.searchParams.append('page', this.posPage);
            if (this.posSearch) url.searchParams.append('q', this.posSearch);
            if (this.posCategoryId) url.searchParams.append('category_id', this.posCategoryId);
            if (this.posBrandId) url.searchParams.append('brand_id', this.posBrandId);
            const res = await fetch(url);
            const data = await res.json();
            if (reset) this.posProducts = data.data;
            else this.posProducts = [...this.posProducts, ...data.data];
            this.posHasMore = data.current_page < data.last_page;
            if (this.posHasMore) this.posPage++;
          } catch (e) {
            console.error('POS fetch:', e);
          } finally {
            this.isLoadingPos = false;
          }
        },

        handlePosScroll(e) {
          const el = e.target;
          if (el.scrollHeight - el.scrollTop <= el.clientHeight + 80) this.fetchPosProducts();
        },

        selectProduct(product) {
          if (parseFloat(product.quantity) <= 0) {
            window.dispatchEvent(new CustomEvent('notify', {
              detail: {
                message: `"${product.name}" is out of stock.`,
                type: 'error'
              }
            }));
            return;
          }
          if (parseFloat(product.price) <= 0) {
            window.dispatchEvent(new CustomEvent('notify', {
              detail: {
                message: `"${product.name}" has no price set.`,
                type: 'error'
              }
            }));
            return;
          }
          const existing = this.items.find(i => i.product_id === product.id);
          if (existing) {
            existing.quantity++;
            return;
          }
          this.items.push({
            product_id: product.id,
            name: product.name,
            code: product.product_code,
            price: parseFloat(product.price),
            quantity: 1
          });
        },

        async searchCustomers() {
          if (this.customerSearch.length < 2) {
            this.customerResults = [];
            return;
          }
          this.isLoadingCustomers = true;
          try {
            const res = await fetch(
              `{{ route('admin.api.search.users') }}?q=${encodeURIComponent(this.customerSearch)}`);
            this.customerResults = await res.json();
          } catch (e) {
            console.error('Customer search:', e);
          } finally {
            this.isLoadingCustomers = false;
          }
        },

        selectCustomer(user) {
          this.selectedUserId = user.id;
          this.selectedUserEmail = user.email || '';
          this.customerSearch = user.name;
          this.customerDropdownOpen = false;
          this.userAddresses = user.addresses || [];
          this.shipping.name = user.name || '';
          this.shipping.email = user.email || '';
          if (this.userAddresses.length > 0) {
            const defaultAddr = this.userAddresses.find(a => a.is_default) || this.userAddresses[0];
            this.fillAddress(defaultAddr);
          }
        },

        clearCustomer() {
          this.selectedUserId = '';
          this.selectedUserEmail = '';
          this.customerSearch = '';
          this.userAddresses = [];
          this.shipping.name = '';
          this.shipping.email = '';
          this.shipping.phone = '';
          this.shipping.address = '';
          this.shipping.city = '';
          this.shipping.postal_code = '';
          this.shipping.country = '';
        },

        fillAddress(address) {
          this.shipping.name = address.name || this.shipping.name;
          this.shipping.email = address.email || this.shipping.email;
          this.shipping.phone = address.phone || '';
          this.shipping.address = (address.address_line_1 || '') + (address.address_line_2 ? ', ' + address
            .address_line_2 : '');
          this.shipping.city = address.city || '';
          this.shipping.postal_code = address.postal_code || '';
          this.shipping.country = address.country || '';
          this.addressDropdownOpen = false;
        },

        isCurrentAddress(address) {
          const addrLine = (address.address_line_1 || '') + (address.address_line_2 ? ', ' + address.address_line_2 : '');
          return this.shipping.address === addrLine && this.shipping.city === (address.city || '');
        },

        closeDropdowns() {
          this.customerDropdownOpen = false;
          this.addressDropdownOpen = false;
        },

        calculateSubtotal() {
          return this.items.reduce((s, i) => s + (i.price * i.quantity), 0);
        },
        formatMoney(amount) {
          return '$' + Number(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          });
        }
      };
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
