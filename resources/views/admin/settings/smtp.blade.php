@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">SMTP Settings</h3>

    <div class="mt-8">
        <form action="{{ route('admin.settings.smtp.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 bg-white rounded-md shadow-md">
                <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                    <div>
                        <label class="text-gray-700" for="mail_driver">Mail Driver</label>
                        <input name="mail_driver" type="text" value="{{ $settings['mail_driver'] ?? 'smtp' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="mail_host">Mail Host</label>
                        <input name="mail_host" type="text" value="{{ $settings['mail_host'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="mail_port">Mail Port</label>
                        <input name="mail_port" type="number" value="{{ $settings['mail_port'] ?? '587' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="mail_username">Mail Username</label>
                        <input name="mail_username" type="text" value="{{ $settings['mail_username'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="mail_password">Mail Password</label>
                        <input name="mail_password" type="password" value="{{ $settings['mail_password'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                     <div>
                        <label class="text-gray-700" for="mail_encryption">Mail Encryption</label>
                        <input name="mail_encryption" type="text" value="{{ $settings['mail_encryption'] ?? 'tls' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="mail_from_address">Mail From Address</label>
                        <input name="mail_from_address" type="email" value="{{ $settings['mail_from_address'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="mail_from_name">Mail From Name</label>
                        <input name="mail_from_name" type="text" value="{{ $settings['mail_from_name'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
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
