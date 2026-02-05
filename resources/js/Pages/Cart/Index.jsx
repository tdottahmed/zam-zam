import { Link, usePage, router, Head } from '@inertiajs/react';
import CustomerLayout from '../../Layouts/CustomerLayout';
import StorageImage from '../../Components/StorageImage';
import { useState } from 'react';

export default function Index() {
    const { cart } = usePage().props;
    const [loadingId, setLoadingId] = useState(null);

    const updateQuantity = (itemId, quantity) => {
        if (quantity < 1) return;
        setLoadingId(itemId);
        router.patch(route('cart.update', itemId), { quantity }, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => setLoadingId(null)
        });
    };

    const removeItem = (itemId) => {
        if (!confirm('Are you sure you want to remove this item?')) return;
        setLoadingId(itemId);
        router.delete(route('cart.destroy', itemId), {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => setLoadingId(null)
        });
    };

    return (
        <CustomerLayout>
            <Head title="Shopping Cart" />
            <div className="bg-gray-50/50 min-h-screen py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-3xl font-extrabold text-gray-900 mb-10">Shopping Cart</h1>

                    {cart && cart.items && cart.items.length > 0 ? (
                        <div className="flex flex-col lg:flex-row gap-8 lg:gap-12">
                            {/* Cart Items Section */}
                            <div className="flex-1">
                                <div className="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
                                    <ul role="list" className="divide-y divide-gray-100">
                                        {cart.items.map((item) => (
                                            <li key={item.id} className="p-6 sm:p-8 flex flex-col sm:flex-row gap-6 transition hover:bg-gray-50/50">
                                                {/* Product Image */}
                                                <div className="flex-shrink-0">
                                                    <div className="h-28 w-28 sm:h-36 sm:w-36 rounded-xl border border-gray-100 bg-white p-3 overflow-hidden shadow-sm">
                                                        <StorageImage
                                                            path={item.image}
                                                            name={item.name}
                                                            className="h-full w-full object-contain object-center"
                                                        />
                                                    </div>
                                                </div>

                                                {/* Product Info & Controls */}
                                                <div className="flex flex-1 flex-col justify-between">
                                                    <div className="flex justify-between items-start gap-4">
                                                        <div>
                                                            <h3 className="text-lg font-bold text-gray-900 leading-snug">
                                                                <Link href={route('shop.show', item.product_id)} className="hover:text-[#C41E3A] transition-colors line-clamp-2">
                                                                    {item.name}
                                                                </Link>
                                                            </h3>
                                                            {item.unit && (
                                                                <p className="mt-1 text-sm text-gray-500 font-medium bg-gray-100 inline-block px-2 py-0.5 rounded">
                                                                    {item.unit_price} / {item.unit}
                                                                </p>
                                                            )}
                                                        </div>
                                                        <p className="text-lg font-bold text-gray-900 tabular-nums">
                                                            ${(item.total).toFixed(2)}
                                                        </p>
                                                    </div>

                                                    <div className="mt-4 flex items-end justify-between">
                                                        {/* Quantity Control */}
                                                        <div className="flex items-center gap-3">
                                                            <div className="flex items-center border border-gray-200 rounded-lg bg-white shadow-sm overflow-hidden">
                                                                <button 
                                                                    onClick={() => updateQuantity(item.id, item.quantity - 1)}
                                                                    disabled={item.quantity <= 1 || loadingId === item.id}
                                                                    className="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 disabled:opacity-50 transition border-r border-gray-100"
                                                                >
                                                                    -
                                                                </button>
                                                                <span className="w-12 text-center text-gray-900 font-semibold px-2">
                                                                    {loadingId === item.id ? '...' : item.quantity}
                                                                </span>
                                                                <button 
                                                                    onClick={() => updateQuantity(item.id, item.quantity + 1)}
                                                                    disabled={loadingId === item.id}
                                                                    className="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 border-l border-gray-100 transition"
                                                                >
                                                                    +
                                                                </button>
                                                            </div>
                                                        </div>

                                                        {/* Remove Action */}
                                                        <button
                                                            type="button"
                                                            onClick={() => removeItem(item.id)}
                                                            className="flex items-center text-sm font-medium text-gray-400 hover:text-[#C41E3A] transition-colors group p-2 hover:bg-red-50 rounded-lg"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5 mr-1.5 group-hover:scale-110 transition-transform">
                                                                <path strokeLinecap="round" strokeLinejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>

                            {/* Order Summary Section */}
                            <div className="lg:w-[24rem]">
                                <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 sticky top-24">
                                    <h2 className="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                                    <div className="flow-root">
                                        <dl className="-my-4 divide-y divide-gray-100 text-sm">
                                            <div className="flex items-center justify-between py-4">
                                                <dt className="text-gray-500">Subtotal</dt>
                                                <dd className="font-bold text-gray-900">${cart.total ? cart.total.toFixed(2) : '0.00'}</dd>
                                            </div>
                                            <div className="flex items-center justify-between py-4">
                                                <dt className="text-gray-500">Shipping estimate</dt>
                                                <dd className="font-medium text-gray-900 italic">Calculated at checkout</dd>
                                            </div>
                                            <div className="flex items-center justify-between py-4">
                                                <dt className="text-gray-500">Tax estimate</dt>
                                                <dd className="font-medium text-gray-900 italic">Calculated at checkout</dd>
                                            </div>
                                            <div className="flex items-center justify-between py-4 border-t border-gray-100 !mt-4">
                                                <dt className="text-base font-bold text-gray-900">Order total</dt>
                                                <dd className="text-2xl font-extrabold text-[#C41E3A]">${cart.total ? cart.total.toFixed(2) : '0.00'}</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <div className="mt-8 space-y-4">
                                        <button
                                            type="button"
                                            className="w-full rounded-xl border border-transparent bg-[#C41E3A] px-6 py-4 text-base font-bold text-white shadow-lg shadow-red-100 hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-offset-2 transition-all transform hover:-translate-y-0.5"
                                        >
                                            Checkout
                                        </button>
                                        <div className="text-center">
                                            <span className="text-gray-400 text-sm">or</span>
                                            <Link href={route('shop.index')} className="ml-2 font-medium text-[#C41E3A] hover:text-[#a01830] hover:underline">
                                                Continue Shopping
                                            </Link>
                                        </div>
                                    </div>
                                    
                                    {/* Trust Badges / Info */}
                                    <div className="mt-8 pt-6 border-t border-gray-100 grid grid-cols-2 gap-4 text-center">
                                        <div className="flex flex-col items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 text-gray-400">
                                              <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                            </svg>
                                            <span className="text-xs font-medium text-gray-500">Secure Checkout</span>
                                        </div>
                                        <div className="flex flex-col items-center gap-1.5">
                                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 text-gray-400">
                                              <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                            </svg>
                                            <span className="text-xs font-medium text-gray-500">Fast Delivery</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : (
                         <div className="text-center py-32 bg-white rounded-3xl shadow-sm border border-gray-100">
                            <div className="inline-flex justify-center items-center w-24 h-24 rounded-full bg-gray-50 mb-6 text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1} stroke="currentColor" className="w-12 h-12">
                                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 5c.07.277-.144.516-.41.488a10.977 10.977 0 0 1-5.26 1.508 10.979 10.979 0 0 1-5.26-1.508c-.266.028-.48-.21-.41-.488l1.263-5a.49.49 0 0 1 .454-.368 18.243 18.243 0 0 0 3.955-.42 18.22 18.22 0 0 0 3.955.42c.174 0 .332.13.454.368Z" />
                                </svg>
                            </div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                            <p className="text-gray-500 mb-10 max-w-md mx-auto text-lg leading-relaxed">It looks like you haven't added anything to your cart yet. Browse our products and find something you love.</p>
                            <Link 
                                href={route('shop.index')}
                                className="inline-flex items-center justify-center rounded-xl border border-transparent bg-[#C41E3A] px-10 py-4 text-lg font-bold text-white shadow-xl shadow-red-100 hover:bg-[#a01830] transition-all transform hover:-translate-y-1"
                            >
                                Start Shopping
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </CustomerLayout>
    );
}
