@props(['name', 'label' => 'Upload Image', 'preview' => null])

<div x-data="{
    previewUrl: '{{ $preview }}',
    isDragging: false,
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewFile(file);
        }
    },
    handleDrop(event) {
        this.isDragging = false;
        const file = event.dataTransfer.files[0];
        if (file) {
            this.$refs.input.files = event.dataTransfer.files;
            this.previewFile(file);
        }
    },
    previewFile(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            this.previewUrl = e.target.result;
        };
        reader.readAsDataURL(file);
    },
    removeImage() {
        this.previewUrl = null;
        this.$refs.input.value = '';
    }
}" class="w-full">
    
    @if($label)
        <label class="block font-medium text-sm text-gray-700 mb-2">{{ $label }}</label>
    @endif

    <div 
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="handleDrop($event)"
        class="relative border-2 border-dashed rounded-lg p-6 flex flex-col items-center justify-center transition-colors duration-200"
        :class="{ 'border-[#C41E3A] bg-red-50': isDragging, 'border-gray-300 hover:border-[#C41E3A]': !isDragging }"
    >
        <input 
            type="file" 
            name="{{ $name }}" 
            x-ref="input"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            @change="handleFileSelect($event)"
            accept="image/*"
        >

        <!-- Placeholder State -->
        <div x-show="!previewUrl" class="text-center pointer-events-none">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <p class="mt-1 text-sm text-gray-600">
                <span class="font-medium text-[#C41E3A]">Upload a file</span> or drag and drop
            </p>
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
        </div>

        <!-- Preview State -->
        <div x-show="previewUrl" class="relative group w-full h-48 bg-gray-100 rounded-md overflow-hidden flex items-center justify-center" style="display: none;">
            <img :src="previewUrl" class="h-full w-auto object-contain">
            
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                 <button type="button" @click.prevent="removeImage" class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
