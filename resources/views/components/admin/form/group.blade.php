@props([
    'label',
    'for',
    'error' => false,
    'help' => false,
    'required' => false
])

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if($label)
        <x-admin.form.label :for="$for" :value="$label" :required="$required" />
    @endif

    <div class="mt-1">
        {{ $slot }}
    </div>

    @if($help)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif

    @error($error ? $error : $for)
        <x-admin.form.input-error :message="$message" class="mt-1" />
    @enderror
</div>
