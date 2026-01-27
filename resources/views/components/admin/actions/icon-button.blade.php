@props([
    'variant' => 'ghost', // primary, secondary, danger, ghost
    'size' => 'md',       // sm, md, lg
    'type' => 'button',
    'href' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary' => 'bg-[#C41E3A] text-white hover:bg-[#A01830] focus:ring-[#C41E3A]',
    'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-[#C41E3A]',
    'danger' => 'bg-red-100 text-red-600 hover:bg-red-200 focus:ring-red-500',
    'ghost' => 'bg-transparent text-gray-500 hover:text-[#C41E3A] hover:bg-[#C41E3A]/10 focus:ring-[#C41E3A]',
];

$sizes = [
    'sm' => 'p-1',
    'md' => 'p-2',
    'lg' => 'p-3',
];

$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['ghost']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
