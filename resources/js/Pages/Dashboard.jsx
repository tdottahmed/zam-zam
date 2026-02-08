import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Dashboard() {
    const user = usePage().props.auth.user;

    return (
        <AuthenticatedLayout title="Dashboard">
             <div className="space-y-8">
                {/* Welcome Banner */}
                <div className="relative overflow-hidden rounded-3xl bg-gray-900 text-white shadow-xl">
                    <div className="absolute inset-0">
                        <img 
                            src="https://images.unsplash.com/photo-1557821552-17105176677c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2089&q=80" 
                            alt="Dashboard Background" 
                            className="h-full w-full object-cover opacity-20"
                        />
                        <div className="absolute inset-0 bg-gradient-to-r from-[#C41E3A]/90 to-purple-900/80 mix-blend-multiply" />
                    </div>
                    <div className="relative px-8 py-12 sm:px-12 sm:py-16">
                        <div className="max-w-xl">
                            <h2 className="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                                Welcome back, {user.name.split(' ')[0]}
                            </h2>
                            <p className="mt-4 text-xl text-gray-100">
                                {user.user_type === 'admin' 
                                    ? "Manage your store, track orders, and oversee operations." 
                                    : "Track your orders, manage your account, and discover new products specifically curated for you."}
                            </p>
                            <div className="mt-8 flex gap-4">
                                {user.user_type === 'admin' ? (
                                     <a 
                                        href={route('admin.dashboard')}
                                        className="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-[#C41E3A] bg-white hover:bg-gray-50 shadow-sm transition-all duration-200 transform hover:-translate-y-0.5"
                                    >
                                        Go to Admin Panel &rarr;
                                    </a>
                                ) : (
                                    <Link 
                                        href={route('shop.index')}
                                        className="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-[#C41E3A] bg-white hover:bg-gray-50 shadow-sm transition-all duration-200 transform hover:-translate-y-0.5"
                                    >
                                        Browse Shop
                                    </Link>
                                )}
                                
                                {user.user_type !== 'admin' && (
                                    <Link 
                                        href={route('admin.orders.index')} // Ideally customer orders route
                                        className="inline-flex items-center justify-center px-6 py-3 border border-white/30 text-base font-medium rounded-xl text-white hover:bg-white/10 backdrop-blur-sm transition-all duration-200"
                                    >
                                        View Orders
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Stats / Widgets Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Widget 1: Orders */}
                    <div className="bg-white dark:bg-[#1E1E1E] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-shadow group">
                        <div className="flex items-center justify-between mb-4">
                            <div className="h-10 w-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <span className="text-xs font-medium px-2.5 py-0.5 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                2 Active
                            </span>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white">12</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Orders</p>
                    </div>

                    {/* Widget 2: Wishlist (Placeholder) */}
                    <div className="bg-white dark:bg-[#1E1E1E] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-shadow group">
                         <div className="flex items-center justify-between mb-4">
                            <div className="h-10 w-10 rounded-full bg-pink-50 dark:bg-pink-900/30 flex items-center justify-center text-pink-500 dark:text-pink-400 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                             <button className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                  <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                </svg>
                             </button>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white">5</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Saved Items</p>
                    </div>

                    {/* Widget 3: Points/Rewards (Placeholder) */}
                    <div className="bg-white dark:bg-[#1E1E1E] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-shadow group">
                         <div className="flex items-center justify-between mb-4">
                            <div className="h-10 w-10 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white">Bronze</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Member Status</p>
                    </div>
                </div>

                {/* Recent Activity / Orders Preview */}
                <div className="bg-white dark:bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                    <div className="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <h3 className="text-lg font-bold text-gray-900 dark:text-white">Recent Orders</h3>
                        <Link href={route('admin.orders.index')} className="text-sm font-medium text-[#C41E3A] hover:text-[#a01830]">
                            View all
                        </Link>
                    </div>
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
                                {/* Mock Data - In real app, pass recent orders as prop */}
                                <tr className="bg-white border-b dark:bg-[#1E1E1E] dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td className="px-6 py-4 font-medium text-gray-900 dark:text-white">#ORD-2391</td>
                                    <td className="px-6 py-4">Oct 24, 2025</td>
                                    <td className="px-6 py-4">
                                        <span className="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                            Delivered
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">$120.50</td>
                                    <td className="px-6 py-4 text-right">
                                        <a href="#" className="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                    </td>
                                </tr>
                                <tr className="bg-white border-b dark:bg-[#1E1E1E] dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td className="px-6 py-4 font-medium text-gray-900 dark:text-white">#ORD-2388</td>
                                    <td className="px-6 py-4">Oct 20, 2025</td>
                                    <td className="px-6 py-4">
                                        <span className="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                            Processing
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">$75.00</td>
                                    <td className="px-6 py-4 text-right">
                                        <a href="#" className="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </AuthenticatedLayout>
    );
}
