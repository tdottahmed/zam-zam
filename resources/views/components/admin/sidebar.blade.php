    <!-- Sidebar -->
    <aside class="z-30 flex flex-shrink-0 flex-col bg-[#1A1A1A] text-white transition-all duration-300 ease-in-out"
           :class="sidebarOpen ? 'w-72' : 'w-20'">
      <!-- Logo Area -->
      <div class="relative flex h-20 items-center justify-center border-b border-gray-800 bg-[#C41E3A]">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 overflow-hidden px-4">
          <!-- Icon -->
          <img src="{{ $globalSettings['site_logo'] }}" alt="{{ $globalSettings['site_name'] }}"
               class="h-12 w-auto rounded-sm bg-white object-contain p-0.5">


        </a>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 space-y-2 overflow-y-auto px-3 py-6">

        <!-- Dashboard Link -->
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group relative flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
            </path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Dashboard</span>

          @if (request()->routeIs('admin.dashboard'))
            <div class="absolute left-0 top-1/2 h-8 w-1 -translate-y-1/2 rounded-r-full bg-[#C41E3A]"></div>
          @endif
        </a>
        <!-- Products -->
        <a href="{{ route('admin.products.index') }}"
           class="{{ request()->routeIs('admin.products.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Products</span>
        </a>

        <!-- Orders -->
        <a href="{{ route('admin.orders.index') }}"
           class="{{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <div
               class="{{ request()->routeIs('admin.orders.*') ? 'bg-[#C41E3A]' : 'group-hover:bg-[#C41E3A]' }} rounded-md p-1 transition-colors">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
          </div>
          <div class="ml-3 flex flex-1 items-center justify-between whitespace-nowrap" x-show="sidebarOpen">
            <span class="font-medium">Orders</span>
            @if (\App\Models\Order::where('status', 'pending')->count() > 0)
              <span class="rounded-full border border-[#C41E3A]/30 bg-[#C41E3A]/20 px-2 py-0.5 text-xs text-[#C41E3A]">
                {{ \App\Models\Order::where('status', 'pending')->count() }}
              </span>
            @endif
          </div>
        </a>

        <!-- Invoices -->
        <a href="{{ route('admin.invoices.index') }}"
           class="{{ request()->routeIs('admin.invoices.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Invoices</span>
        </a>

        <!-- Credit Notes -->
        <a href="{{ route('admin.credit-notes.index') }}"
           class="{{ request()->routeIs('admin.credit-notes.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Credit Notes</span>
        </a>

        <!-- Categories -->
        <a href="{{ route('admin.categories.index') }}"
           class="{{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
            </path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Categories</span>
        </a>

        <!-- Brands -->
        <a href="{{ route('admin.brands.index') }}"
           class="{{ request()->routeIs('admin.brands.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
            </path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Brands</span>
        </a>

        <!-- Customers -->
        <a href="{{ route('admin.users.index') }}"
           class="{{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
            </path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Customers</span>
        </a>

        <!-- Newsletter Subscribers -->
        <a href="{{ route('admin.newsletter-subscribers.index') }}"
           class="{{ request()->routeIs('admin.newsletter-subscribers.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
            </path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Newsletter</span>
        </a>

        <!-- Pages -->
        <a href="{{ route('admin.pages.index') }}"
           class="{{ request()->routeIs('admin.pages.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} group flex items-center rounded-lg px-4 py-3 transition-colors">
          <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Pages</span>
        </a>

        <!-- Settings Dropdown -->
        <div x-data="{ settingsOpen: {{ request()->routeIs('admin.taxes.*') || request()->routeIs('admin.units.*') || request()->routeIs('admin.shipping-methods.*') || request()->routeIs('admin.offline-payment-methods.*') || request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }" class="mt-auto">
          <button @click="settingsOpen = !settingsOpen; if(!sidebarOpen) sidebarOpen = true"
                  class="group flex w-full items-center justify-between rounded-lg px-4 py-3 text-gray-400 transition-colors hover:bg-gray-800 hover:text-white"
                  :class="{ 'bg-gray-800 text-white': settingsOpen ||
                          {{ request()->routeIs('admin.taxes.*') || request()->routeIs('admin.units.*') || request()->routeIs('admin.shipping-methods.*') || request()->routeIs('admin.offline-payment-methods.*') || request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }">
            <div class="flex items-center">
              <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span class="ml-4 whitespace-nowrap font-medium" x-show="sidebarOpen">Settings</span>
            </div>
            <svg class="h-4 w-4 transition-transform duration-200" :class="settingsOpen ? 'rotate-180' : ''"
                 x-show="sidebarOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <div x-show="settingsOpen && sidebarOpen" x-transition class="overflow-hidden bg-gray-900">
            <a href="{{ route('admin.settings.general') }}"
               class="{{ request()->routeIs('admin.settings.general') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.settings.general') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              General
            </a>
            <a href="{{ route('admin.settings.about-us') }}"
               class="{{ request()->routeIs('admin.settings.about-us') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.settings.about-us') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              About Us
            </a>
            <a href="{{ route('admin.settings.smtp') }}"
               class="{{ request()->routeIs('admin.settings.smtp') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.settings.smtp') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              SMTP
            </a>
            <a href="{{ route('admin.settings.seo') }}"
               class="{{ request()->routeIs('admin.settings.seo') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.settings.seo') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              SEO
            </a>
            <a href="{{ route('admin.settings.third-party') }}"
               class="{{ request()->routeIs('admin.settings.third-party') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.settings.third-party') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              Third Party
            </a>
            <a href="{{ route('admin.settings.health') }}"
               class="{{ request()->routeIs('admin.settings.health') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.settings.health') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              System Health
            </a>
            <a href="{{ route('admin.offline-payment-methods.index') }}"
               class="{{ request()->routeIs('admin.offline-payment-methods.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.offline-payment-methods.*') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              Payment Methods
            </a>
            <a href="{{ route('admin.taxes.index') }}"
               class="{{ request()->routeIs('admin.taxes.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.taxes.*') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              Taxes
            </a>
            <a href="{{ route('admin.units.index') }}"
               class="{{ request()->routeIs('admin.units.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.units.*') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              Units
            </a>
            <a href="{{ route('admin.shipping-methods.index') }}"
               class="{{ request()->routeIs('admin.shipping-methods.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} flex items-center py-2 pl-14 pr-4 text-sm transition-colors">
              <span
                    class="{{ request()->routeIs('admin.shipping-methods.*') ? 'bg-[#C41E3A]' : '' }} mr-2 h-1.5 w-1.5 rounded-full bg-gray-600"></span>
              Shipping Methods
            </a>
          </div>
        </div>

      </nav>

      <!-- User Profile (Bottom) -->
      <div class="border-t border-gray-800 p-4">
        <div class="flex items-center space-x-3">
          <div
               class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-tr from-[#C41E3A] to-orange-500 text-lg font-bold shadow-lg">
            {{ substr(Auth::user()->name, 0, 1) }}
          </div>
          <div class="overflow-hidden" x-show="sidebarOpen">
            <p class="truncate text-sm font-semibold">{{ Auth::user()->name }}</p>
            <p class="truncate text-xs text-gray-500">Administrator</p>
          </div>
        </div>
      </div>
    </aside>
