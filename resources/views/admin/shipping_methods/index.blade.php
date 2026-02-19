@extends('layouts.admin')

@section('header')
    Shipping Methods
@endsection

@section('content')
    <x-admin.ui.section-header>
        Shipping Methods
        <x-slot:actions>
            <x-admin.actions.button href="{{ route('admin.shipping-methods.create') }}" variant="primary" size="sm">
                Add New Method
            </x-admin.actions.button>
        </x-slot:actions>
    </x-admin.ui.section-header>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:head>
                <x-admin.ui.th>Name</x-admin.ui.th>
                <x-admin.ui.th>Cost</x-admin.ui.th>
                <x-admin.ui.th>Estimate</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($methods as $method)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td class="font-medium text-gray-900">{{ $method->name }}</x-admin.ui.td>
                        <x-admin.ui.td class="text-gray-500">
                            @if($method->cost !== null)
                                ${{ number_format($method->cost, 2) }}
                            @else
                                <span class="text-xs text-gray-400 italic">Calculated at checkout</span>
                            @endif
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-gray-500">{{ $method->estimated_delivery_time ?? '-' }}</x-admin.ui.td>
                        <x-admin.ui.td>
                            @if($method->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-right">
                             <div class="flex items-center justify-end gap-2">
                                <x-admin.actions.icon-button href="{{ route('admin.shipping-methods.edit', $method) }}" variant="secondary" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </x-admin.actions.icon-button>
                                
                                <form action="{{ route('admin.shipping-methods.destroy', $method) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This method will be removed from future selection, but existing orders will keep their history.')">
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
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No shipping methods found.</td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>
    </x-admin.ui.card>
@endsection
