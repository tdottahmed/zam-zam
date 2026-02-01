@extends('layouts.admin')

@section('header')
    Taxes
@endsection

@section('content')

    <x-admin.ui.section-header>
        Taxes
        <x-slot:description>
            Manage tax rates.
        </x-slot:description>
        <x-slot:actions>
             <x-admin.actions.button href="{{ route('admin.taxes.create') }}" variant="primary">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 Add Tax
             </x-admin.actions.button>
        </x-slot:actions>
    </x-admin.ui.section-header>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <form method="GET" action="{{ route('admin.taxes.index') }}" class="w-full max-w-sm">
                    <div class="relative">
                        <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search taxes..." class="pl-10" />
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    </div>
                </form>
            </x-slot:search>
            <x-slot:head>
                <x-admin.ui.th>Name</x-admin.ui.th>
                <x-admin.ui.th>Value</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($taxes as $tax)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td>
                            <span class="font-medium text-gray-900">{{ $tax->name }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            {{ number_format($tax->value, 2) }}%
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            @if($tax->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-right">
                             <div class="flex items-center justify-end gap-2">
                                <x-admin.actions.icon-button href="{{ route('admin.taxes.edit', $tax) }}" variant="secondary" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </x-admin.actions.icon-button>
                                
                                <form method="POST" action="{{ route('admin.taxes.destroy', $tax) }}" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No taxes found.
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>

        <div class="mt-4">
            {{ $taxes->links() }}
        </div>
    </x-admin.ui.card>

@endsection
