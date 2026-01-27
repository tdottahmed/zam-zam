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
        <x-slot:actions>
             <x-admin.actions.button href="{{ route('admin.products.create') }}" variant="primary">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Add Product
             </x-admin.actions.button>
        </x-slot:actions>
    </x-admin.ui.section-header>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <form method="GET" action="{{ route('admin.products.index') }}" class="w-full max-w-sm">
                    <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search products..." />
                </form>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th>Product Name</x-admin.ui.th>
                <x-admin.ui.th>Box Price</x-admin.ui.th>
                <x-admin.ui.th>Unit Price</x-admin.ui.th>
                <x-admin.ui.th>Weight</x-admin.ui.th>
                <x-admin.ui.th>Packaging</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td>
                            <span class="font-medium text-gray-900">{{ $product->name }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>${{ number_format($product->box_price, 2) }}</x-admin.ui.td>
                        <x-admin.ui.td>${{ number_format($product->unit_price, 2) }}</x-admin.ui.td>
                        <x-admin.ui.td>{{ $product->weight }}</x-admin.ui.td>
                        <x-admin.ui.td>{{ $product->packaging }}</x-admin.ui.td>
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
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
