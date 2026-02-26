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
                <x-admin.notifications />

                 <!-- User Dropdown -->
                 <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 p-1.5 pr-3 rounded-full hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-opacity-30 group" aria-expanded="open" aria-haspopup="true">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#C41E3A] to-red-500 flex items-center justify-center text-white text-sm font-bold shadow-sm group-hover:shadow transition-all">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden md:flex flex-col items-start pr-1">
                            <span class="text-sm font-bold text-gray-800 group-hover:text-[#C41E3A] transition-colors leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-[11px] text-gray-500 font-medium uppercase tracking-wide">Administrator</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#C41E3A] transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        x-cloak
                        x-show="open" 
                        @click.away="open = false" 
                        class="absolute right-0 mt-3 w-64 bg-white/95 backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-gray-100 p-2 z-50 transform origin-top-right ring-1 ring-black/5"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                        style="display: none;"
                    >
                         <!-- User Info Profile Section -->
                         <div class="px-3 py-3 border-b border-gray-100 mb-1 rounded-xl bg-gray-50/80 flex items-center space-x-3">
                             <div class="w-10 h-10 min-w-[40px] rounded-full bg-gradient-to-br from-[#C41E3A] to-red-500 flex items-center justify-center text-white text-lg font-bold shadow-inner">
                                 {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                             </div>
                             <div class="overflow-hidden">
                                 <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                 <p class="text-xs font-medium text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                             </div>
                         </div>

                         <!-- Links -->
                         <div class="space-y-1 py-1">
                             <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 hover:text-[#C41E3A] transition-all duration-200">
                                 <div class="w-8 h-8 rounded-lg bg-gray-100/80 group-hover:bg-red-50 flex items-center justify-center mr-3 transition-colors">
                                     <svg class="w-4 h-4 text-gray-500 group-hover:text-[#C41E3A] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                 </div>
                                 User View
                             </a>
                         </div>

                         <!-- Logout -->
                         <div class="mt-1 pt-1 border-t border-gray-100">
                             <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full group flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-xl hover:bg-red-50 hover:text-red-700 transition-all duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-red-50 group-hover:bg-red-100 flex items-center justify-center mr-3 transition-colors">
                                        <svg class="w-4 h-4 text-red-500 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    </div>
                                    Log Out
                                </button>
                            </form>
                         </div>
                    </div>
                 </div>
            </div>
        </header>
