@extends('layouts.admin')

@section('header')
    Add Unit
@endsection

@section('content')
    <div class="mb-6">
        <x-admin.actions.button href="{{ route('admin.units.index') }}" variant="secondary" size="sm">
            &larr; Back to Units
        </x-admin.actions.button>
    </div>
    <x-admin.ui.card>
        <form method="POST" action="{{ route('admin.units.store') }}">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <x-admin.form.label for="name" value="Unit Name" />
                    <x-admin.form.input id="name" name="name" :value="old('name')" required autofocus placeholder="e.g. Kilogram" />
                    <x-admin.form.input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Code -->
                <div>
                    <x-admin.form.label for="code" value="Unit Code" />
                    <x-admin.form.input id="code" name="code" :value="old('code')" required placeholder="e.g. kg" />
                    <x-admin.form.input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <!-- Type -->
                <div>
                    <x-admin.form.label for="type" value="Unit Type" />
                    <p class="text-xs text-gray-500 mb-2">Determines where this unit appears in product forms.</p>
                    <div class="grid grid-cols-3 gap-3 mt-1" x-data="{ selected: '{{ old('type', 'both') }}' }">
                        @foreach(['weight' => ['label' => 'Weight', 'icon' => '⚖️', 'desc' => 'GM, ML, KG, etc.'], 'stock' => ['label' => 'Stock', 'icon' => '📦', 'desc' => 'Piece, Dozen, Box, etc.'], 'both' => ['label' => 'Both', 'icon' => '🔀', 'desc' => 'Appears in all unit fields']] as $value => $info)
                            <label
                                class="relative flex flex-col items-center justify-center gap-1 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 text-center select-none"
                                :class="selected === '{{ $value }}' ? 'border-[#C41E3A] bg-red-50 text-[#C41E3A]' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'"
                            >
                                <input type="radio" name="type" value="{{ $value }}" x-model="selected" class="sr-only" />
                                <span class="text-2xl">{{ $info['icon'] }}</span>
                                <span class="text-sm font-semibold">{{ $info['label'] }}</span>
                                <span class="text-xs opacity-70">{{ $info['desc'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-admin.form.input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <!-- Status -->
                <div>
                    <label for="is_active" class="inline-flex items-center cursor-pointer">
                        <input id="is_active" type="checkbox" class="rounded border-gray-300 text-[#C41E3A] shadow-sm focus:ring-[#C41E3A]" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">Active</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-8">
                    <x-admin.actions.button type="submit" variant="primary">
                        Create Unit
                    </x-admin.actions.button>
                </div>
            </div>
        </form>
    </x-admin.ui.card>
@endsection
