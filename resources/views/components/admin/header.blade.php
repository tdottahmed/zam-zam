        <!-- Header -->
        <header class="h-20 bg-white/70 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-8 z-20 sticky top-0">
            <!-- Left: Toggle & Title -->
            <div class="flex items-center space-x-6">
                 <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-[#C41E3A] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                 </button>
                 <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                    {{ $slot }}
                 </h2>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center space-x-6">
                <!-- Notifications -->
                <button class="relative text-gray-400 hover:text-gray-600">
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-[#C41E3A] rounded-full border-2 border-white"></span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>

                 <!-- User Dropdown -->
                 <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-[#C41E3A] transition focus:outline-none">
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open" 
                        @click.away="open = false" 
                        class="absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-50 transform transition-all duration-200"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        style="display: none;"
                    >
                         <div class="px-4 py-3 border-b border-gray-100">
                             <p class="text-sm text-gray-500">Signed in as</p>
                             <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                         </div>
                         <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#C41E3A]">User View</a>
                         <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">Log Out</button>
                        </form>
                    </div>
                 </div>
            </div>
        </header>
