@extends('layouts.admin')

@section('header')
    Newsletter Subscribers
@endsection

@section('content')
    <x-admin.ui.section-header>
        Newsletter Subscribers
        <x-slot:description>
            Emails collected from the footer newsletter form.
        </x-slot:description>
    </x-admin.ui.section-header>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <form method="GET" action="{{ route('admin.newsletter-subscribers.index') }}" class="w-full max-w-sm">
                    <div class="relative">
                        <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search by email..." class="pl-10" />
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    </div>
                </form>
            </x-slot:search>
            <x-slot:head>
                <x-admin.ui.th class="w-12">SL</x-admin.ui.th>
                <x-admin.ui.th>Email</x-admin.ui.th>
                <x-admin.ui.th>Subscribed at</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>
            <x-slot:body>
                @forelse($subscribers as $subscriber)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td class="text-gray-500">{{ $subscribers->firstItem() ? ($loop->iteration + $subscribers->firstItem() - 1) : $loop->iteration }}</x-admin.ui.td>
                        <x-admin.ui.td>
                            <span class="font-medium text-gray-900">{{ $subscriber->email }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-gray-600">{{ $subscriber->created_at->format('M d, Y H:i') }}</x-admin.ui.td>
                        <x-admin.ui.td class="text-right">
                            <form method="POST" action="{{ route('admin.newsletter-subscribers.destroy', $subscriber) }}" class="inline-block" onsubmit="return confirm('Remove this subscriber?');">
                                @csrf
                                @method('DELETE')
                                <x-admin.actions.icon-button type="submit" variant="danger" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </x-admin.actions.icon-button>
                            </form>
                        </x-admin.ui.td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No subscribers yet.
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $subscribers->links() }}
        </div>
    </x-admin.ui.card>
@endsection
