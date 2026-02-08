<div
    x-data="{
        show: false,
        type: '',
        message: '',
        timeout: null,
        init() {
            @if (session('success'))
                this.notify('success', '{{ session('success') }}');
            @elseif (session('error'))
                this.notify('error', '{{ session('error') }}');
            @elseif (session('warning'))
                this.notify('warning', '{{ session('warning') }}');
            @elseif (session('info'))
                this.notify('info', '{{ session('info') }}');
            @elseif ($errors->any())
                this.notify('error', 'Please check the form for errors.');
            @endif
        },
        notify(type, message) {
            this.type = type;
            this.message = message;
            this.show = true;
            if (this.timeout) clearTimeout(this.timeout);
            this.timeout = setTimeout(() => { this.show = false }, 5000);
        },
        close() {
            this.show = false;
            if (this.timeout) clearTimeout(this.timeout);
        }
    }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;"
    class="fixed top-5 right-5 z-50 flex w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg border border-gray-100"
>
    <div class="flex-shrink-0 flex items-center justify-center w-12"
         :class="{
            'bg-green-500': type === 'success',
            'bg-red-500': type === 'error',
            'bg-yellow-500': type === 'warning',
            'bg-blue-500': type === 'info'
         }">
        
        <!-- Success Icon -->
        <svg x-show="type === 'success'" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>

        <!-- Error Icon -->
        <svg x-show="type === 'error'" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>

        <!-- Warning Icon -->
        <svg x-show="type === 'warning'" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>

        <!-- Info Icon -->
        <svg x-show="type === 'info'" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>

    <div class="px-4 py-3 -mx-3">
        <div class="mx-3">
            <span class="font-semibold" 
                  :class="{
                    'text-green-500': type === 'success',
                    'text-red-500': type === 'error',
                    'text-yellow-500': type === 'warning',
                    'text-blue-500': type === 'info'
                  }" x-text="type.charAt(0).toUpperCase() + type.slice(1)"></span>
            <p class="text-sm text-gray-600" x-text="message"></p>
        </div>
    </div>
    
    <!-- Close Button -->
    <div class="absolute top-2 right-2 cursor-pointer" @click="close">
         <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
         </svg>
    </div>
</div>
