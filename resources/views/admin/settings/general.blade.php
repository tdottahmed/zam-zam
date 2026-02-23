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
                            <div class="sm:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-admin.form.file-upload name="site_logo" label="Site Logo" :preview="isset($settings['site_logo']) ? Storage::url($settings['site_logo']) : null" />
                                </div>
                                <div>
                                    <x-admin.form.file-upload name="site_favicon" label="Favicon" :preview="isset($settings['site_favicon']) ? Storage::url($settings['site_favicon']) : null" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="site_name">Site Name</label>
                                <input name="site_name" type="text" value="{{ $settings['site_name'] ?? 'ZamZam Import and Export Inc.' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="contact_email">Contact Email</label>
                                <input name="contact_email" type="email" value="{{ $settings['contact_email'] ?? 'zamzamimport2023@gmail.com' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="contact_phone">Contact Phone</label>
                                <input name="contact_phone" type="text" value="{{ $settings['contact_phone'] ?? '+1 416-283-4488' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="contact_cell">Contact Cell</label>
                                <input name="contact_cell" type="text" value="{{ $settings['contact_cell'] ?? '+1 647-482-1133' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="tax_id">Tax ID</label>
                                <input name="tax_id" type="text" value="{{ $settings['tax_id'] ?? '731247144RT0001' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>
                            
                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="currency_symbol">Currency Symbol</label>
                                <input name="currency_symbol" type="text" value="{{ $settings['currency_symbol'] ?? '$' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="address">Address</label>
                                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">{{ $settings['address'] ?? "1-283 Morningside Ave\nScarborough, Ontario, M1E 3G1\nCanada" }}</textarea>
                            </div>

                            <div class="sm:col-span-2 mt-4 pt-4 border-t border-gray-100">
                                <h5 class="text-sm font-semibold text-gray-700 mb-3">Alternate Address & Emails</h5>
                                <p class="text-xs text-gray-500 mb-3">Secondary location and contact emails (e.g. for documents or correspondence).</p>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2" for="address_alt">Alternate Address</label>
                                        <textarea name="address_alt" id="address_alt" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="500 Coronation Drive, Unit-14&#10;Scarborough Ontario-M1E4V7">{{ $settings['address_alt'] ?? "500 Coronation Drive, Unit-14\nScarborough Ontario-M1E4V7" }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2" for="contact_email_alt_1">Alternate Email 1</label>
                                            <input name="contact_email_alt_1" id="contact_email_alt_1" type="email" value="{{ $settings['contact_email_alt_1'] ?? 'zamzamimport2023@gmail.com' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="zamzamimport2023@gmail.com">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2" for="contact_email_alt_2">Alternate Email 2</label>
                                            <input name="contact_email_alt_2" id="contact_email_alt_2" type="email" value="{{ $settings['contact_email_alt_2'] ?? 'zamzamcanada23@gmail.com' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="zamzamcanada23@gmail.com">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Rules Card -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Business Rules -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
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

                    <!-- Social Media Links -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-1 pb-2 border-b border-gray-100">Social Media Links</h4>
                        <p class="text-xs text-gray-400 mb-5">These appear as clickable icons in the website footer.</p>
                        <div class="space-y-4">

                            {{-- Facebook --}}
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center bg-[#1877F2]/10">
                                    <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Facebook</label>
                                    <input name="social_facebook" type="url" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/yourpage" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                            </div>

                            {{-- Instagram --}}
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center bg-[#E1306C]/10">
                                    <svg class="w-5 h-5 text-[#E1306C]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Instagram</label>
                                    <input name="social_instagram" type="url" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/yourhandle" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                            </div>

                            {{-- LinkedIn --}}
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center bg-[#0A66C2]/10">
                                    <svg class="w-5 h-5 text-[#0A66C2]" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">LinkedIn</label>
                                    <input name="social_linkedin" type="url" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/yourcompany" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                            </div>

                            {{-- Twitter / X --}}
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center bg-gray-900/10">
                                    <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Twitter / X</label>
                                    <input name="social_twitter" type="url" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://x.com/yourhandle" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-sm">
                                </div>
                            </div>

                            {{-- YouTube --}}
                            <div class="flex items-center gap-3">
                                <span class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center bg-[#FF0000]/10">
                                    <svg class="w-5 h-5 text-[#FF0000]" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">YouTube</label>
                                    <input name="social_youtube" type="url" value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/@yourchannel" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-sm">
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
