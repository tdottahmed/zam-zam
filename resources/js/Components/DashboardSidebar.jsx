import { Link, usePage } from '@inertiajs/react';

export default function DashboardSidebar({ className = '' }) {
    const { url, auth } = usePage().props;
    const user = auth.user;

    // Helper to check active state
    const isActive = (routeName) => {
        return route().current(routeName);
    };

    const links = [
        { name: 'Dashboard', href: route('dashboard'), route: 'dashboard', icon: 'HomeIcon' },
        ...(user.user_type === 'admin' ? [
            { name: 'Admin Panel', href: route('admin.dashboard'), route: 'admin.dashboard', icon: 'AdminIcon' }
        ] : [
            { name: 'My Orders', href: route('orders.index'), route: 'orders.*', icon: 'ShoppingBagIcon' }
        ]),
        { name: 'Address Book', href: route('addresses.index'), route: 'addresses.*', icon: 'MapPinIcon' }, 
        { name: 'Account Details', href: route('profile.edit'), route: 'profile.*', icon: 'UserIcon' },
        { name: 'Wishlist', href: route('wishlist.index'), route: 'wishlist.*', icon: 'HeartIcon' }, 
    ];

    return (
        <aside className={`w-72 bg-white dark:bg-[#1E1E1E] border-r border-gray-100 dark:border-gray-800 flex-shrink-0 flex flex-col h-full ${className}`}>
            
            {/* User Profile Summary */}
            <div className="p-6 border-b border-gray-100 dark:border-gray-800">
                <div className="flex items-center gap-4">
                    <div className="h-12 w-12 rounded-full bg-gradient-to-tr from-[#C41E3A] to-orange-500 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        {user.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <h3 className="text-base font-bold text-gray-900 dark:text-white leading-tight">{user.name}</h3>
                        <p className="text-xs text-gray-500 dark:text-gray-400">Customer Account</p>
                    </div>
                </div>
            </div>

            {/* Navigation */}
            <div className="flex-1 px-4 py-6 overflow-y-auto">
                <p className="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Menu</p>
                <ul className="space-y-1">
                    {links.map((link) => {
                        const active = isActive(link.route);
                        return (
                            <li key={link.name}>
                                <Link
                                    href={link.href === '#' ? undefined : link.href}
                                    className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden ${
                                        active
                                            ? 'bg-[#C41E3A]/10 text-[#C41E3A] font-medium'
                                            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white'
                                    }`}
                                >
                                    {/* Active Indicator Bar */}
                                    {active && (
                                        <div className="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-[#C41E3A] rounded-r-md"></div>
                                    )}

                                    <span className={`transition-colors duration-200 ${
                                        active ? 'text-[#C41E3A]' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300'
                                    }`}>
                                       {/* Icons */}
                                       {link.icon === 'HomeIcon' && (
                                           <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                       )}
                                       {link.icon === 'ShoppingBagIcon' && (
                                           <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                       )}
                                       {link.icon === 'UserIcon' && (
                                           <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                       )}
                                       {link.icon === 'MapPinIcon' && (
                                           <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                       )}
                                       {link.icon === 'HeartIcon' && (
                                           <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                       )}
                                       {link.icon === 'AdminIcon' && (
                                           <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                       )}
                                    </span>
                                    <span className="font-medium">{link.name}</span>
                                </Link>
                            </li>
                        );
                    })}
                </ul>
            </div>

            {/* Bottom Actions */}
            <div className="p-4 border-t border-gray-100 dark:border-gray-800">
                <Link
                    href={route('logout')}
                    method="post"
                    as="button"
                    className="flex w-full items-center gap-3 px-4 py-3 text-gray-600 rounded-xl dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-500 transition-colors duration-200 group"
                >
                    <svg className="w-5 h-5 transition-colors group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span className="font-medium">Log Out</span>
                </Link>
            </div>
        </aside>
    );
}
