@extends('layouts.admin')

@section('header')
    Offline Payment Methods
@endsection

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Payment Methods List</h2>
            <p class="text-sm text-gray-500 mt-1">Manage custom offline payment gateways like Bank Transfer, Mobile Money, etc.</p>
        </div>
        <x-admin.actions.button href="{{ route('admin.offline-payment-methods.create') }}" variant="primary">
            Add Method
        </x-admin.actions.button>
    </div>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:head>
                <x-admin.ui.th>Name</x-admin.ui.th>
                <x-admin.ui.th>Description</x-admin.ui.th>
                <x-admin.ui.th>Required Fields</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>
            <x-slot:body>
                @forelse($methods as $method)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td>
                            <span class="font-bold text-gray-900">{{ $method->name }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            <span class="text-sm text-gray-500">{{ Str::limit($method->description, 50) }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            @php
                                $fields = is_array($method->required_fields) ? $method->required_fields : [];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                {{ count($fields) }} Field(s)
                            </span>
                        </x-admin.ui.td>
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
                            <div class="flex items-center justify-end space-x-2">
                                <x-admin.actions.edit href="{{ route('admin.offline-payment-methods.edit', $method) }}" />
                                <x-admin.actions.delete action="{{ route('admin.offline-payment-methods.destroy', $method) }}" />
                            </div>
                        </x-admin.ui.td>
                    </tr>
                @empty
                    <tr>
                        <x-admin.ui.td colspan="5" class="text-center text-gray-500 py-8">
                            No payment methods found. <a href="{{ route('admin.offline-payment-methods.create') }}" class="text-[#C41E3A] hover:text-[#a01830] font-medium">Create one</a>
                        </x-admin.ui.td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>
    </x-admin.ui.card>
@endsection
