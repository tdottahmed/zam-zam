@extends('layouts.admin')

@section('header')
    Invoices
@endsection

@section('title', 'Invoices')

@section('content')
    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-4 py-3 text-sm text-green-800 dark:text-green-200" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-800 px-4 py-3 text-sm text-amber-800 dark:text-amber-200" role="alert">
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Invoices</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and track your invoices. Download PDFs or send them to customers.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <x-admin.actions.button href="{{ route('admin.invoices.create') }}" variant="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Invoice
            </x-admin.actions.button>
        </div>
    </div>

    <div x-data="{
        selectedIds: [],
        allIds: {{ $invoices->pluck('id')->toJson() }},
        init() {
            const saved = sessionStorage.getItem('jamjam_admin_invoices_selected');
            if (saved) this.selectedIds = JSON.parse(saved);
            this.$watch('selectedIds', val => sessionStorage.setItem('jamjam_admin_invoices_selected', JSON.stringify(val)));
        }
    }">
        <x-admin.ui.card>
            <x-admin.ui.table>
                <x-slot:search>
                    <div class="w-full space-y-4">
                        {{-- Main toolbar: search & filters (when nothing selected) --}}
                        <div class="flex flex-col lg:flex-row gap-4 w-full" x-show="selectedIds.length === 0">
                            <form action="{{ route('admin.invoices.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 w-full">
                                <div class="w-full lg:w-96 relative">
                                    <x-admin.form.input name="search" value="{{ request('search') }}" placeholder="Search Invoice #, Customer..." />
                                    <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 text-gray-500 hover:text-primary dark:hover:text-primary">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </button>
                                </div>
                                <div class="w-full sm:w-48">
                                    <x-admin.form.select name="status" :options="[
                                        '' => 'All Statuses',
                                        'draft' => 'Draft',
                                        'sent' => 'Sent',
                                        'paid' => 'Paid',
                                        'overdue' => 'Overdue',
                                        'cancelled' => 'Cancelled',
                                    ]" :selected="request('status')" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md transition-colors font-medium">
                                        Filter
                                    </button>
                                    @if(request()->anyFilled(['search', 'status']))
                                        <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium">
                                            Clear
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        {{-- Bulk actions toolbar --}}
                        <div class="flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 p-4 rounded-lg shadow-sm" x-show="selectedIds.length > 0" x-cloak x-transition>
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#C41E3A] text-sm font-bold text-white shadow-sm" x-text="selectedIds.length"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">invoices selected</span>
                            </div>
                            <div class="flex flex-wrap gap-2 w-full sm:w-auto justify-end">
                                <form method="POST" action="{{ route('admin.invoices.bulk-send-email') }}" class="inline" @submit.prevent="if(confirm('Send invoice email to customers for ' + selectedIds.length + ' selected invoice(s)?')) $el.submit()">
                                    @csrf
                                    <template x-for="id in selectedIds" :key="id">
                                        <input type="hidden" name="ids[]" :value="id">
                                    </template>
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-[#C41E3A] hover:bg-[#a91930] text-white rounded-md text-sm font-medium transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Send invoice email
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.invoices.bulk-destroy') }}" x-ref="bulkDeleteForm" class="inline" @submit.prevent="if(confirm('Permanently delete ' + selectedIds.length + ' selected invoice(s)? This cannot be undone.')) $refs.bulkDeleteForm.submit()">
                                    @csrf
                                    <template x-for="id in selectedIds" :key="id">
                                        <input type="hidden" name="ids[]" :value="id">
                                    </template>
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium transition-colors shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete selected
                                    </button>
                                </form>
                                <button type="button" @click="selectedIds = []" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </x-slot:search>

                <x-slot:head>
                    <x-admin.ui.th class="w-10 text-center">
                        <input type="checkbox"
                               :checked="allIds.length > 0 && allIds.every(id => selectedIds.includes(id))"
                               @change="$event.target.checked ? selectedIds = [...new Set([...selectedIds, ...allIds])] : selectedIds = selectedIds.filter(id => !allIds.includes(id))"
                               class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-[#C41E3A] focus:ring-[#C41E3A] cursor-pointer"
                               title="Select all on this page">
                    </x-admin.ui.th>
                    <x-admin.ui.th>Invoice #</x-admin.ui.th>
                    <x-admin.ui.th>Date</x-admin.ui.th>
                    <x-admin.ui.th>Customer / Order</x-admin.ui.th>
                    <x-admin.ui.th class="text-right">Amount</x-admin.ui.th>
                    <x-admin.ui.th class="text-center">Status</x-admin.ui.th>
                    <x-admin.ui.th class="text-right">Actions</x-admin.ui.th>
                </x-slot:head>

                <x-slot:body>
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" :class="{ 'bg-gray-50 dark:bg-gray-800/80': selectedIds.includes({{ $invoice->id }}) }">
                            <x-admin.ui.td class="text-center">
                                <input type="checkbox"
                                       value="{{ $invoice->id }}"
                                       x-model="selectedIds"
                                       class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-[#C41E3A] focus:ring-[#C41E3A] cursor-pointer">
                            </x-admin.ui.td>
                            <x-admin.ui.td class="font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="hover:text-[#C41E3A] dark:hover:text-[#C41E3A] transition-colors">{{ $invoice->invoice_number }}</a>
                            </x-admin.ui.td>
                            <x-admin.ui.td class="whitespace-nowrap">
                                <div>{{ $invoice->invoice_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">Due: {{ $invoice->due_date->format('M d, Y') }}</div>
                            </x-admin.ui.td>
                            <x-admin.ui.td>
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-[#C41E3A]/10 dark:bg-[#C41E3A]/20 flex items-center justify-center text-[#C41E3A] font-bold mr-3 text-sm">
                                        {{ strtoupper(substr($invoice->order->user->name ?? 'G', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $invoice->order->user->name ?? 'Guest' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Order: <a href="{{ route('admin.orders.show', $invoice->order) }}" class="hover:underline text-[#C41E3A]">#{{ $invoice->order->id }}</a>
                                        </div>
                                    </div>
                                </div>
                            </x-admin.ui.td>
                            <x-admin.ui.td class="text-right font-medium text-gray-900 dark:text-white">
                                ${{ number_format($invoice->total, 2) }}
                                @if($invoice->advance_amount > 0)
                                    <div class="text-xs font-normal text-blue-600">Advance: ${{ number_format($invoice->advance_amount, 2) }}</div>
                                    <div class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                        {{ $invoice->balance_due < 0 ? 'Credit' : 'Due' }}: ${{ number_format(abs($invoice->balance_due), 2) }}
                                    </div>
                                @endif
                            </x-admin.ui.td>
                            <x-admin.ui.td class="text-center">
                                @php
                                    $statusClasses = [
                                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'sent' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'cancelled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    ];
                                    $class = $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $class }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </x-admin.ui.td>
                            <x-admin.ui.td class="text-right whitespace-nowrap">
                                <div class="flex justify-end items-center gap-1">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded transition-colors" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.invoices.download', $invoice) }}" class="p-2 text-gray-400 hover:text-[#C41E3A] dark:hover:text-[#C41E3A] rounded transition-colors" title="Download PDF (saves to server if needed)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded transition-colors" title="Open PDF in new tab">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="p-2 text-gray-400 hover:text-[#C41E3A] dark:hover:text-[#C41E3A] rounded transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </x-admin.ui.td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-14 h-14 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-lg font-medium">No invoices found</p>
                                    <p class="text-sm mt-1">Try adjusting your filters or create a new invoice.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-slot:body>
            </x-admin.ui.table>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $invoices->withQueryString()->links() }}
            </div>
        </x-admin.ui.card>
    </div>
@endsection
