import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Dashboard({ auth, stats, recent_orders }) {
    const user = auth.user;
    const isPendingApproval = user?.status === 'pending';

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />

            <div className="py-12 max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                {/* Pending approval banner — shown only when account is pending */}
                {isPendingApproval && (
                    <div className="rounded-2xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-6 py-5 shadow-sm">
                        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div className="flex items-start gap-4">
                                <div className="flex-shrink-0 w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-800/50 flex items-center justify-center">
                                    <svg className="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="text-lg font-semibold text-amber-900 dark:text-amber-100">
                                        Your account is pending approval
                                    </h3>
                                    <p className="mt-1 text-sm text-amber-800 dark:text-amber-200">
                                        You can browse the shop and save items to your wishlist. Placing orders will be available once an administrator approves your account.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Welcome Section */}
                <div className="relative overflow-hidden bg-gradient-to-r from-[#1a1a1a] to-[#2d2d2d] rounded-3xl p-8 sm:p-10 shadow-2xl border border-gray-800">
                    <div className="relative z-10">
                        <h2 className="text-3xl sm:text-4xl font-bold text-white mb-2">
                            Welcome back, {user.name.split(' ')[0]}! 👋
                        </h2>
                        <p className="text-gray-400 text-lg max-w-xl">
                            {isPendingApproval
                                ? "Your registration has been received. We'll notify you when your account is approved."
                                : "Here's what's happening with your account today."}
                        </p>
                        
                        <div className="mt-8 flex flex-wrap gap-4">
                            <Link 
                                href={route('shop.index')}
                                className="inline-flex items-center justify-center px-6 py-3 bg-[#C41E3A] border border-transparent text-base font-medium rounded-xl text-white hover:bg-[#a01830] active:bg-[#8a1428] shadow-lg hover:shadow-[#C41E3A]/30 transition-all duration-200"
                            >
                                Browse Shop
                            </Link>
                            
                            {user.user_type !== 'admin' && !isPendingApproval && (
                                <Link 
                                    href={route('orders.index')} 
                                    className="inline-flex items-center justify-center px-6 py-3 border border-white/30 text-base font-medium rounded-xl text-white hover:bg-white/10 backdrop-blur-sm transition-all duration-200"
                                >
                                    View Orders
                                </Link>
                            )}
                            {user.user_type == 'admin' && (
                                <a  
                                    href={route('admin.dashboard')} 
                                    target="_blank"
                                    className="inline-flex items-center justify-center px-6 py-3 border border-white/30 text-base font-medium rounded-xl text-white hover:bg-white/10 backdrop-blur-sm transition-all duration-200"
                                >
                                    Admin Dashboard
                                </a>
                            )}
                        </div>
                    </div>
                    
                    {/* Decorative Background Elements */}
                    <div className="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-[#C41E3A] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                    <div className="absolute bottom-0 right-20 w-64 h-64 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                </div>

                {/* Stats / Widgets Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Widget 1: Orders — not clickable for pending users */}
                    {isPendingApproval ? (
                        <div className="bg-white dark:bg-[#1E1E1E] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 opacity-75 cursor-not-allowed" title="Available after account approval">
                            <div className="flex items-center justify-between mb-4">
                                <div className="h-10 w-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <span className="text-xs font-medium px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">After approval</span>
                            </div>
                            <h3 className="text-2xl font-bold text-gray-900 dark:text-white">—</h3>
                            <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Orders</p>
                        </div>
                    ) : (
                    <Link href={route('orders.index')} className="bg-white dark:bg-[#1E1E1E] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-shadow group cursor-pointer">
                        <div className="flex items-center justify-between mb-4">
                            <div className="h-10 w-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <span className="text-xs font-medium px-2.5 py-0.5 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                {stats?.active_orders || 0} Active
                            </span>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white">{stats?.total_orders || 0}</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Orders</p>
                    </Link>
                    )}

                    {/* Widget 2: Wishlist */}
                    <Link href={route('wishlist.index')} className="bg-white dark:bg-[#1E1E1E] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-shadow group cursor-pointer">
                         <div className="flex items-center justify-between mb-4">
                            <div className="h-10 w-10 rounded-full bg-pink-50 dark:bg-pink-900/30 flex items-center justify-center text-pink-500 dark:text-pink-400 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                             <div className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                  <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                </svg>
                             </div>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white">{stats?.wishlist_count || 0}</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Saved Items</p>
                    </Link>

                    {/* Widget 3: Addresses */}
                    <Link href={route('addresses.index')} className="bg-white dark:bg-[#1E1E1E] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-shadow group cursor-pointer relative overflow-hidden">
                         <div className="flex items-center justify-between mb-4 relative z-10">
                            <div className="h-10 w-10 rounded-full bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div className="h-8 w-8 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-400 group-hover:text-[#C41E3A] group-hover:bg-red-50 dark:group-hover:bg-red-900/20 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white relative z-10">Address</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1 relative z-10">Manage Shipping & Billing</p>
                        <div className="absolute -bottom-4 -right-4 h-24 w-24 bg-purple-500/5 rounded-full blur-2xl group-hover:bg-purple-500/10 transition-all duration-300"></div>
                    </Link>
                </div>

                {/* Recent Activity / Orders Preview */}
                <div className="bg-white dark:bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                    <div className="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <h3 className="text-lg font-bold text-gray-900 dark:text-white">Recent Orders</h3>
                        {!isPendingApproval && (
                            <Link href={route('orders.index')} className="text-sm font-medium text-[#C41E3A] hover:text-[#a01830]">
                                View all
                            </Link>
                        )}
                    </div>
                    {isPendingApproval ? (
                        <div className="px-6 py-12 text-center">
                            <div className="inline-flex w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-900/30 items-center justify-center text-amber-600 dark:text-amber-400 mb-4">
                                <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p className="text-gray-600 dark:text-gray-400 font-medium">Orders will appear here once your account is approved.</p>
                            <p className="text-sm text-gray-500 dark:text-gray-500 mt-1">You can still browse the shop and save items to your wishlist.</p>
                        </div>
                    ) : (
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                                <tr>
                                    <th scope="col" className="px-6 py-3">Order ID</th>
                                    <th scope="col" className="px-6 py-3">Date</th>
                                    <th scope="col" className="px-6 py-3">Status</th>
                                    <th scope="col" className="px-6 py-3">Total</th>
                                    <th scope="col" className="px-6 py-3"><span className="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                {recent_orders.length > 0 ? (
                                    recent_orders.map((order) => (
                                        <tr key={order.id} className="bg-white border-b dark:bg-[#1E1E1E] dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td className="px-6 py-4 font-medium text-gray-900 dark:text-white">#{order.id}</td>
                                            <td className="px-6 py-4">{order.created_at}</td>
                                            <td className="px-6 py-4">
                                                <span className={`text-xs font-semibold px-2.5 py-0.5 rounded capitalize ${
                                                    order.status === 'completed' || order.status === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
                                                    order.status === 'processing' || order.status === 'pending' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' :
                                                    order.status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' :
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                                }`}>
                                                    {order.status}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4">${Number(order.total_amount).toFixed(2)}</td>
                                            <td className="px-6 py-4 text-right">
                                                <Link href={route('orders.show', order.id)} className="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</Link>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr className="bg-white border-b dark:bg-[#1E1E1E] dark:border-gray-800">
                                        <td colSpan="5" className="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            No orders found. <Link href={route('shop.index')} className="text-[#C41E3A] hover:underline">Start shopping</Link>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    )}
                </div>

            </div>
        </AuthenticatedLayout>
    );
}
