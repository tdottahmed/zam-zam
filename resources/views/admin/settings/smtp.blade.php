@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">SMTP Settings</h3>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- SMTP Configuration Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.settings.smtp.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 h-full">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Configuration</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_driver">Mail Driver</label>
                            <input name="mail_driver" type="text" value="{{ $settings['mail_driver'] ?? 'smtp' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_host">Mail Host</label>
                            <input name="mail_host" type="text" value="{{ $settings['mail_host'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_port">Mail Port</label>
                            <input name="mail_port" type="number" value="{{ $settings['mail_port'] ?? '587' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_username">Mail Username</label>
                            <input name="mail_username" type="text" value="{{ $settings['mail_username'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_password">Mail Password</label>
                            <input name="mail_password" type="password" value="{{ $settings['mail_password'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                        </div>

                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_encryption">Mail Encryption</label>
                            <input name="mail_encryption" type="text" value="{{ $settings['mail_encryption'] ?? 'tls' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="tls, ssl, or null">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_from_address">Mail From Address</label>
                            <input name="mail_from_address" type="email" value="{{ $settings['mail_from_address'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="mail_from_name">Mail From Name</label>
                            <input name="mail_from_name" type="text" value="{{ $settings['mail_from_name'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-2.5 bg-[#C41E3A] text-white font-medium text-sm leading-tight uppercase rounded-lg shadow-md hover:bg-[#a01830] hover:shadow-lg focus:bg-[#a01830] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-[#801326] active:shadow-lg transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Test Connection Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full mb-8 lg:mb-0">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Test Connection</h4>
                <p class="text-sm text-gray-500 mb-4">Send a test email to verify your SMTP configuration.</p>
                
                <form action="{{ route('admin.settings.smtp.test') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                         <label class="block text-sm font-medium text-gray-700 mb-2" for="test_email">Target Email</label>
                         <input name="test_email" type="email" value="{{ auth()->user()->email ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="Enter email address" required>
                    </div>

                    <button type="submit" class="w-full px-6 py-2.5 bg-gray-800 text-white font-medium text-sm leading-tight uppercase rounded-lg shadow-md hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Test Email
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
