import { Link, usePage, router, Head } from '@inertiajs/react';
import CustomerLayout from '../../Layouts/CustomerLayout';
import StorageImage from '../../Components/StorageImage';

export default function Index() {
    const { cart } = usePage().props;

    const updateQuantity = (itemId, quantity) => {
        if (quantity < 1) return;
        router.patch(route('cart.update', itemId), { quantity }, {
            preserveScroll: true,
            preserveState: true,
        });
    };

    const removeItem = (itemId) => {
        router.delete(route('cart.destroy', itemId), {
            preserveScroll: true,
            preserveState: true,
        });
    };

    return (
        <CustomerLayout>
            <Head title="Shopping Cart" />
            <div className="bg-white min-h-screen py-8">
                <div className="max-w-[1920px] mx-auto px-4 lg:px-8">
                    <h1 className="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

                    {cart && cart.items && cart.items.length > 0 ? (
                        <div className="flex flex-col lg:flex-row gap-12">
                            {/* Cart Items */}
                            <div className="flex-1">
                                <ul role="list" className="divide-y divide-gray-200 border-t border-b border-gray-200">
                                    {cart.items.map((item) => (
                                        <li key={item.id} className="flex py-6 sm:py-10">
                                            <div className="flex-shrink-0">
                                                <div className="h-24 w-24 sm:h-32 sm:w-32 rounded-lg border border-gray-200 bg-gray-50 p-2 overflow-hidden">
                                                    <StorageImage
                                                        path={item.image}
                                                        name={item.name}
                                                        className="h-full w-full object-contain object-center"
                                                    />
                                                </div>
                                            </div>

                                            <div className="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                                <div className="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                                    <div>
                                                        <div className="flex justify-between">
                                                            <h3 className="text-lg font-medium text-gray-700">
                                                                <Link href={route('shop.show', item.product_id)} className="hover:text-[#C41E3A]">
                                                                    {item.name}
                                                                </Link>
                                                            </h3>
                                                        </div>
                                                         {item.unit && (
                                                            <p className="mt-1 text-sm text-gray-500">{item.unit}</p>
                                                        )}
                                                        <p className="mt-1 text-sm font-medium text-gray-900">${Number(item.unit_price).toFixed(2)}</p>
                                                    </div>

                                                    <div className="mt-4 sm:mt-0 sm:pr-9">
                                                        <div className="flex items-center space-x-4">
                                                            <div className="flex items-center border border-gray-300 rounded overflow-hidden">
                                                                <button 
                                                                    onClick={() => updateQuantity(item.id, item.quantity - 1)}
                                                                    className="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 border-r border-gray-300 disabled:opacity-50"
                                                                    disabled={item.quantity <= 1}
                                                                >
                                                                    -
                                                                </button>
                                                                <input 
                                                                    type="text" 
                                                                    value={item.quantity}
                                                                    readOnly
                                                                    className="w-12 text-center text-gray-900 border-none focus:ring-0 p-1.5"
                                                                />
                                                                <button 
                                                                    onClick={() => updateQuantity(item.id, item.quantity + 1)}
                                                                    className="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 border-l border-gray-300"
                                                                >
                                                                    +
                                                                </button>
                                                            </div>
                                                            <button
                                                                type="button"
                                                                onClick={() => removeItem(item.id)}
                                                                className="text-sm font-medium text-[#C41E3A] hover:text-[#a01830]"
                                                            >
                                                                Remove
                                                            </button>
                                                        </div>

                                                        <div className="absolute top-0 right-0 sm:bottom-0 sm:top-auto">
                                                            <p className="text-lg font-bold text-gray-900 text-right">
                                                                ${(item.total).toFixed(2)}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            {/* Order Summary */}
                            <div className="lg:w-96">
                                <div className="bg-gray-50 rounded-lg shadow-sm p-6 sm:p-8 sticky top-24">
                                    <h2 className="text-lg font-bold text-gray-900 mb-6">Order Summary</h2>

                                    <div className="flow-root">
                                        <dl className="-my-4 text-sm divide-y divide-gray-200">
                                            <div className="flex items-center justify-between py-4">
                                                <dt className="text-gray-600">Subtotal</dt>
                                                <dd className="font-bold text-gray-900">${cart.total ? cart.total.toFixed(2) : '0.00'}</dd>
                                            </div>
                                            <div className="flex items-center justify-between py-4">
                                                <dt className="text-gray-600">Shipping estimate</dt>
                                                <dd className="font-medium text-gray-900">$0.00</dd>
                                            </div>
                                            <div className="flex items-center justify-between py-4">
                                                <dt className="text-gray-600">Tax estimate</dt>
                                                <dd className="font-medium text-gray-900">$0.00</dd>
                                            </div>
                                            <div className="flex items-center justify-between py-4 border-t border-gray-200 !mt-4">
                                                <dt className="text-base font-bold text-gray-900">Order total</dt>
                                                <dd className="text-xl font-bold text-[#C41E3A]">${cart.total ? cart.total.toFixed(2) : '0.00'}</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <div className="mt-8">
                                        <button
                                            type="button"
                                            className="w-full rounded-md border border-transparent bg-[#C41E3A] px-6 py-4 text-base font-bold text-white shadow-sm hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-offset-2 focus:ring-offset-gray-50 transition duration-300"
                                        >
                                            Checkout
                                        </button>
                                    </div>
                                    <div className="mt-6 text-center text-sm text-gray-500">
                                         <p>
                                            or{' '}
                                            <Link href={route('shop.index')} className="font-medium text-[#C41E3A] hover:text-[#a01830]">
                                                Continue Shopping
                                                <span aria-hidden="true"> &rarr;</span>
                                            </Link>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : (
                         <div className="text-center py-24 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <div className="inline-flex justify-center items-center w-20 h-20 rounded-full bg-gray-100 mb-6 text-[#C41E3A]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-10 h-10">
                                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 5c.07.277-.144.516-.41.488a10.977 10.977 0 0 1-5.26 1.508 10.979 10.979 0 0 1-5.26-1.508c-.266.028-.48-.21-.41-.488l1.263-5a.49.49 0 0 1 .454-.368 18.243 18.243 0 0 0 3.955-.42 18.22 18.22 0 0 0 3.955.42c.174 0 .332.13.454.368Z" />
                                </svg>
                            </div>
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
                            <p className="text-gray-500 mb-8 max-w-sm mx-auto">It looks like you haven't added anything to your cart yet.</p>
                            <Link 
                                href={route('shop.index')}
                                className="inline-flex items-center justify-center rounded-md border border-transparent bg-[#C41E3A] px-8 py-3 text-base font-medium text-white shadow-sm hover:bg-[#a01830] transition duration-300"
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
