import StorageImage from '@/Components/StorageImage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Show({ order, offlinePaymentMethods = [] }) {
    const shippingAddress = order.shipping_address || {};
    const items = order.items || [];
    
    const isOfflineMethod = offlinePaymentMethods.some(m => m.name === order.payment_method);
    const offlineMethod = isOfflineMethod ? offlinePaymentMethods.find(m => m.name === order.payment_method) : null;
    
    // Check if we need to show the payment form
    // We show it if it's an offline method, payment_status is pending, and we have a matched method
    const needsPaymentDetails = isOfflineMethod && order.payment_status === 'pending' && offlineMethod && (!order.payment_data || Object.keys(order.payment_data).length === 0);

    const { data, setData, post, processing, errors } = useForm({
        payment_data: order.payment_data || {}
    });

    const submitPayment = (e) => {
        e.preventDefault();
        post(route('orders.submit-payment', order.id), {
            preserveScroll: true,
            forceFormData: true, // Needed for file uploads
        });
    };

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
                    <div className="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a 
                             href={order.invoice ? route('orders.download-invoice', order.id) : '#'} 
                             target={order.invoice ? "_blank" : "_self"}
                             className={`inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-gray-300 dark:hover:bg-[#333] transition-all duration-200 shadow-sm ${!order.invoice ? 'opacity-50 cursor-not-allowed' : ''}`}
                             title={order.invoice ? "Download Invoice" : "Invoice not available"}
                        >
                            <svg className="w-5 h-5 mr-2.5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Download Invoice
                        </a>
                        <Link 
                            href={route('credit-notes.create', order.id)}
                            className="inline-flex items-center justify-center px-5 py-2.5 bg-[#C41E3A] border border-transparent rounded-xl text-sm font-medium text-white hover:bg-[#a01830] active:bg-[#8a1428] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
                        >
                            <svg className="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                            Create Credit Note
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
                                                     <StorageImage
                                                     path={item.product.image}
                                                     name={item.product.name}
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

                        {/* Credit Notes Section */}
                        {order.credit_notes && order.credit_notes.length > 0 && (
                            <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                                <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white">Returns & Credit Notes</h3>
                                </div>
                                <div className="divide-y divide-gray-100 dark:divide-gray-800">
                                    {order.credit_notes.map((cn) => (
                                        <div key={cn.id} className="p-6 hover:bg-gray-50/30 dark:hover:bg-gray-800/30 transition-colors flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                            <div>
                                                <div className="flex items-center gap-3">
                                                    <h4 className="text-base font-bold text-gray-900 dark:text-white">
                                                        {cn.credit_note_number}
                                                    </h4>
                                                    <StatusBadge status={cn.status} />
                                                </div>
                                                <div className="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                   <span>Requested on {new Date(cn.created_at).toLocaleDateString()}</span>
                                                   <span className="hidden sm:inline">&bull;</span>
                                                   <span>Reason: {cn.reason}</span>
                                                   <span className="hidden sm:inline">&bull;</span>
                                                   <span className="font-medium text-[#C41E3A]">${Number(cn.grand_total).toFixed(2)}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <Link 
                                                    href={route('credit-notes.show', cn.id)}
                                                    className="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-[#252525] hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] transition-colors"
                                                >
                                                    View Details
                                                </Link>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
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
                                        {offlineMethod ? offlineMethod.name : (order.payment_method || 'Unknown')}
                                    </p>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-1 capitalize">
                                        Status: {order.payment_status}
                                    </p>
                                </div>
                            </div>

                            {isOfflineMethod && order.payment_data && Object.keys(order.payment_data).length > 0 && (
                                <div className="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                                    <h4 className="text-sm font-medium text-gray-900 dark:text-white mb-3">Submitted Details:</h4>
                                    <dl className="space-y-3">
                                        {Object.entries(order.payment_data).map(([key, value]) => {
                                            const fieldDef = offlineMethod?.required_fields?.find(f => f.name === key);
                                            const label = fieldDef?.label || key.replace('_', ' ');
                                            const isImage = typeof value === 'string' && value.match(/\.(jpeg|jpg|gif|png|webp)$/i) != null;
                                            
                                            return (
                                                <div key={key}>
                                                    <dt className="text-xs text-gray-500 dark:text-gray-400 capitalize">{label}</dt>
                                                    <dd className="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                                        {isImage || fieldDef?.type === 'file' ? (
                                                            <a href={`/storage/${value}`} target="_blank" rel="noreferrer" className="text-[#C41E3A] hover:underline flex items-center gap-1">
                                                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                                View Document
                                                            </a>
                                                        ) : (
                                                            value
                                                        )}
                                                    </dd>
                                                </div>
                                            );
                                        })}
                                    </dl>
                                </div>
                            )}
                        </div>

                        {/* Payment Submission Form */}
                        {needsPaymentDetails && (
                            <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-[#C41E3A]/30 p-6 relative overflow-hidden">
                                <div className="absolute top-0 left-0 w-1 h-full bg-[#C41E3A]"></div>
                                <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-2">Complete Your Payment</h3>
                                <p className="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    {offlineMethod.description || "Please submit the following details to verify your payment."}
                                </p>
                                
                                <form onSubmit={submitPayment} className="space-y-4">
                                    {offlineMethod.required_fields?.map((field, index) => (
                                        <div key={index}>
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                {field.label} {field.is_required ? <span className="text-red-500">*</span> : null}
                                            </label>
                                            
                                            {field.type === 'file' ? (
                                                <input 
                                                    type="file" 
                                                    required={field.is_required}
                                                    onChange={e => setData('payment_data', {...data.payment_data, [field.name]: e.target.files[0]})}
                                                    className="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#C41E3A]/10 file:text-[#C41E3A] hover:file:bg-[#C41E3A]/20 transition-colors border border-gray-300 dark:border-gray-700 rounded-xl"
                                                />
                                            ) : (
                                                <input 
                                                    type={field.type === 'number' ? 'number' : 'text'}
                                                    required={field.is_required}
                                                    value={data.payment_data[field.name] || ''}
                                                    onChange={e => setData('payment_data', {...data.payment_data, [field.name]: e.target.value})}
                                                    className="block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors text-sm py-2.5"
                                                    placeholder={`Enter ${field.label}`}
                                                />
                                            )}
                                            {errors[`payment_data.${field.name}`] && (
                                                <p className="mt-1 text-sm text-red-600">{errors[`payment_data.${field.name}`]}</p>
                                            )}
                                        </div>
                                    ))}
                                    
                                    <button 
                                        type="submit" 
                                        disabled={processing}
                                        className="w-full mt-2 inline-flex justify-center items-center px-4 py-2.5 bg-[#C41E3A] border border-transparent rounded-xl font-semibold text-white uppercase tracking-widest hover:bg-[#a01830] focus:bg-[#a01830] active:bg-[#801326] focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-offset-2 transition ease-in-out duration-150 shadow-md disabled:opacity-50"
                                    >
                                        {processing ? 'Submitting...' : 'Submit Payment Details'}
                                    </button>
                                </form>
                            </div>
                        )}

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
