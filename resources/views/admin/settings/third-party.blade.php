@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Third Party Settings</h3>

    <div class="mt-8">
        <form action="{{ route('admin.settings.third-party.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 bg-white rounded-md shadow-md">
                <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                    <div>
                        <label class="text-gray-700" for="google_analytics_id">Google Analytics ID</label>
                        <input name="google_analytics_id" type="text" value="{{ $settings['google_analytics_id'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="facebook_pixel_id">Facebook Pixel ID</label>
                        <input name="facebook_pixel_id" type="text" value="{{ $settings['facebook_pixel_id'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="stripe_key">Stripe Key</label>
                        <input name="stripe_key" type="text" value="{{ $settings['stripe_key'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="stripe_secret">Stripe Secret</label>
                        <input name="stripe_secret" type="text" value="{{ $settings['stripe_secret'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                     <div>
                        <label class="text-gray-700" for="paypal_client_id">PayPal Client ID</label>
                        <input name="paypal_client_id" type="text" value="{{ $settings['paypal_client_id'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label class="text-gray-700" for="paypal_secret">PayPal Secret</label>
                        <input name="paypal_secret" type="text" value="{{ $settings['paypal_secret'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button class="px-4 py-2 text-white bg-[#C41E3A] rounded-md hover:bg-opacity-90 focus:outline-none focus:bg-opacity-90">Save Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
