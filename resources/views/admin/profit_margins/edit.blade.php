@extends('layouts.admin')

@section('header')
    Profit Margin Settings
@endsection

@section('content')
    <div class="mx-auto">
        <x-admin.ui.card>
            <form method="POST" action="{{ route('admin.profit-margin.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Value -->
                    <div>
                        <x-admin.form.label for="value" value="Default Profit Margin (%)" />
                        <x-admin.form.input id="value" name="value" type="number" step="0.01" :value="old('value', $profitMargin->value)" required />
                        <p class="mt-1 text-sm text-gray-500">This percentage will be applied as the default profit margin.</p>
                        <x-admin.form.input-error :messages="$errors->get('value')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-admin.actions.button type="submit" variant="primary">
                            Save Changes
                        </x-admin.actions.button>
                    </div>
                </div>
            </form>
        </x-admin.ui.card>
    </div>
@endsection
