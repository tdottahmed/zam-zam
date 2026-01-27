@props(['enabled' => false, 'label' => null])

<div x-data="{ on: {{ $enabled ? 'true' : 'false' }} }" class="flex items-center">
    <!-- Hidden Input for Form Submission -->
    <input type="hidden" name="{{ $attributes->get('name') }}" :value="on ? 1 : 0">

    <button 
        type="button" 
        @click="on = !on"
        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A]"
        :class="{ 'bg-[#C41E3A]': on, 'bg-gray-200': !on }"
        role="switch" 
        aria-checked="false"
        {!! $attributes->except(['name', 'value']) !!}
    >
        <span 
            aria-hidden="true" 
            class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
            :class="{ 'translate-x-5': on, 'translate-x-0': !on }"
        ></span>
    </button>
    
    @if($label)
        <span class="ml-3 text-sm text-gray-600 cursor-pointer" @click="on = !on">{{ $label }}</span>
    @endif
</div>
