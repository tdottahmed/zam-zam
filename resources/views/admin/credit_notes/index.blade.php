@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Credit Notes</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage customer returns and refunds.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-admin.ui.card class="group relative overflow-hidden">
             <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50/50 transition-all group-hover:bg-blue-50 dark:bg-blue-900/10"></div>
            <div class="relative flex items-center justify-between p-6">
                <div>
                     <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Requests</p>
                    <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\CreditNote::where('status', 'draft')->count() }}</h4>
                </div>
                 <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </x-admin.ui.card>

        <x-admin.ui.card class="group relative overflow-hidden">
             <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-green-50/50 transition-all group-hover:bg-green-50 dark:bg-green-900/10"></div>
            <div class="relative flex items-center justify-between p-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved (This Month)</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\CreditNote::where('status', 'approved')->whereMonth('created_at', now()->month)->count() }}</p>
                </div>
                 <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-admin.ui.card>

         <x-admin.ui.card class="group relative overflow-hidden">
             <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-purple-50/50 transition-all group-hover:bg-purple-50 dark:bg-purple-900/10"></div>
            <div class="relative flex items-center justify-between p-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Refunded</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">${{ number_format(\App\Models\CreditNote::where('status', 'refunded')->sum('grand_total'), 2) }}</p>
                </div>
                 <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-admin.ui.card>
    </div>

    <!-- Table -->
    <x-admin.ui.card>
        <x-admin.ui.table>
            <x-slot:search>
                <div class="w-full space-y-4">
                     <form action="{{ route('admin.credit-notes.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 w-full">
                        <!-- Search Input -->
                        <div class="w-full lg:w-96 relative">
                            <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search Number, Order, Customer..." />
                            <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-500 hover:text-primary">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>

                         <!-- Status Filter -->
                         <div class="w-full sm:w-48">
                            <x-admin.form.select name="status" :options="[
                                '' => 'All Statuses',
                                'draft' => 'Draft / Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'refunded' => 'Refunded',
                            ]" :selected="request('status')" />
                        </div>

                        <div class="flex items-center gap-2">
                             <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md transition-colors">
                                Filter
                            </button>
                             @if(request()->anyFilled(['search', 'status']))
                                <a href="{{ route('admin.credit-notes.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </x-slot:search>

            <x-slot:head>
                <x-admin.ui.th>CN Number</x-admin.ui.th>
                <x-admin.ui.th>Customer</x-admin.ui.th>
                <x-admin.ui.th>Date</x-admin.ui.th>
                <x-admin.ui.th>Items</x-admin.ui.th>
                <x-admin.ui.th>Total</x-admin.ui.th>
                <x-admin.ui.th>Status</x-admin.ui.th>
                <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
            </x-slot:head>

            <x-slot:body>
                @forelse($creditNotes as $cn)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <x-admin.ui.td class="font-mono text-xs font-bold text-gray-900 border-l-4 border-transparent hover:border-primary transition-all">
                        {{ $cn->credit_note_number }}
                    </x-admin.ui.td>
                    <x-admin.ui.td>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $cn->user->name }}</span>
                            <span class="text-xs text-gray-500">{{ $cn->user->email }}</span>
                        </div>
                    </x-admin.ui.td>
                    <x-admin.ui.td class="text-gray-500 text-sm">
                        {{ $cn->created_at->format('M d, Y') }}
                    </x-admin.ui.td>
                     <x-admin.ui.td class="text-gray-500 text-sm">
                        {{ $cn->items_count }} items
                    </x-admin.ui.td>
                    <x-admin.ui.td class="font-medium text-gray-900 dark:text-white">
                        ${{ number_format($cn->grand_total, 2) }}
                    </x-admin.ui.td>
                    <x-admin.ui.td>
                           <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium 
                            {{ $cn->status === 'approved' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : '' }}
                            {{ $cn->status === 'draft' ? 'bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-400' : '' }}
                            {{ $cn->status === 'pending' ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                            {{ $cn->status === 'refunded' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                            {{ $cn->status === 'rejected' ? 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                             <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            {{ ucfirst($cn->status) }}
                        </span>
                    </x-admin.ui.td>
                    <x-admin.ui.td class="text-right">
                        <a href="{{ route('admin.credit-notes.show', $cn) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="View Details">
                             <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Details
                        </a>
                    </x-admin.ui.td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>No credit notes found.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </x-slot:body>
        </x-admin.ui.table>
        @if($creditNotes->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $creditNotes->links() }}
        </div>
        @endif
    </x-admin.ui.card>
</div>
@endsection
