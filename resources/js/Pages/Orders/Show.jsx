import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ order }) {
    const shippingAddress = order.shipping_address || {};
    const items = order.items || [];

    // Helper status badge with consistent styling
    const StatusBadge = ({ status }) => (
        <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium capitalize border ${
            status === 'completed' || status === 'delivered' 
                ? 'bg-green-50 text-green-700 border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-900/30' 
            : status === 'processing' 
                ? 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-900/30'
            : status === 'cancelled' 
                ? 'bg-red-50 text-red-700 border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30'
            : 'bg-yellow-50 text-yellow-700 border-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-900/30'
        }`}>
            <span className={`w-2 h-2 rounded-full mr-2 ${
                status === 'completed' || status === 'delivered' ? 'bg-green-500' :
                status === 'processing' ? 'bg-blue-500' :
                status === 'cancelled' ? 'bg-red-500' :
                'bg-yellow-500'
            }`}></span>
            {status}
        </span>
    );

    return (
        <AuthenticatedLayout title={`Order #${order.id}`}>
            <div className="space-y-6">
                 {/* Header / Breadcrumb */}
                 <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                            <Link href={route('orders.index')} className="hover:text-[#C41E3A] transition-colors">Orders</Link>
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
                            <span>#{order.id}</span>
                        </div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            Order #{order.id}
                            <StatusBadge status={order.status} />
                        </h2>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Placed on {new Date(order.created_at).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}
                        </p>
                    </div>
                    <div>
                        <a 
                             href={order.invoice ? route('orders.download-invoice', order.id) : '#'} 
                             target={order.invoice ? "_blank" : "_self"}
                             className={`inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-[#C41E3A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-gray-300 dark:hover:bg-[#333] transition-all duration-200 ${!order.invoice ? 'opacity-50 cursor-not-allowed' : ''}`}
                             title={order.invoice ? "Download Invoice" : "Invoice not available"}
                        >
                            <svg className="w-5 h-5 mr-2 -ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Download Invoice
                        </a>
                        <Link 
                            href={route('credit-notes.create', order.id)}
                            className="inline-flex items-center justify-center px-4 py-2 bg-[#C41E3A] border border-transparent rounded-xl text-sm font-medium text-white hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] shadow-sm transition-all duration-200"
                        >
                            Request Support / Return
                        </Link>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Main Content: Items */}
                    <div className="lg:col-span-2 space-y-6">
                         <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white">Order Items</h3>
                            </div>
                            <ul className="divide-y divide-gray-100 dark:divide-gray-800">
                                {items.map((item) => (
                                    <li key={item.id} className="p-6 hover:bg-gray-50/30 dark:hover:bg-gray-800/30 transition-colors">
                                        <div className="flex items-start">
                                            <div className="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white">
                                                {item.product && item.product.image ? (
                                                     <img
                                                        src={item.product.image}
                                                        alt={item.product.name}
                                                        className="h-full w-full object-cover object-center"
                                                    />
                                                ) : (
                                                    <div className="h-full w-full flex items-center justify-center bg-gray-50 dark:bg-gray-800 text-gray-400">
                                                         <svg className="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                )}
                                            </div>
                                            <div className="ml-6 flex-1 flex flex-col">
                                                <div className="flex justify-between">
                                                    <h4 className="text-base font-medium text-gray-900 dark:text-white">
                                                        {item.product ? item.product.name : 'Product Unavailable'}
                                                    </h4>
                                                    <p className="text-base font-semibold text-gray-900 dark:text-white">
                                                        ${Number(item.total_price).toFixed(2)}
                                                    </p>
                                                </div>
                                                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Variant: {item.variant_name || 'Default'}
                                                </p>
                                                <div className="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                    <span>Qty {item.quantity}</span>
                                                    <span className="mx-2">&times;</span>
                                                    <span>${Number(item.unit_price).toFixed(2)} each</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>

                    {/* Sidebar: Summary & Info */}
                    <div className="space-y-6">
                        {/* Order Summary */}
                        <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
                            <div className="space-y-3">
                                <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Subtotal</span>
                                    <span>${items.reduce((acc, item) => acc + Number(item.total_price), 0).toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Shipping</span>
                                    <span>$0.00</span> {/* Replace with actual shipping cost if available */}
                                </div>
                                <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Tax</span>
                                    <span>$0.00</span> {/* Replace with actual tax if available */}
                                </div>
                                <div className="border-t border-gray-100 dark:border-gray-800 pt-3 flex justify-between items-center">
                                    <span className="text-base font-bold text-gray-900 dark:text-white">Total</span>
                                    <span className="text-xl font-bold text-[#C41E3A]">${Number(order.total_amount).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>

                         {/* Shipping Info */}
                         <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Shipping Details</h3>
                            <div className="flex items-start gap-3">
                                <div className="mt-1 h-8 w-8 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                     <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <div className="text-sm text-gray-600 dark:text-gray-400">
                                    <p className="font-medium text-gray-900 dark:text-white mb-1">
                                        {shippingAddress.first_name} {shippingAddress.last_name}
                                    </p>
                                    <p>{shippingAddress.address}</p>
                                    {shippingAddress.address_2 && <p>{shippingAddress.address_2}</p>}
                                    <p>{shippingAddress.city}, {shippingAddress.state} {shippingAddress.zip_code}</p>
                                    <p>{shippingAddress.country}</p>
                                </div>
                            </div>
                        </div>

                        {/* Payment Info */}
                        <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h3>
                            <div className="flex items-start gap-3">
                                <div className="mt-1 h-8 w-8 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400">
                                     <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                </div>
                                <div>
                                    <p className="text-sm font-medium text-gray-900 dark:text-white capitalize">
                                        {order.payment_method?.replace('_', ' ') || 'Credit Card'}
                                    </p>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Payment is successful
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
