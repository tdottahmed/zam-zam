@extends('layouts.admin')

@section('header')
    Products
@endsection

@section('content')
    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if(session('import_failures') && count(session('import_failures')) > 0)
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3" role="alert">
            <p class="font-medium text-amber-800">Import issues (row → errors):</p>
            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-amber-700">
                @foreach(session('import_failures') as $failure)
                    <li><strong>Row {{ $failure->row() }}</strong>: {{ implode(' ', $failure->errors()) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-admin.ui.section-header>
        Product Catalogue
        <x-slot:description>
            Browse, search, and manage all products in your inventory.
        </x-slot:description>
    </x-admin.ui.section-header>

    <div x-data="{
        selectedIds: [],
        allIds: {{ $products->pluck('id')->toJson() }},
        init() {
            @if(session('success') && \Illuminate\Support\Str::contains(session('success'), 'deleted successfully'))
                sessionStorage.removeItem('jamjam_admin_products_selected');
            @endif
            this.selectedIds = JSON.parse(sessionStorage.getItem('jamjam_admin_products_selected')) || [];
            this.$watch('selectedIds', value => sessionStorage.setItem('jamjam_admin_products_selected', JSON.stringify(value)));
        }
    }">
        <x-admin.ui.card>
            <x-admin.ui.table>
            <x-slot:search>
                <div class="w-full space-y-3">
                    <!-- Toolbar: Search + Actions -->
                    <div class="flex flex-col lg:flex-row gap-3 justify-between items-start lg:items-center" x-show="selectedIds.length === 0">

                        {{-- Search & Filter --}}
                        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                            <div class="w-full sm:w-44">
                                <x-admin.form.select name="filter_by" :options="[
                                    'name'         => 'Product Name',
                                    'product_code' => 'SKU / Code',
                                    'unit_value'   => 'Weight',
                                    'box_price'    => 'Box Price',
                                    'unit_price'   => 'Unit Price',
                                ]" placeholder="Search by…" :selected="request('filter_by')" />
                            </div>
                            <div class="w-full sm:w-72 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search products…" class="pl-9 pr-16" />
                                <div class="absolute inset-y-0 right-0 flex items-center gap-1 pr-2">
                                    @if(request('search'))
                                        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-600 p-1" title="Clear search">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </a>
                                    @endif
                                    <button type="submit" class="text-gray-400 hover:text-[#C41E3A] p-1 transition-colors" title="Search">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap gap-2 w-full lg:w-auto justify-end">
                            {{-- Add Product --}}
                            <a href="{{ route('admin.products.create') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Product
                            </a>

                            {{-- Import Excel --}}
                            <button type="button" x-data="" @click="$dispatch('open-modal', 'import-products')"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Import Excel
                            </button>

                            {{-- Export PDF --}}
                            <a href="{{ route('admin.products.export-pdf', request()->only(['search', 'filter_by'])) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#C41E3A] border border-transparent rounded-md text-sm font-medium text-white hover:bg-[#a01830] transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Export PDF
                            </a>
                        </div>
                    </div>

                    <!-- Bulk Actions Toolbar -->
                    <div class="flex flex-col lg:flex-row gap-3 justify-between items-start lg:items-center bg-gray-50 border border-gray-200 p-3 rounded-lg shadow-sm" x-show="selectedIds.length > 0" x-cloak>
                        <div class="flex items-center gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#C41E3A] text-xs font-bold text-white shadow-sm" x-text="selectedIds.length"></span>
                            <span class="text-sm font-medium text-gray-700">products selected</span>
                        </div>
                        <div class="flex flex-wrap gap-2 w-full lg:w-auto justify-end">
                            {{-- Bulk Export PDF --}}
                            <form method="POST" action="{{ route('admin.products.bulk-export-pdf') }}" target="_blank" class="inline">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Export Selected
                                </button>
                            </form>

                            {{-- Bulk Delete --}}
                            <form method="POST" action="{{ route('admin.products.bulk-destroy') }}" x-ref="bulkDeleteForm" class="inline" @submit.prevent="if(confirm('Are you sure you want to delete ' + selectedIds.length + ' selected products? This cannot be undone.')) $refs.bulkDeleteForm.submit()">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete Selected
                                </button>
                            </form>

                            {{-- Cancel Selection --}}
                            <button type="button" @click="selectedIds = []" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                                Cancel
                            </button>
                        </div>
                    </div>

                    {{-- Active filter indicator --}}
                    @if(request('search'))
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-[#C41E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                            Showing results for <span class="font-semibold text-gray-700">"{{ request('search') }}"</span>
                            @if(request('filter_by'))
                                in <span class="font-semibold text-gray-700">{{ ['name' => 'Product Name', 'product_code' => 'SKU / Code', 'unit_value' => 'Weight', 'box_price' => 'Box Price', 'unit_price' => 'Unit Price'][request('filter_by')] ?? request('filter_by') }}</span>
                            @endif
                            &mdash; <a href="{{ route('admin.products.index') }}" class="text-[#C41E3A] hover:underline">Clear filter</a>
                        </div>
                    @endif
                </div>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th class="w-10 text-center">
                    <input type="checkbox" 
                           :checked="allIds.length > 0 && allIds.every(id => selectedIds.includes(id))"
                           @change="$event.target.checked 
                               ? selectedIds = [...new Set([...selectedIds, ...allIds])] 
                               : selectedIds = selectedIds.filter(id => !allIds.includes(id))"
                           class="h-4 w-4 rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition-colors cursor-pointer"
                           title="Select all products on this page">
                </x-admin.ui.th>
                <x-admin.ui.th class="w-10">#</x-admin.ui.th>
                <x-admin.ui.th class="w-12 text-center">Image</x-admin.ui.th>
                <x-admin.ui.th>Product Name</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Box Price</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Sell / Unit</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Buy / Unit</x-admin.ui.th>
                <x-admin.ui.th>Weight</x-admin.ui.th>
                <x-admin.ui.th class="text-center">Pcs / Box</x-admin.ui.th>
                <x-admin.ui.th class="text-center">Featured</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/60 transition-colors group" :class="{ 'bg-gray-50/80': selectedIds.includes({{ $product->id }}) }">
                        {{-- Checkbox --}}
                        <x-admin.ui.td class="text-center">
                            <input type="checkbox" 
                                   value="{{ $product->id }}" 
                                   x-model="selectedIds"
                                   class="h-4 w-4 rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition-colors cursor-pointer">
                        </x-admin.ui.td>

                        {{-- Row index --}}
                        <x-admin.ui.td class="text-gray-400 text-xs tabular-nums">
                            {{ $products->firstItem() ? ($loop->iteration + $products->firstItem() - 1) : $loop->iteration }}
                        </x-admin.ui.td>

                        {{-- Thumbnail --}}
                        <x-admin.ui.td class="w-12">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="h-10 w-10 object-cover rounded-lg border border-gray-200 shadow-sm">
                            @else
                                <div class="h-10 w-10 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </x-admin.ui.td>

                        {{-- Product name & meta --}}
                        <x-admin.ui.td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="font-medium text-gray-900 hover:text-[#C41E3A] transition-colors">
                                {{ $product->name }}
                            </a>
                            <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                @if($product->product_code)
                                    <span class="font-mono text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ $product->product_code }}</span>
                                @endif
                                @if($product->category)
                                    <span class="text-xs text-gray-400">{{ $product->category->name }}</span>
                                @endif
                            </div>
                        </x-admin.ui.td>

                        {{-- Box price --}}
                        <x-admin.ui.td class="text-right tabular-nums">
                            <span class="text-gray-900 font-medium">${{ number_format($product->box_price ?? 0, 2) }}</span>
                        </x-admin.ui.td>

                        {{-- Sell unit price --}}
                        <x-admin.ui.td class="text-right tabular-nums">
                            <span class="text-green-700 font-medium">${{ number_format($product->unit_price ?? 0, 2) }}</span>
                        </x-admin.ui.td>

                        {{-- Buying price --}}
                        <x-admin.ui.td class="text-right tabular-nums">
                            <span class="text-gray-500">${{ number_format($product->buying_price ?? 0, 2) }}</span>
                        </x-admin.ui.td>

                        {{-- Weight --}}
                        <x-admin.ui.td class="text-gray-600 text-sm">
                            {{ $product->weight_display ?? '—' }}
                        </x-admin.ui.td>

                        {{-- Pcs per box --}}
                        <x-admin.ui.td class="text-center">
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                {{ $product->pcs_in_ctn }}
                            </span>
                        </x-admin.ui.td>

                        {{-- Featured toggle --}}
                        <x-admin.ui.td class="text-center">
                            <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    role="switch"
                                    aria-checked="{{ $product->is_featured ? 'true' : 'false' }}"
                                    title="{{ $product->is_featured ? 'Remove from featured' : 'Mark as featured' }}"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-offset-2 {{ $product->is_featured ? 'bg-[#C41E3A]' : 'bg-gray-200' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $product->is_featured ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </form>
                        </x-admin.ui.td>

                        {{-- Actions --}}
                        <x-admin.ui.td class="text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 hover:border-gray-300 transition-colors shadow-sm"
                                   title="Edit product">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs text-red-600 bg-white border border-red-200 rounded-md hover:bg-red-50 hover:border-red-300 transition-colors shadow-sm"
                                            title="Delete product">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </x-admin.ui.td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <p class="font-medium text-gray-500">No products found</p>
                                @if(request('search'))
                                    <a href="{{ route('admin.products.index') }}" class="text-sm text-[#C41E3A] hover:underline">Clear filter and show all</a>
                                @else
                                    <a href="{{ route('admin.products.create') }}" class="text-sm text-[#C41E3A] hover:underline">Add your first product</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </x-admin.ui.card>
    </div>

    <x-admin.ui.modal name="import-products" title="Import Products from Excel">
        <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4">
                <p class="text-sm text-gray-600">
                    Upload an <strong>.xlsx</strong>, <strong>.xls</strong>, or <strong>.csv</strong> file. Use the template so column headers and format match.
                </p>
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <p class="text-sm font-medium text-gray-700">Step 1: Get the template</p>
                    <a href="{{ route('admin.products.import.template') }}" download
                       class="inline-flex w-fit items-center gap-2 rounded-md border border-[#C41E3A] bg-white px-3 py-2 text-sm font-medium text-[#C41E3A] hover:bg-[#C41E3A] hover:text-white transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download product import template (.xlsx)
                    </a>
                    <p class="text-xs text-gray-500">Template includes column headers and a second sheet with instructions. Fill the <strong>Products</strong> sheet (first sheet) from row 2.</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Step 2: Upload your file</p>
                    <x-admin.form.label for="import_file" value="Select Excel File" />
                    <input type="file" id="import_file" name="file" accept=".xlsx,.xls,.csv"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 cursor-pointer" required />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-admin.actions.button type="button" variant="secondary" @click="$dispatch('close-modal', 'import-products')">
                    Cancel
                </x-admin.actions.button>
                <x-admin.actions.button type="submit" variant="primary">
                    Import Products
                </x-admin.actions.button>
            </div>
        </form>
    </x-admin.ui.modal>
@endsection
