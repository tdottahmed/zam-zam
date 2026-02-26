@extends('layouts.admin')

@section('header')
    Mail Templates
@endsection

@section('content')

    <x-admin.ui.section-header>
        Mail Templates
        <x-slot:description>
            Manage your system mail notification templates.
        </x-slot:description>
        <x-slot:actions>
            <x-admin.actions.button href="{{ route('admin.mail-templates.create') }}" variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Template
            </x-admin.actions.button>
        </x-slot:actions>
    </x-admin.ui.section-header>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <form method="GET" action="{{ route('admin.mail-templates.index') }}" class="w-full max-w-sm">
                    <div class="relative">
                        <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search templates..." class="pl-10" />
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    </div>
                </form>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th>Type</x-admin.ui.th>
                <x-admin.ui.th>Subject</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th>Created At</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($mailTemplates as $template)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <x-admin.ui.td>
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 uppercase tracking-wide">
                                {{ str_replace('_', ' ', $template->type) }}
                            </span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            <span class="font-medium text-gray-900">{{ $template->subject }}</span>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            @if($template->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Inactive
                                </span>
                            @endif
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-gray-500 text-sm">
                            {{ $template->created_at->format('M j, Y') }}
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Edit --}}
                                <x-admin.actions.icon-button href="{{ route('admin.mail-templates.edit', $template) }}" variant="secondary" size="sm" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </x-admin.actions.icon-button>

                                <form method="POST" action="{{ route('admin.mail-templates.destroy', $template) }}" onsubmit="return confirm('Delete this template? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <x-admin.actions.icon-button type="submit" variant="danger" size="sm" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </x-admin.actions.icon-button>
                                </form>
                            </div>
                        </x-admin.ui.td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-700">No templates found</p>
                                    <p class="text-sm text-gray-400 mt-1">Get started by creating a new template.</p>
                                </div>
                                <x-admin.actions.button href="{{ route('admin.mail-templates.create') }}" variant="primary" size="sm" class="mt-4">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Create Template
                                </x-admin.actions.button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>

        <div class="mt-4">
            {{ $mailTemplates->links() }}
        </div>
    </x-admin.ui.card>
@endsection
