@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">General Settings</h3>
    </div>

    <div class="mt-4">
        <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Site Identity Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Site Identity & Contact</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="site_name">Site Name</label>
                                <input name="site_name" type="text" value="{{ $settings['site_name'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="contact_email">Contact Email</label>
                                <input name="contact_email" type="email" value="{{ $settings['contact_email'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="contact_phone">Contact Phone</label>
                                <input name="contact_phone" type="text" value="{{ $settings['contact_phone'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>
                            
                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="currency_symbol">Currency Symbol</label>
                                <input name="currency_symbol" type="text" value="{{ $settings['currency_symbol'] ?? '$' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="address">Address</label>
                                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">{{ $settings['address'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Rules Card -->
                 <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Business Rules</h4>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="default_profit_margin">Default Profit Margin (%)</label>
                                <div class="relative">
                                    <input name="default_profit_margin" type="number" step="0.01" min="0" value="{{ $settings['default_profit_margin'] ?? '0.00' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm pr-8">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Applied to new products by default.</p>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="auto_send_invoice" name="auto_send_invoice" type="checkbox" value="1" {{ ($settings['auto_send_invoice'] ?? '0') == '1' ? 'checked' : '' }} class="focus:ring-[#C41E3A] h-4 w-4 text-[#C41E3A] border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="auto_send_invoice" class="font-medium text-gray-700">Auto-send Invoice</label>
                                    <p class="text-gray-500">Automatically email invoices to customers upon order completion.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-2.5 bg-[#C41E3A] text-white font-medium text-sm leading-tight uppercase rounded-lg shadow-md hover:bg-[#a01830] hover:shadow-lg focus:bg-[#a01830] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-[#801326] active:shadow-lg transition duration-150 ease-in-out flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
