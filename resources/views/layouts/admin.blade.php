<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased h-full bg-gray-50 flex overflow-hidden" x-data="{ sidebarOpen: true }">

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

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <!-- Header -->
        <header class="h-20 bg-white/70 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-8 z-20 sticky top-0">
            <!-- Left: Toggle & Title -->
            <div class="flex items-center space-x-6">
                 <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-[#C41E3A] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                 </button>
                 <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                    @yield('header')
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

        <!-- Content Scroller -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
            <div class="max-w-7xl mx-auto space-y-8">
                 @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="mt-12 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} Super Asia Foods. All rights reserved.
            </footer>
        </main>

    </div>
</body>
</html>
