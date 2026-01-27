@extends('layouts.admin')

@section('header')
    Add Category
@endsection

@section('content')
    <div class="mx-auto">
        <div class="mb-6">
            <x-admin.actions.button href="{{ route('admin.categories.index') }}" variant="secondary" size="sm">
                &larr; Back to Categories
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6" x-data="{ name: '{{ old('name') }}', slug: '{{ old('slug') }}' }">
                    <!-- Name -->
                    <div>
                        <x-admin.form.label for="name" value="Category Name" />
                        <x-admin.form.input id="name" name="name" x-model="name" @input="slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')" required />
                        <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Slug -->
                    <div>
                        <x-admin.form.label for="slug" value="Slug" />
                        <x-admin.form.input id="slug" name="slug" x-model="slug" required />
                        <x-admin.form.input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <!-- Image -->
                    <div>
                         <x-admin.form.file-upload name="image" label="Category Image (Optional)" />
                         <x-admin.form.input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-admin.actions.button type="submit" variant="primary">
                            Create Category
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
