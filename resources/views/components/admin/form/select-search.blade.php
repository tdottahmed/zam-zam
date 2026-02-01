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
    },
    toggle() {
        if (this.open) {
            this.open = false;
        } else {
            this.open = true;
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
        }
    }
}" @click.away="open = false" class="relative w-full">

    @if($label)
        <label class="block font-medium text-sm text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="selected">

    <button @click="toggle()" type="button"
        class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2.5 text-left cursor-pointer focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 sm:text-sm transition-all duration-200 ease-in-out hover:border-red-300"
        :class="{'ring-1 ring-red-500 border-red-500': open}">
        <span class="block truncate" :class="{ 'text-gray-500': !selected, 'text-gray-900': selected }" x-text="selectedLabel"></span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
            <svg class="h-5 w-5 transition-transform duration-200" :class="{'rotate-180': open}" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95 translate-y-[-10px]"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-95 translate-y-[-10px]"
         class="absolute z-50 mt-1 w-full bg-white shadow-xl max-h-60 rounded-md py-0 text-base ring-1 ring-black ring-opacity-5 overflow-hidden focus:outline-none sm:text-sm border border-gray-100"
         style="display: none;">
        
        <div class="p-2 sticky top-0 bg-white border-b border-red-100 z-10 backdrop-blur-sm bg-opacity-95">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-red-400" fill="none" class="h-4 w-4" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input x-ref="searchInput" x-model="search" type="text" placeholder="Search..." 
                    class="block w-full pl-10 pr-3 py-2 border border-red-100 rounded-md leading-5 bg-red-50 placeholder-red-300 text-red-900 focus:outline-none focus:bg-white focus:ring-1 focus:ring-red-500 focus:border-red-500 sm:text-sm transition duration-150 ease-in-out">
            </div>
        </div>

        <div class="max-h-52 overflow-auto custom-scrollbar">
            <template x-for="(label, value) in filteredOptions" :key="value">
                <div @click="select(value)"
                    class="cursor-pointer select-none relative py-2.5 pl-3 pr-9 border-b border-gray-50 last:border-b-0 hover:bg-red-50 transition-colors duration-150 group"
                    :class="{ 'bg-red-50 text-red-900': selected == value, 'text-gray-700': selected != value }">
                    <span class="block truncate group-hover:text-red-700" :class="{ 'font-semibold': selected == value, 'font-normal': selected != value }" x-text="label"></span>
                    
                    <span x-show="selected == value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-red-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </template>
            
            <div x-show="Object.keys(filteredOptions).length === 0" class="px-3 py-4 text-center text-gray-500 text-sm italic">
                No found.
            </div>
        </div>
    </div>
</div>
