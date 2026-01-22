import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage } from '@inertiajs/react';

export default function Dashboard() {
    const user = usePage().props.auth.user;

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                    
                    {/* Welcome Banner */}
                    <div className="overflow-hidden bg-gradient-to-r from-[#C41E3A] to-[#E02443] rounded-2xl shadow-lg relative text-white">
                        <div className="p-8 md:p-12 relative z-10">
                            <h3 className="text-3xl font-bold mb-2">Welcome back, {user.name}!</h3>
                            <p className="text-red-100 text-lg opacity-90">
                                {user.user_type === 'admin' 
                                    ? "Manage your store, track orders, and oversee operations." 
                                    : "Here is what's happening with your projects and orders."}
                            </p>
                            
                            {user.user_type === 'admin' && (
                                <div className="mt-6">
                                    <a href={route('admin.dashboard')} className="inline-block px-6 py-2 bg-white text-[#C41E3A] font-bold rounded-lg hover:bg-gray-100 transition shadow-md">
                                        Go to Admin Panel &rarr;
                                    </a>
                                </div>
                            )}
                        </div>
                        {/* Decorative Circle */}
                        <div className="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                        <div className="absolute -left-12 -bottom-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
                    </div>

                    {/* Stats / Widgets Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="bg-white dark:bg-[#1E1E1E] p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition">
                            <h4 className="text-gray-500 dark:text-gray-400 font-medium text-sm text-transform uppercase tracking-wider mb-2">My Profile</h4>
                            <div className="flex items-center justify-between">
                                <div className="text-2xl font-bold text-gray-800 dark:text-gray-100">Settings</div>
                                <span className="text-2xl">⚙️</span>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-[#1E1E1E] p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition">
                            <h4 className="text-gray-500 dark:text-gray-400 font-medium text-sm text-transform uppercase tracking-wider mb-2">Notifications</h4>
                            <div className="flex items-center justify-between">
                                <div className="text-2xl font-bold text-gray-800 dark:text-gray-100">3 New</div>
                                <span className="text-2xl">🔔</span>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-[#1E1E1E] p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition">
                            <h4 className="text-gray-500 dark:text-gray-400 font-medium text-sm text-transform uppercase tracking-wider mb-2">Orders</h4>
                            <div className="flex items-center justify-between">
                                <div className="text-2xl font-bold text-gray-800 dark:text-gray-100">Track</div>
                                <span className="text-2xl">📦</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
