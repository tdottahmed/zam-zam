@props(['disabled' => false, 'error' => null])

@php
$classes = 'w-full rounded-md shadow-sm border-gray-300 focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A]/20 transition duration-200 placeholder-gray-400 text-sm';

if ($error) {
    $classes .= ' border-red-500 focus:border-red-500 focus:ring-red-500/20';
}
@endphp

<div class="relative">
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
    
    @if($error)
        <p class="mt-1 text-xs text-red-500">{{ $error }}</p>
    @endif
</div>
