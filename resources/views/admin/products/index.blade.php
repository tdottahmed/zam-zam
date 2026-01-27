@extends('layouts.admin')

@section('header')
    Products
@endsection

@section('content')
    <x-admin.ui.section-header>
        All Products
        <x-slot:description>
            Manage your product inventory here.
        </x-slot:description>
    </x-admin.ui.section-header>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <div class="w-full space-y-4">
                    <!-- Top Row: Filters & Main Actions -->
                    <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
                        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                            <div class="w-full sm:w-48">
                                <x-admin.form.select name="filter_by" :options="[
                                    'name' => 'Name',
                                    'product_code' => 'Code',
                                    'weight' => 'Weight',
                                    'box_price' => 'Box Price',
                                    'unit_price' => 'Unit Price'
                                ]" placeholder="Filter By" :selected="request('filter_by')" />
                            </div>
                            <div class="w-full sm:w-64 relative">
                                <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Enter value" />
                                <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-500 hover:text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </div>
                        </form>

                        <div class="flex gap-2 w-full lg:w-auto justify-end">
                            <x-admin.actions.button href="{{ route('admin.products.create') }}" variant="secondary">
                                New Entry
                            </x-admin.actions.button>
                            <x-admin.actions.button variant="primary">
                                Export
                            </x-admin.actions.button>
                        </div>
                    </div>

                    <!-- Bottom Row: Invoice Actions -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col xl:flex-row gap-4 justify-between items-start xl:items-center">
                        <!-- Customer Invoice -->
                        <div class="flex flex-col sm:flex-row gap-2 w-full xl:w-auto items-center">
                            <div class="w-full sm:w-64">
                                <x-admin.form.select-search name="customer_id" placeholder="Select Customer" :options="[]" />
                            </div>
                            <x-admin.actions.button variant="primary" size="md">
                                Send To Invoice
                            </x-admin.actions.button>
                        </div>

                         <!-- Add to Invoice -->
                         <div class="flex flex-col sm:flex-row gap-2 w-full xl:w-auto items-center justify-end">
                            <div class="w-full sm:w-64">
                                <x-admin.form.select-search name="invoice_id" placeholder="Select Invoice/Customer" :options="[]" />
                            </div>
                            <x-admin.actions.button variant="secondary" size="md">
                                Add To Invoice
                            </x-admin.actions.button>
                        </div>
                    </div>
                </div>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th>Code</x-admin.ui.th>
                <x-admin.ui.th>Product Name</x-admin.ui.th>
                <x-admin.ui.th>Weight</x-admin.ui.th>
                <x-admin.ui.th>Packing</x-admin.ui.th>
                <x-admin.ui.th>Box Price</x-admin.ui.th>
                <x-admin.ui.th>Unit Price</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td class="font-mono text-xs text-gray-500">{{ $product->product_code }}</x-admin.ui.td>
                        <x-admin.ui.td>
                            <span class="font-medium text-gray-900">{{ $product->name }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>{{ $product->weight }}</x-admin.ui.td>
                        <x-admin.ui.td>{{ $product->pcs_in_ctn }}</x-admin.ui.td>
                        <x-admin.ui.td>${{ number_format($product->box_price, 2) }}</x-admin.ui.td>
                        <x-admin.ui.td>${{ number_format($product->unit_price, 2) }}</x-admin.ui.td>
                        <x-admin.ui.td class="text-right">
                             <div class="flex items-center justify-end gap-2">
                                <x-admin.actions.icon-button href="{{ route('admin.products.edit', $product) }}" variant="secondary" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </x-admin.actions.icon-button>
                                
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-admin.actions.icon-button type="submit" variant="danger" size="sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </x-admin.actions.icon-button>
                                </form>
                            </div>
                        </x-admin.ui.td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </x-admin.ui.card>
@endsection
