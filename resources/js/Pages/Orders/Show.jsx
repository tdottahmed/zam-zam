import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Show({ order }) {
    const shippingAddress = order.shipping_address || {};
    // Ensure items is an array, defaulting to empty if not present
    const items = order.items || [];

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Order #{order.id}
                </h2>
            }
        >
            <Head title={`Order #${order.id}`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            
                            <div className="flex items-center justify-between mb-8">
                                <div>
                                    <h3 className="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                        Order Details
                                    </h3>
                                    <p className="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                        Placed on {new Date(order.created_at).toLocaleDateString()}
                                    </p>
                                </div>
                                <span className={`px-3 py-1 text-sm font-semibold rounded-full 
                                    ${order.status === 'completed' || order.status === 'delivered' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                    order.status === 'processing' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                    order.status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'}`}>
                                    {order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                </span>
                            </div>

                            <div className="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
                                <dl className="sm:divide-y sm:divide-gray-200 dark:sm:divide-gray-700">
                                    
                                    {/* Shipping Address */}
                                    <div className="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Shipping Address
                                        </dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                            {shippingAddress.first_name} {shippingAddress.last_name}<br />
                                            {shippingAddress.address}<br />
                                            {shippingAddress.city}, {shippingAddress.state} {shippingAddress.zip_code}<br />
                                            {shippingAddress.country}
                                        </dd>
                                    </div>

                                    {/* Payment Method */}
                                    <div className="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Payment Method
                                        </dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 capitalize">
                                            {order.payment_method}
                                        </dd>
                                    </div>
                                    
                                    {/* Order Items */}
                                    <div className="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Items
                                        </dt>
                                        <dd className="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                            <ul role="list" className="border border-gray-200 dark:border-gray-700 rounded-md divide-y divide-gray-200 dark:divide-gray-700">
                                                {items.map((item) => (
                                                    <li key={item.id} className="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                                        <div className="w-0 flex-1 flex items-center">
                                                            <div className="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg overflow-hidden">
                                                                {/* Example Placeholder Image - ideally from item.product.image */}
                                                                <svg className="h-full w-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                            <div className="ml-4 flex-1">
                                                                <div className="font-medium text-gray-900 dark:text-white">
                                                                    {item.product ? item.product.name : 'Product Unavailable'}
                                                                </div>
                                                                <div className="text-gray-500 dark:text-gray-400">
                                                                    Qty: {item.quantity} x ${Number(item.price).toFixed(2)}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div className="ml-4 flex-shrink-0 font-medium">
                                                            ${(item.quantity * item.price).toFixed(2)}
                                                        </div>
                                                    </li>
                                                ))}
                                            </ul>
                                        </dd>
                                    </div>

                                    {/* Totals */}
                                     <div className="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50 dark:bg-gray-700/50">
                                        <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Total Amount
                                        </dt>
                                        <dd className="mt-1 text-lg font-bold text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 text-[#C41E3A]">
                                            ${Number(order.total_amount).toFixed(2)}
                                        </dd>
                                    </div>

                                </dl>
                            </div>
                        </div>
                        <div className="bg-gray-50 dark:bg-gray-700/30 px-4 py-4 sm:px-6 flex justify-end">
                            <Link href={route('orders.index')} className="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                &larr; Back to Orders
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
