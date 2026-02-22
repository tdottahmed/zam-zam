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
                            <button type="button" x-data="" @click="$dispatch('open-modal', 'import-products')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Import
                            </button>
                            <a href="{{ route('admin.products.export-pdf', request()->only(['search', 'filter_by'])) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-[#C41E3A] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#a01830] active:bg-[#8a1428] focus:outline-none focus:border-[#8a1428] focus:ring ring-[#C41E3A] disabled:opacity-25 transition ease-in-out duration-150">
                                Export PDF
                            </a>
                        </div>
                    </div>

                </div>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th>Code</x-admin.ui.th>
                <x-admin.ui.th>Image</x-admin.ui.th>
                <x-admin.ui.th>Product Name</x-admin.ui.th>
                <x-admin.ui.th>Packing</x-admin.ui.th>
                <x-admin.ui.th>Box Price</x-admin.ui.th>
                <x-admin.ui.th>Unit Price</x-admin.ui.th>
                <x-admin.ui.th class="text-center">Featured</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td class="font-mono text-xs text-gray-500">{{ $product->product_code }}</x-admin.ui.td>
                        <x-admin.ui.td class="w-16">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded-md border border-gray-200">
                            @else
                                <div class="h-10 w-10 bg-gray-50 rounded-md border border-gray-200 flex items-center justify-center text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            <span class="font-medium text-gray-900">{{ $product->name }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>{{ $product->pcs_in_ctn }}</x-admin.ui.td>
                        <x-admin.ui.td>${{ number_format($product->box_price, 2) }}</x-admin.ui.td>
                        <x-admin.ui.td>${{ number_format($product->unit_price, 2) }}</x-admin.ui.td>
                        <x-admin.ui.td class="text-center">
                            <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" role="switch" aria-checked="{{ $product->is_featured ? 'true' : 'false' }}" title="{{ $product->is_featured ? 'Remove from homepage featured' : 'Show on homepage featured' }}" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-offset-2 {{ $product->is_featured ? 'bg-[#C41E3A]' : 'bg-gray-200 dark:bg-gray-600' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $product->is_featured ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </form>
                        </x-admin.ui.td>
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
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
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

    <x-admin.ui.modal name="import-products" title="Import Products">
        <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4">
                <p class="text-sm text-gray-600">
                    Upload an Excel file (.xlsx, .xls) to import products. The file should follow the provided template format.
                </p>
                <div>
                    <x-admin.form.label for="import_file" value="Select File" />
                    <input type="file" id="import_file" name="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100" required />
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
