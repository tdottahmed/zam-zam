@extends('layouts.admin')

@section('header')
    Add Tax
@endsection

@section('content')
    <div class="mx-auto">
        <div class="mb-6">
            <x-admin.actions.button href="{{ route('admin.taxes.index') }}" variant="secondary" size="sm">
                &larr; Back to Taxes
            </x-admin.actions.button>
        </div>

        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.taxes.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <x-admin.form.label for="name" value="Tax Name" />
                        <x-admin.form.input id="name" name="name" :value="old('name')" required autofocus placeholder="e.g. VAT" />
                        <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Value -->
                    <div>
                        <x-admin.form.label for="value" value="Percentage (%)" />
                        <x-admin.form.input id="value" name="value" type="number" step="0.01" :value="old('value')" required placeholder="e.g. 15.00" />
                        <x-admin.form.input-error :messages="$errors->get('value')" class="mt-2" />
                    </div>

                    <!-- Is Active -->
                    <div>
                        <input type="hidden" name="is_active" value="0">
                        <x-admin.form.checkbox id="is_active" name="is_active" value="1" label="Active" checked />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-admin.actions.button type="submit" variant="primary">
                            Create Tax
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
