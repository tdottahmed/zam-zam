@props(['options' => [], 'name' => '', 'label' => '', 'placeholder' => 'Select an option', 'selected' => null])

<div x-data="{
    open: false,
    search: '',
    selected: {{ json_encode($selected) }},
    options: {{ json_encode($options) }},
    get filteredOptions() {
        if (this.search === '') {
            return this.options;
        }
        return Object.keys(this.options).reduce((acc, key) => {
            if (this.options[key].toLowerCase().includes(this.search.toLowerCase())) {
                acc[key] = this.options[key];
            }
            return acc;
        }, {});
    },
    get selectedLabel() {
        return this.options[this.selected] || {{ json_encode($placeholder) }};
    },
    select(value) {
        this.selected = value;
        this.open = false;
        this.search = '';
    }
}" @click.away="open = false" class="relative w-full">

    @if($label)
        <label class="block font-medium text-sm text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="selected">

    <button @click="open = !open" type="button"
        class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-[#C41E3A] focus:border-[#C41E3A] sm:text-sm">
        <span class="block truncate" x-text="selectedLabel"></span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
         style="display: none;">
        
        <div class="px-2 py-1 sticky top-0 bg-white border-b border-gray-100">
            <input x-model="search" type="text" placeholder="Search..." 
                class="w-full border-gray-300 rounded-md text-sm focus:ring-[#C41E3A] focus:border-[#C41E3A] px-2 py-1">
        </div>

        <template x-for="(label, value) in filteredOptions" :key="value">
            <div @click="select(value)"
                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50 hover:text-[#C41E3A]"
                :class="{ 'text-[#C41E3A] bg-red-50': selected == value, 'text-gray-900': selected != value }">
                <span class="block truncate" :class="{ 'font-semibold': selected == value, 'font-normal': selected != value }" x-text="label"></span>
                
                <span x-show="selected == value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#C41E3A]">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </template>
        
        <div x-show="Object.keys(filteredOptions).length === 0" class="px-3 py-2 text-gray-500 text-sm">
            No results found.
        </div>
    </div>
</div>
