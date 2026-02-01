@extends('layouts.admin')

@section('header')
    Edit Tax
@endsection

@section('content')
    <div class="mx-auto">
        <div class="mb-6">
            <x-admin.actions.button href="{{ route('admin.taxes.index') }}" variant="secondary" size="sm">
                &larr; Back to Taxes
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.taxes.update', $tax) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <x-admin.form.label for="name" value="Tax Name" />
                        <x-admin.form.input id="name" name="name" :value="old('name', $tax->name)" required autofocus />
                        <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Value -->
                    <div>
                        <x-admin.form.label for="value" value="Percentage (%)" />
                        <x-admin.form.input id="value" name="value" type="number" step="0.01" :value="old('value', $tax->value)" required />
                        <x-admin.form.input-error :messages="$errors->get('value')" class="mt-2" />
                    </div>

                     <!-- Is Active -->
                     <div>
                         <input type="hidden" name="is_active" value="0">
                        <x-admin.form.checkbox id="is_active" name="is_active" value="1" label="Active" :checked="old('is_active', $tax->is_active)" />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-admin.actions.button type="submit" variant="primary">
                            Update Tax
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
