<div 
    x-data="{
        notifications: {{ Js::from($notifications) }},
        unreadCount: {{ $unreadCount }},
        open: false,
        audio: new Audio('https://cdn.freesound.org/previews/320/320655_5260872-lq.mp3'), 
        
        async poll() {
            try {
                const response = await fetch('{{ route('admin.notifications.poll') }}');
                const data = await response.json();
                
                const previousCount = this.unreadCount;
                
                // Only update if there are changes to avoid unnecessary re-renders or logic
                if (data.unread_count !== this.unreadCount || data.notifications.length !== this.notifications.length) {
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                
                    // Play sound if new notifications arrived (count increased)
                    if (this.unreadCount > previousCount) {
                        this.audio.play().catch(e => console.log('Audio play failed:', e));
                        
                        // Show toast for the newest notification if it's actually new
                        if (this.notifications.length > 0) {
                            const newest = this.notifications[0];
                            window.dispatchEvent(new CustomEvent('notify', { 
                                detail: { 
                                    message: newest.data.message,
                                    type: 'info'
                                }
                            }));
                        }
                    }
                }
                
            } catch (error) {
                console.error('Notification poll failed:', error);
            }
        },
        
        async markAsRead(id = null) {
            try {
                await fetch('{{ route('admin.notifications.mark-read') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ id: id })
                });
                
                // Refresh notifications
                this.poll();
                
            } catch (error) {
                console.error('Mark as read failed:', error);
            }
        },

        init() {
            // Poll every 5 seconds
            setInterval(() => this.poll(), 5000);
        }
    }"
    class="relative"
    @click.away="open = false"
>
    <!-- Bell Icon -->
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none transition duration-150 ease-in-out">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        
        <!-- Badge -->
        <template x-if="unreadCount > 0">
            <span class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-[#C41E3A] ring-2 ring-white text-[10px] font-bold text-white text-center leading-4" x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </template>
    </button>

    <!-- Dropdown Panel -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50 border border-gray-100"
        style="display: none;"
    >
        <div class="py-2">
            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Notifications</h3>
                <button @click="markAsRead(null)" class="text-xs text-[#C41E3A] hover:text-red-700 font-medium">Mark all as read</button>
            </div>
            
            <div class="max-h-64 overflow-y-auto">
                <template x-if="notifications.length === 0">
                    <div class="px-4 py-6 text-center text-gray-500 text-sm">
                        No new notifications
                    </div>
                </template>
                
                <template x-for="notification in notifications" :key="notification.id">
                    <a :href="notification.data.type === 'credit_note' ? `/admin/credit-notes/${notification.data.credit_note_id}` : `/admin/orders/${notification.data.order_id}`" 
                       @click="markAsRead(notification.id)"
                       class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100 last:border-0"
                    >
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full"
                                      :class="notification.data.type === 'credit_note' ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-[#C41E3A]'">
                                    <template x-if="notification.data.type === 'credit_note'">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                                        </svg>
                                    </template>
                                    <template x-if="!notification.data.type">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </template>
                                </span>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900" x-text="notification.data.message"></p>
                                <p class="mt-1 text-xs text-gray-500" x-text="new Date(notification.created_at).toLocaleString()"></p>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
            
            <a href="{{ route('admin.orders.index') }}" class="block text-center px-4 py-2 text-sm text-gray-600 bg-gray-50 hover:bg-gray-100 font-medium">
                View all orders
            </a>
        </div>
    </div>
</div>
