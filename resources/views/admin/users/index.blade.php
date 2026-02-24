@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Customers</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage customer accounts, profiles, and addresses.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
             <x-admin.actions.button href="{{ route('admin.users.create') }}" variant="primary">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Customer
            </x-admin.actions.button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-admin.ui.card class="md:col-span-1">
            <div class="flex items-center p-6">
                <div class="p-3 rounded-full bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::where('user_type', 'user')->count() }}</p>
                </div>
            </div>
        </x-admin.ui.card>
    </div>

    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <div class="w-full space-y-4">
                     <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 w-full items-end">
                        <div class="w-full lg:w-80 relative">
                             <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search by name or email..." />
                            <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-500 hover:text-primary">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>
                        <div class="w-full lg:w-48">
                            <x-admin.form.select name="status" :options="['' => 'All statuses', 'pending' => 'Pending', 'approved' => 'Approved']" :selected="request('status')" />
                        </div>
                        <x-admin.actions.button type="submit" variant="secondary">Filter</x-admin.actions.button>
                    </form>
                </div>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th>Customer</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th>Joined</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <x-admin.ui.td>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </x-admin.ui.td>
                        <x-admin.ui.td>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($user->status ?? 'approved') === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' }}">
                                {{ ucfirst($user->status ?? 'approved') }}
                            </span>
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </x-admin.ui.td>
                        <x-admin.ui.td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-admin.actions.icon-button href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" variant="ghost" size="sm" title="View orders & history">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </x-admin.actions.icon-button>
                                <x-admin.actions.icon-button href="{{ route('admin.users.edit', $user) }}" variant="secondary" size="sm" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </x-admin.actions.icon-button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this customer? This cannot be undone.');">
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
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-lg font-medium">No customers found</p>
                                <p class="text-sm">Try adjusting your search or add a new customer.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>
        
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </x-admin.ui.card>
@endsection
