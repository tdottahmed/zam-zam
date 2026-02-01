@extends('layouts.admin')

@section('header')
    Edit Unit
@endsection

@section('content')
    <div class="mb-6">
        <x-admin.actions.button href="{{ route('admin.units.index') }}" variant="secondary" size="sm">
            &larr; Back to Units
        </x-admin.actions.button>
    </div>
    <x-admin.ui.card>
        <form method="POST" action="{{ route('admin.units.update', $unit) }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <x-admin.form.label for="name" value="Unit Name" />
                    <x-admin.form.input id="name" name="name" :value="old('name', $unit->name)" required autofocus />
                    <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Code -->
                <div>
                    <x-admin.form.label for="code" value="Unit Code" />
                    <x-admin.form.input id="code" name="code" :value="old('code', $unit->code)" required />
                    <x-admin.form.input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <!-- Status -->
                <div>
                     <label for="is_active" class="inline-flex items-center">
                        <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-8">
                    <x-admin.actions.button type="submit" variant="primary">
                        Update Unit
                    </x-admin.actions.button>
                </div>
            </div>
        </form>
    </x-admin.ui.card>
@endsection
