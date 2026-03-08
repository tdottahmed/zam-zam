@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Credit Note {{ $creditNote->credit_note_number }}</h1>
                @php
                    $statusColors = [
                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800',
                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800',
                        'refunded' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$creditNote->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    {{ ucfirst($creditNote->status) }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Requested on {{ $creditNote->created_at->format('M d, Y h:i A') }}
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <x-admin.actions.button href="{{ route('admin.credit-notes.index') }}" variant="secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </x-admin.actions.button>
            
            @if(in_array($creditNote->status, ['draft', 'pending']))
                <x-admin.actions.button href="{{ route('admin.credit-notes.edit', $creditNote) }}" variant="secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Request
                </x-admin.actions.button>

                <form action="{{ route('admin.credit-notes.destroy', $creditNote) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this credit note request?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete Request
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content: Items -->
        <div class="lg:col-span-2 space-y-6">
            <x-admin.ui.card>
                 <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                         <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Returned Items
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $creditNote->items->count() }} items</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3">Product</th>
                                <th class="px-6 py-3 text-right">Price</th>
                                <th class="px-6 py-3 text-center">Ordered Qty</th>
                                <th class="px-6 py-3 text-center">Credit Qty</th>
                                <th class="px-6 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($creditNote->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($item->product->images && count($item->product->images) > 0)
                                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="" class="h-10 w-10 rounded-lg object-cover bg-gray-100">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500">SKU: 
                                                <span class="text-gray-900 bg-orange-100 px-2 py-1 rounded">{{ $item->product->product_code }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-300">
                                    ${{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-900 dark:text-white font-medium">
                                    {{ $item->ordered_quantity ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-900 dark:text-white font-medium">
                                    {{ $item->credit_quantity }}
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($item->line_total, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                 <!-- Financial Summary Footer -->
                <div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col items-end gap-2">
                        <div class="w-full sm:w-64 space-y-2">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($creditNote->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>Tax Refund</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($creditNote->tax_amount, 2) }}</span>
                            </div>
                             <div class="border-t border-gray-200 dark:border-gray-700 my-2 pt-2">
                                <div class="flex justify-between text-lg font-bold">
                                    <span class="text-gray-900 dark:text-white">Total Refund</span>
                                    <span class="text-[#C41E3A]">${{ number_format($creditNote->grand_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.ui.card>
        </div>

        <!-- Sidebar: Summary -->
        <div class="space-y-6">
            
             <!-- Actions Card -->
             <x-admin.ui.card>
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Actions
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                     @if($creditNote->status === 'draft' || $creditNote->status === 'pending')
                         <p class="text-sm text-gray-500">Review the requested items and reason. You can approve or reject this credit note.</p>
                        <div class="grid grid-cols-2 gap-3">
                            <form action="{{ route('admin.credit-notes.update', $creditNote) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="w-full justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors flex items-center">
                                    Reject
                                </button>
                            </form>
                            <form action="{{ route('admin.credit-notes.update', $creditNote) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="w-full justify-center px-4 py-2 bg-[#C41E3A] text-white border border-transparent rounded-lg hover:bg-[#a01830] font-medium transition-colors shadow-sm flex items-center">
                                    Approve
                                </button>
                            </form>
                        </div>
                    @elseif($creditNote->status === 'approved')
                        <p class="text-sm text-gray-500">This credit note is approved. You can now mark it as refunded.</p>
                         <form action="{{ route('admin.credit-notes.update', $creditNote) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="refunded">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Mark as Refunded
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 text-center">
                            <p class="text-sm text-gray-500">No available actions for this status.</p>
                        </div>
                    @endif
                </div>
            </x-admin.ui.card>

             <!-- Customer Card -->
             <x-admin.ui.card>
                 <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Customer
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <span class="inline-block h-12 w-12 rounded-full bg-primary/10 text-primary flex items-center justify-center text-lg font-bold">
                                {{ substr($creditNote->user->name, 0, 1) }}
                            </span>
                        </div>
                         <div>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $creditNote->user->name }}
                            </p>
                            <a href="{{ route('admin.users.edit', $creditNote->user) }}" class="text-xs text-primary hover:underline">View Profile</a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Contact Info</p>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $creditNote->user->email }}
                                </div>
                                @if($creditNote->user->phone)
                                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $creditNote->user->phone }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.ui.card>

            <!-- Admin Internal Notes -->
             <x-admin.ui.card>
                 <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Reason & Comments</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Reason for Return</span>
                        <p class="text-gray-900 dark:text-white font-medium capitalize">{{ str_replace('_', ' ', $creditNote->reason) }}</p>
                    </div>
                    @if($creditNote->admin_notes)
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Notes</span>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 text-gray-700 dark:text-gray-300 text-sm leading-relaxed border border-gray-100 dark:border-gray-700">
                            {{ $creditNote->admin_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </x-admin.ui.card>
        </div>
    </div>
</div>
@endsection
