@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">About Us Settings</h3>
    </div>

    <div class="mt-4">
        <form action="{{ route('admin.settings.about-us.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-6">
                    <!-- Our Story Content -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Our Story Content</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_heading">Heading</label>
                                <input name="about_us_heading" type="text" value="{{ $settings['about_us_heading'] ?? 'Our Story' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_description_1">Paragraph 1</label>
                                <textarea name="about_us_description_1" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">{{ $settings['about_us_description_1'] ?? '' }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_description_2">Paragraph 2</label>
                                <textarea name="about_us_description_2" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">{{ $settings['about_us_description_2'] ?? '' }}</textarea>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_description_3">Paragraph 3</label>
                                <textarea name="about_us_description_3" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">{{ $settings['about_us_description_3'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Highlight Features -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Highlight Features</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            
                            <!-- Feature 1 -->
                            <div class="sm:col-span-1 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_feature_1_title">Feature 1 Title</label>
                                    <input name="about_us_feature_1_title" type="text" value="{{ $settings['about_us_feature_1_title'] ?? 'Authenticity' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_feature_1_desc">Feature 1 Description</label>
                                    <textarea name="about_us_feature_1_desc" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">{{ $settings['about_us_feature_1_desc'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="sm:col-span-1 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_feature_2_title">Feature 2 Title</label>
                                    <input name="about_us_feature_2_title" type="text" value="{{ $settings['about_us_feature_2_title'] ?? 'Reliability' }}" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_feature_2_desc">Feature 2 Description</label>
                                    <textarea name="about_us_feature_2_desc" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm">{{ $settings['about_us_feature_2_desc'] ?? '' }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <!-- Image & Badge Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Image & Badge</h4>
                        
                        <div class="mb-6">
                            <x-admin.form.file-upload name="about_us_image" label="Main Image" :preview="isset($settings['about_us_image']) ? Storage::url($settings['about_us_image']) : null" />
                            <p class="text-xs text-gray-500 mt-2">Upload a high-quality showcase image for the left side of the section.</p>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2">Floating Badge Overlay</h5>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_badge_text">Badge Main Text</label>
                                <input name="about_us_badge_text" type="text" value="{{ $settings['about_us_badge_text'] ?? '15+' }}" placeholder="e.g. 15+" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-center font-bold text-xl text-[#C41E3A]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="about_us_badge_subtext">Badge Subtext</label>
                                <input name="about_us_badge_subtext" type="text" value="{{ $settings['about_us_badge_subtext'] ?? 'YEARS OF EXCELLENCE' }}" placeholder="e.g. YEARS OF EXCELLENCE" class="w-full rounded-lg border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 transition shadow-sm text-center text-xs tracking-wider uppercase">
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
