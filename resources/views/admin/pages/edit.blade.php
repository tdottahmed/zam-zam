@extends('layouts.admin')

@section('header')
    Edit Page
@endsection

@section('content')
    <div class="mx-auto max-w-8xl">
        <div class="mb-6 flex items-center gap-3">
            <x-admin.actions.button href="{{ route('admin.pages.index') }}" variant="secondary" size="sm">
                &larr; Back to Pages
            </x-admin.actions.button>
            <x-admin.actions.button href="{{ route('admin.pages.show', $page) }}" variant="secondary" size="sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Preview
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.pages.update', $page) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6" x-data="{
                    title: '{{ old('title', $page->title) }}',
                    slug: '{{ old('slug', $page->slug) }}',
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
                                class="pl-6"
                                required
                            />
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">Changing the slug may break existing links to this page.</p>
                        <x-admin.form.input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    {{-- Content --}}
                    <div>
                        <x-admin.form.label for="content" value="Page Content" />
                        <x-admin.form.editor
                            name="content"
                            :value="old('content', $page->content ?? '')"
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
                            Update Page
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
