@extends('layouts.admin')

@section('header')
    Units
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Unit List</h2>
        <x-admin.actions.button href="{{ route('admin.units.create') }}" variant="primary">
            Add Unit
        </x-admin.actions.button>
    </div>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <form method="GET" action="{{ route('admin.units.index') }}" class="w-full max-w-sm">
                    <div class="relative">
                        <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search units..." class="pl-10" />
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    </div>
                </form>
            </x-slot:search>
            <x-slot:head>
                <x-admin.ui.th>Name</x-admin.ui.th>
                <x-admin.ui.th>Code</x-admin.ui.th>
                <x-admin.ui.th>Type</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>
            <x-slot:body>
                @forelse($units as $unit)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td>
                            <span class="font-medium text-gray-900">{{ $unit->name }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $unit->code }}
                            </span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            @php
                                $typeStyles = [
                                    'weight' => 'bg-blue-100 text-blue-800',
                                    'stock'  => 'bg-green-100 text-green-800',
                                    'both'   => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeStyles[$unit->type] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($unit->type) }}
                            </span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            @if($unit->is_active)
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
                            <div class="flex items-center justify-end space-x-2">
                                <x-admin.actions.edit href="{{ route('admin.units.edit', $unit) }}" />
                                <x-admin.actions.delete action="{{ route('admin.units.destroy', $unit) }}" />
                            </div>
                        </x-admin.ui.td>
                    </tr>
                @empty
                    <tr>
                        <x-admin.ui.td colspan="4" class="text-center text-gray-500 py-8">
                            No units found. <a href="{{ route('admin.units.create') }}" class="text-indigo-600 hover:text-indigo-900">Create one</a>
                        </x-admin.ui.td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $units->links() }}
        </div>
    </x-admin.ui.card>
@endsection
