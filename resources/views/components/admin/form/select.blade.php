@props(['disabled' => false, 'error' => null, 'options' => [], 'placeholder' => 'Select an option'])

@php
$classes = 'w-full rounded-md shadow-sm border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A]/20 transition duration-200 text-sm';

if ($error) {
    $classes .= ' border-red-500 focus:border-red-500 focus:ring-red-500/20';
}
@endphp

<div class="relative">
    <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
        @if($placeholder)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif
        
        {{ $slot }}
        
        @foreach($options as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @endforeach
    </select>
    
    @if($error)
        <p class="mt-1 text-xs text-red-500">{{ $error }}</p>
    @endif
</div>
