@extends('layouts.admin')

@section('header')
    Edit Brand
@endsection

@section('content')
    <div class="mx-auto">
        <div class="mb-6">
            <x-admin.actions.button href="{{ route('admin.brands.index') }}" variant="secondary" size="sm">
                &larr; Back to Brands
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6" x-data="{ name: '{{ old('name', $brand->name) }}', slug: '{{ old('slug', $brand->slug) }}' }">
                    <!-- Name -->
                    <div>
                        <x-admin.form.label for="name" value="Brand Name" />
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
                         <x-admin.form.file-upload name="image" label="Brand Image (Optional)" :preview="$brand->image ? asset('storage/' . $brand->image) : null" />
                         <x-admin.form.input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-admin.actions.button type="submit" variant="primary">
                            Update Brand
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
