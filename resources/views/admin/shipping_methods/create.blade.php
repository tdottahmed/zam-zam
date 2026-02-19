@extends('layouts.admin')

@section('header')
    Add Shipping Method
@endsection

@section('content')
    <div class="mb-6">
        <x-admin.actions.button href="{{ route('admin.shipping-methods.index') }}" variant="secondary" size="sm">
            &larr; Back to List
        </x-admin.actions.button>
    </div>

    <x-admin.ui.card>
        <form method="POST" action="{{ route('admin.shipping-methods.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <x-admin.form.label for="name" value="Method Name" />
                    <x-admin.form.input id="name" name="name" :value="old('name')" required placeholder="e.g. Standard Delivery" />
                    <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Cost -->
                <div>
                    <x-admin.form.label for="cost" value="Cost (Optional)" />
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <x-admin.form.input id="cost" name="cost" type="number" step="0.01" :value="old('cost')" class="pl-7" placeholder="Leave empty for calculated" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Leave empty if calculated at checkout.</p>
                    <x-admin.form.input-error :messages="$errors->get('cost')" class="mt-2" />
                </div>

                <!-- Estimate -->
                <div>
                    <x-admin.form.label for="estimated_delivery_time" value="Estimated Time" />
                    <x-admin.form.input id="estimated_delivery_time" name="estimated_delivery_time" :value="old('estimated_delivery_time')" placeholder="e.g. 3-5 Business Days" />
                    <x-admin.form.input-error :messages="$errors->get('estimated_delivery_time')" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <x-admin.form.label for="description" value="Description" />
                    <textarea id="description" name="description" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50 transition-colors duration-200">{{ old('description') }}</textarea>
                    <x-admin.form.input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                     <label class="inline-flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-[#C41E3A] shadow-sm focus:ring-[#C41E3A]" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-100">
                <x-admin.actions.button type="submit" variant="primary">
                    Create Shipping Method
                </x-admin.actions.button>
            </div>
        </form>
    </x-admin.ui.card>
@endsection
