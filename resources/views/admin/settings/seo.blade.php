@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">SEO Settings</h3>

    <div class="mt-8">
        <form action="{{ route('admin.settings.seo.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Meta Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
                        <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                             <div class="p-2 bg-purple-50 rounded-lg mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Search Engine Optimization</h4>
                                <p class="text-sm text-gray-500">Configure default meta tags for your site</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="meta_title">Meta Title</label>
                                <input name="meta_title" type="text" value="{{ $settings['meta_title'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="My Awesome Shop">
                                <p class="mt-1 text-xs text-gray-500">The default title that appears in search engine results.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="meta_description">Meta Description</label>
                                <textarea name="meta_description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="A brief description of your shop...">{{ $settings['meta_description'] ?? '' }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">A short summary of your page content (recommended: 150-160 characters).</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="meta_keywords">Meta Keywords</label>
                                <input name="meta_keywords" type="text" value="{{ $settings['meta_keywords'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm" placeholder="keyword1, keyword2, keyword3">
                                <p class="mt-1 text-xs text-gray-500">Comma-separated keywords relevant to your site.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Webmaster Tools & Sitemap -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Google Search Console -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                         <div class="flex items-center mb-4 pb-2 border-b border-gray-100">
                             <div class="p-2 bg-orange-50 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-base font-semibold text-gray-800">Webmaster Tools</h4>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="google_verification_code">Google Verification Code</label>
                            <input name="google_verification_code" type="text" value="{{ $settings['google_verification_code'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-sm" placeholder="google-site-verification=...">
                            <p class="mt-1 text-xs text-gray-500">Enter the content of the verification meta tag.</p>
                        </div>
                    </div>

                    <!-- Sitemap & RSS -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                         <div class="flex items-center mb-4 pb-2 border-b border-gray-100">
                             <div class="p-2 bg-blue-50 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </div>
                            <h4 class="text-base font-semibold text-gray-800">Sitemap & RSS</h4>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700">Sitemap XML</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                     <a href="{{ route('sitemap') }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                        View
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <form action="{{ route('admin.settings.sitemap.generate') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded transition">Generate Static Sitemap</button>
                                </form>
                            </div>

                             <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700">RSS Feed</span>
                                </div>
                                <a href="/feed" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                    View
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                         <p class="mt-3 text-xs text-gray-500">These are automatically generated based on your content.</p>
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
