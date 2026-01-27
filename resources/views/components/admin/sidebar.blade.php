    <!-- Sidebar -->
    <aside 
        class="bg-[#1A1A1A] text-white flex-shrink-0 transition-all duration-300 ease-in-out flex flex-col z-30"
        :class="sidebarOpen ? 'w-72' : 'w-20'"
    >
        <!-- Logo Area -->
        <div class="h-20 flex items-center justify-center border-b border-gray-800 relative bg-[#C41E3A]">
             <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 overflow-hidden">
                <!-- Icon -->
                <svg class="w-8 h-8 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                
                <span class="text-xl font-bold tracking-wider whitespace-nowrap" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Super Asia</span>
             </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-2">
            
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-lg group transition-colors relative {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Dashboard</span>
                
                @if(request()->routeIs('admin.dashboard'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-[#C41E3A] rounded-r-full"></div>
                @endif
            </a>

            <!-- Orders (Example) -->
            <a href="#" 
               class="flex items-center px-4 py-3 rounded-lg group transition-colors text-gray-400 hover:bg-gray-800 hover:text-white">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <div class="ml-4 flex-1 flex justify-between items-center whitespace-nowrap" x-show="sidebarOpen">
                    <span class="font-medium">Orders</span>
                    <span class="px-2 py-0.5 rounded-full bg-[#C41E3A]/20 text-[#C41E3A] text-xs border border-[#C41E3A]/30">New</span>
                </div>
            </a>

             <!-- Products (Example) -->
             <a href="#" 
               class="flex items-center px-4 py-3 rounded-lg group transition-colors text-gray-400 hover:bg-gray-800 hover:text-white">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Products</span>
            </a>

             <!-- Users (Example) -->
             <a href="#" 
               class="flex items-center px-4 py-3 rounded-lg group transition-colors text-gray-400 hover:bg-gray-800 hover:text-white">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Users</span>
            </a>

             <!-- Settings (Example) -->
             <a href="#" 
               class="flex items-center px-4 py-3 rounded-lg group transition-colors text-gray-400 hover:bg-gray-800 hover:text-white mt-auto">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="ml-4 font-medium whitespace-nowrap" x-show="sidebarOpen">Settings</span>
            </a>

        </nav>

        <!-- User Profile (Bottom) -->
        <div class="p-4 border-t border-gray-800">
             <div class="flex items-center space-x-3">
                 <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#C41E3A] to-orange-500 flex items-center justify-center font-bold text-lg shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                 </div>
                 <div class="overflow-hidden" x-show="sidebarOpen">
                     <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                     <p class="text-xs text-gray-500 truncate">Administrator</p>
                 </div>
             </div>
        </div>
    </aside>
