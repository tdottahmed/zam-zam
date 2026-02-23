@extends('layouts.admin')

@section('header')
    Add Page
@endsection

@section('content')
    <div class="mx-auto max-w-8xl">
        <div class="mb-6">
            <x-admin.actions.button href="{{ route('admin.pages.index') }}" variant="secondary" size="sm">
                &larr; Back to Pages
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.pages.store') }}">
                @csrf

                <div class="space-y-6" x-data="{
                    title: '{{ old('title') }}',
                    slug: '{{ old('slug') }}',
                    generateSlug() {
                        this.slug = this.title
                            .toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .trim()
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                    }
                }">
                    {{-- Page Title --}}
                    <div>
                        <x-admin.form.label for="title" value="Page Title" />
                        <x-admin.form.input
                            id="title"
                            name="title"
                            x-model="title"
                            @input="generateSlug()"
                            placeholder="e.g. About Us"
                            required
                        />
                        <x-admin.form.input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- Slug --}}
                    <div>
                        <x-admin.form.label for="slug" value="URL Slug" />
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm select-none pointer-events-none">/</span>
                            <x-admin.form.input
                                id="slug"
                                name="slug"
                                x-model="slug"
                                placeholder="about-us"
                                class="pl-6"
                                required
                            />
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Auto-generated from the title. You can edit it manually.</p>
                        <x-admin.form.input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    {{-- Content --}}
                    <div>
                        <x-admin.form.label for="content" value="Page Content" />
                        <x-admin.form.editor
                            name="content"
                            :value="old('content', '')"
                            height="h-72"
                        />
                        <x-admin.form.input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <x-admin.actions.button href="{{ route('admin.pages.index') }}" variant="secondary">
                            Cancel
                        </x-admin.actions.button>
                        <x-admin.actions.button type="submit" variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Create Page
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
