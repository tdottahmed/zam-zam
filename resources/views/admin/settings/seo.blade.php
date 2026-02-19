@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">SEO Settings</h3>

    <div class="mt-8">
        <form action="{{ route('admin.settings.seo.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 bg-white rounded-md shadow-md">
                <div class="grid grid-cols-1 gap-6 mt-4">
                    <div>
                        <label class="text-gray-700" for="meta_title">Meta Title</label>
                        <input name="meta_title" type="text" value="{{ $settings['meta_title'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">
                    </div>

                    <div>
                        <label class="text-gray-700" for="meta_description">Meta Description</label>
                        <textarea name="meta_description" rows="4" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50">{{ $settings['meta_description'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="text-gray-700" for="meta_keywords">Meta Keywords</label>
                        <input name="meta_keywords" type="text" value="{{ $settings['meta_keywords'] ?? '' }}" class="w-full mt-2 border-gray-200 rounded-md focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50" placeholder="keyword1, keyword2, keyword3">
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
