import { Link, usePage, useForm, Head, router } from '@inertiajs/react';
import CustomerLayout from '../../Layouts/CustomerLayout';
import StorageImage from '../../Components/StorageImage';
import { useState } from 'react';

export default function Checkout() {
    const { cart, auth } = usePage().props;
    const { data, setData, post, processing, errors } = useForm({
        email: auth.user.email || '',
        phone: auth.user.phone || '',
        shipping_address: {
            name: auth.user.name || '',
            address: '',
            city: '',
            zip: '',
            country: '',
        },
        payment_method: 'cod',
    });

    const updateQuantity = (itemId, quantity) => {
        if (quantity < 1) return;
        router.patch(route('cart.update', itemId), { quantity }, {
            preserveScroll: true,
            preserveState: true,
        });
    };

    const removeItem = (itemId) => {
        if (confirm('Are you sure you want to remove this item?')) {
            router.delete(route('cart.destroy', itemId), {
                preserveScroll: true,
                preserveState: true,
            });
        }
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('checkout.store'));
    };

    return (
        <CustomerLayout>
            <Head title="Checkout" />
            <div className="bg-gray-50 min-h-screen py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col lg:flex-row gap-12">
                        {/* Left Column: Forms */}
                        <div className="flex-1">
                            <form onSubmit={submit}>
                                {/* Contact Info */}
                                <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 mb-8">
                                    <h2 className="text-xl font-bold text-gray-900 mb-6">Contact Information</h2>
                                    <div className="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                        <div className="sm:col-span-2">
                                            <label htmlFor="email" className="block text-sm font-medium text-gray-700">Email address</label>
                                            <div className="mt-1">
                                                <input
                                                    type="email"
                                                    id="email"
                                                    value={data.email}
                                                    onChange={e => setData('email', e.target.value)}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                                {errors.email && <p className="mt-2 text-sm text-red-600">{errors.email}</p>}
                                            </div>
                                        </div>
                                         <div className="sm:col-span-2">
                                            <label htmlFor="phone" className="block text-sm font-medium text-gray-700">Phone Number</label>
                                            <div className="mt-1">
                                                <input
                                                    type="text"
                                                    id="phone"
                                                    value={data.phone}
                                                    onChange={e => setData('phone', e.target.value)}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                                {errors.phone && <p className="mt-2 text-sm text-red-600">{errors.phone}</p>}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Shipping Address */}
                                <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 mb-8">
                                    <h2 className="text-xl font-bold text-gray-900 mb-6">Shipping Address</h2>
                                    <div className="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div className="sm:col-span-6">
                                            <label htmlFor="name" className="block text-sm font-medium text-gray-700">Full Name</label>
                                            <div className="mt-1">
                                                <input
                                                    type="text"
                                                    id="name"
                                                    value={data.shipping_address.name}
                                                    onChange={e => setData('shipping_address', { ...data.shipping_address, name: e.target.value })}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                                 {errors['shipping_address.name'] && <p className="mt-2 text-sm text-red-600">{errors['shipping_address.name']}</p>}
                                            </div>
                                        </div>

                                        <div className="sm:col-span-6">
                                            <label htmlFor="address" className="block text-sm font-medium text-gray-700">Address</label>
                                            <div className="mt-1">
                                                <input
                                                    type="text"
                                                    id="address"
                                                    value={data.shipping_address.address}
                                                    onChange={e => setData('shipping_address', { ...data.shipping_address, address: e.target.value })}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                                 {errors['shipping_address.address'] && <p className="mt-2 text-sm text-red-600">{errors['shipping_address.address']}</p>}
                                            </div>
                                        </div>

                                        <div className="sm:col-span-2">
                                            <label htmlFor="city" className="block text-sm font-medium text-gray-700">City</label>
                                            <div className="mt-1">
                                                <input
                                                    type="text"
                                                    id="city"
                                                    value={data.shipping_address.city}
                                                    onChange={e => setData('shipping_address', { ...data.shipping_address, city: e.target.value })}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                                 {errors['shipping_address.city'] && <p className="mt-2 text-sm text-red-600">{errors['shipping_address.city']}</p>}
                                            </div>
                                        </div>

                                        <div className="sm:col-span-2">
                                            <label htmlFor="region" className="block text-sm font-medium text-gray-700">State / Province</label>
                                            <div className="mt-1">
                                                <input
                                                    type="text"
                                                    id="region"
                                                    disabled
                                                    placeholder="N/A"
                                                    className="block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div className="sm:col-span-2">
                                            <label htmlFor="postal-code" className="block text-sm font-medium text-gray-700">ZIP / Postal code</label>
                                            <div className="mt-1">
                                                <input
                                                    type="text"
                                                    id="postal-code"
                                                    value={data.shipping_address.zip}
                                                    onChange={e => setData('shipping_address', { ...data.shipping_address, zip: e.target.value })}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                                 {errors['shipping_address.zip'] && <p className="mt-2 text-sm text-red-600">{errors['shipping_address.zip']}</p>}
                                            </div>
                                        </div>
                                         <div className="sm:col-span-6">
                                            <label htmlFor="country" className="block text-sm font-medium text-gray-700">Country</label>
                                            <div className="mt-1">
                                                <input
                                                    type="text"
                                                    id="country"
                                                    value={data.shipping_address.country}
                                                    onChange={e => setData('shipping_address', { ...data.shipping_address, country: e.target.value })}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm"
                                                />
                                                 {errors['shipping_address.country'] && <p className="mt-2 text-sm text-red-600">{errors['shipping_address.country']}</p>}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Payment Method */}
                                <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                                    <h2 className="text-xl font-bold text-gray-900 mb-6">Payment Method</h2>
                                    <div className="space-y-4">
                                        <div className="relative flex items-start">
                                            <div className="flex h-5 items-center">
                                                <input
                                                    id="payment-cod"
                                                    name="payment-method"
                                                    type="radio"
                                                    checked={data.payment_method === 'cod'}
                                                    onChange={() => setData('payment_method', 'cod')}
                                                    className="h-4 w-4 border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A]"
                                                />
                                            </div>
                                            <div className="ml-3 text-sm">
                                                <label htmlFor="payment-cod" className="font-medium text-gray-700">Cash on Delivery (COD)</label>
                                                <p className="text-gray-500">Pay when you receive your order.</p>
                                            </div>
                                        </div>
                                         <div className="relative flex items-start opacity-50 cursor-not-allowed">
                                            <div className="flex h-5 items-center">
                                                <input
                                                    id="payment-card"
                                                    name="payment-method"
                                                    type="radio"
                                                    disabled
                                                    className="h-4 w-4 border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A]"
                                                />
                                            </div>
                                            <div className="ml-3 text-sm">
                                                <label htmlFor="payment-card" className="font-medium text-gray-700">Credit Card (Coming Soon)</label>
                                                <p className="text-gray-500">Secure online payment.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {/* Right Column: Order Summary */}
                            <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8 sticky top-24">
                                <h2 className="text-lg font-bold text-gray-900 mb-6">Order Summary</h2>

                                <div className="flow-root max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                                    <ul role="list" className="-my-4 divide-y divide-gray-100">
                                        {cart.items.map((item) => (
                                            <li key={item.id} className="flex py-6 transition hover:bg-gray-50 -mx-4 px-4 rounded-lg relative group">
                                                <div className="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-white p-2 shadow-sm">
                                                     <StorageImage
                                                        path={item.image}
                                                        name={item.name}
                                                        className="h-full w-full object-contain object-center"
                                                    />
                                                </div>
                                                <div className="ml-4 flex flex-1 flex-col justify-between">
                                                    <div>
                                                        <div className="flex justify-between text-base font-bold text-gray-900">
                                                            <h3 className="line-clamp-2 pr-4 text-sm leading-snug">{item.name}</h3>
                                                            <p className="tabular-nums">${item.total.toFixed(2)}</p>
                                                        </div>
                                                        {item.unit && <p className="mt-1 text-xs text-gray-500">{item.unit_price} / {item.unit}</p>}
                                                    </div>
                                                    
                                                    <div className="flex items-center justify-between mt-3">
                                                        {/* Quantity Control */}
                                                        <div className="flex items-center border border-gray-200 rounded-lg bg-white h-8 w-fit shadow-sm overflow-hidden">
                                                            <button 
                                                                onClick={(e) => { e.preventDefault(); updateQuantity(item.id, item.quantity - 1); }}
                                                                disabled={item.quantity <= 1}
                                                                className="w-8 h-full flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 disabled:opacity-50 transition border-r border-gray-100"
                                                            >
                                                                -
                                                            </button>
                                                            <span className="w-8 text-center text-xs font-bold text-gray-900 px-1">
                                                                {item.quantity}
                                                            </span>
                                                            <button 
                                                                onClick={(e) => { e.preventDefault(); updateQuantity(item.id, item.quantity + 1); }}
                                                                className="w-8 h-full flex items-center justify-center bg-gray-50 hover:bg-gray-100 text-gray-600 transition border-l border-gray-100"
                                                            >
                                                                +
                                                            </button>
                                                        </div>

                                                        {/* Remove Button (Icon only) */}
                                                        <button 
                                                            type="button"
                                                            onClick={() => removeItem(item.id)}
                                                            className="p-1.5 text-gray-400 hover:text-[#C41E3A] hover:bg-red-50 rounded-full transition-all opacity-0 group-hover:opacity-100 focus:opacity-100"
                                                            title="Remove item"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                                                                <path strokeLinecap="round" strokeLinejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>

                                <dl className="mt-8 space-y-4 border-t border-gray-100 pt-6 text-sm">
                                    <div className="flex items-center justify-between">
                                        <dt className="text-gray-600">Subtotal</dt>
                                        <dd className="font-bold text-gray-900">${cart.total ? cart.total.toFixed(2) : '0.00'}</dd>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <dt className="text-gray-600">Shipping</dt>
                                        <dd className="font-medium text-green-600">Free</dd>
                                    </div>
                                    <div className="flex items-center justify-between border-t border-gray-100 pt-4">
                                        <dt className="text-base font-bold text-gray-900">Total</dt>
                                        <dd className="text-xl font-bold text-[#C41E3A]">${cart.total ? cart.total.toFixed(2) : '0.00'}</dd>
                                    </div>
                                </dl>

                                <div className="mt-8">
                                    <button
                                        type="submit"
                                        onClick={submit}
                                        disabled={processing}
                                        className="w-full rounded-xl border border-transparent bg-[#C41E3A] px-6 py-4 text-base font-bold text-white shadow-lg shadow-red-100 hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:-translate-y-0.5"
                                    >
                                        {processing ? 'Processing...' : 'Place Order'}
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </CustomerLayout>
    );
}
