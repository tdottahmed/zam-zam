@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Third Party Settings</h3>

    <div class="mt-8">
        <form action="{{ route('admin.settings.third-party.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Analytics & Tracking -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                        <div class="p-2 bg-blue-50 rounded-lg mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Analytics & Tracking</h4>
                            <p class="text-sm text-gray-500">Configure your tracking codes</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="google_analytics_id">Google Analytics ID</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">G-</span>
                                </div>
                                <input name="google_analytics_id" type="text" value="{{ $settings['google_analytics_id'] ?? '' }}" class="focus:ring-[#C41E3A] focus:border-[#C41E3A] block w-full pl-8 sm:text-sm border-gray-300 rounded-lg" placeholder="XXXXXXXXXX">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Your Google Analytics 4 Measurement ID.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="facebook_pixel_id">Facebook Pixel ID</label>
                            <input name="facebook_pixel_id" type="text" value="{{ $settings['facebook_pixel_id'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="1234567890">
                            <p class="mt-1 text-xs text-gray-500">Your Meta Pixel ID for tracking conversions.</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Gateways -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                     <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                        <div class="p-2 bg-green-50 rounded-lg mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Payment Gateways</h4>
                            <p class="text-sm text-gray-500">Manage Stripe and PayPal credentials</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Stripe -->
                        <div class="border-b border-gray-100 pb-6">
                            <h5 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                                <span class="w-2 h-2 bg-[#635BFF] rounded-full mr-2"></span> Stripe
                            </h5>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1" for="stripe_key">Public Key</label>
                                    <input name="stripe_key" type="text" value="{{ $settings['stripe_key'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#635BFF] focus:ring focus:ring-[#635BFF] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1" for="stripe_secret">Secret Key</label>
                                    <input name="stripe_secret" type="password" value="{{ $settings['stripe_secret'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#635BFF] focus:ring focus:ring-[#635BFF] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div>
                             <h5 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                                <span class="w-2 h-2 bg-[#003087] rounded-full mr-2"></span> PayPal
                            </h5>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1" for="paypal_client_id">Client ID</label>
                                    <input name="paypal_client_id" type="text" value="{{ $settings['paypal_client_id'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#003087] focus:ring focus:ring-[#003087] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1" for="paypal_secret">Secret Key</label>
                                    <input name="paypal_secret" type="password" value="{{ $settings['paypal_secret'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#003087] focus:ring focus:ring-[#003087] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8">
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
