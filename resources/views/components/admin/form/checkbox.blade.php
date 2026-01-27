@props(['disabled' => false, 'label' => null])

<label class="inline-flex items-center cursor-pointer">
    <input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded border-gray-300 text-[#C41E3A] shadow-sm focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A]/20 transition duration-200 ease-in-out']) !!}>
    
    @if($label)
        <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
    @endif
</label>
