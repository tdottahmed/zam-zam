import { Fragment, useEffect } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { Link, usePage } from '@inertiajs/react';
import useCartStore from '../../Stores/useCartStore';
import StorageImage from '../StorageImage';

export default function CartSidebar() {
    const { cart: propsCart } = usePage().props;
    const { 
        isCartOpen, 
        closeCart, 
        cart: storeCart, 
        setCart, 
        updateQuantity, 
        removeItem 
    } = useCartStore();

    // Sync store with props on mount or when props change (e.g. after navigation)
    useEffect(() => {
        setCart(propsCart);
    }, [propsCart, setCart]);

    // Use store cart for UI (optimistic)
    const cart = storeCart && storeCart.items ? storeCart : (propsCart || { items: [], total: 0 });

    return (
        <Transition.Root show={isCartOpen} as={Fragment}>
            <Dialog as="div" className="relative z-[100]" onClose={closeCart}>
                <Transition.Child
                    as={Fragment}
                    enter="ease-in-out duration-500"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in-out duration-500"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" />
                </Transition.Child>

                <div className="fixed inset-0 overflow-hidden">
                    <div className="absolute inset-0 overflow-hidden">
                        <div className="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                            <Transition.Child
                                as={Fragment}
                                enter="transform transition ease-in-out duration-500 sm:duration-700"
                                enterFrom="translate-x-full"
                                enterTo="translate-x-0"
                                leave="transform transition ease-in-out duration-500 sm:duration-700"
                                leaveFrom="translate-x-0"
                                leaveTo="translate-x-full"
                            >
                                <Dialog.Panel className="pointer-events-auto w-screen max-w-md">
                                    <div className="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                                        <div className="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                            <div className="flex items-start justify-between">
                                                <Dialog.Title className="text-xl font-bold text-gray-900"> Shopping Cart </Dialog.Title>
                                                <div className="ml-3 flex h-7 items-center">
                                                    <button
                                                        type="button"
                                                        className="relative -m-2 p-2 text-gray-400 hover:text-gray-500"
                                                        onClick={closeCart}
                                                    >
                                                        <span className="absolute -inset-0.5" />
                                                        <span className="sr-only">Close panel</span>
                                                        <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" aria-hidden="true">
                                                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div className="mt-8">
                                                <div className="flow-root">
                                                    {cart && cart.items && cart.items.length > 0 ? (
                                                        <ul role="list" className="-my-6 divide-y divide-gray-100">
                                                            {cart.items.map((item) => (
                                                                <li key={item.id} className="flex py-6 transition hover:bg-gray-50 -mx-6 px-6 relative group">
                                                                    <div className="h-24 w-24 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100 bg-white p-2">
                                                                        <StorageImage
                                                                            path={item.image}
                                                                            name={item.name}
                                                                            className="h-full w-full object-contain object-center"
                                                                        />
                                                                    </div>

                                                                    <div className="ml-4 flex flex-1 flex-col justify-between">
                                                                        <div>
                                                                            <div className="flex justify-between text-base font-medium text-gray-900">
                                                                                <h3 className="line-clamp-2 pr-6">
                                                                                     <Link href={route('shop.show', item.product_id)} onClick={closeCart} className="hover:text-[#C41E3A] transition-colors">
                                                                                        {item.name}
                                                                                    </Link>
                                                                                </h3>
                                                                                <p className="ml-4 tabular-nums font-bold text-[#C41E3A]">${item.total.toFixed(2)}</p>
                                                                            </div>
                                                                            {item.unit && <p className="mt-1 text-sm text-gray-500">{item.unit_price} / {item.unit}</p>}
                                                                        </div>
                                                                        
                                                                        <div className="flex flex-1 items-end justify-between text-sm mt-3">
                                                                            <div className="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                                                                <button 
                                                                                    onClick={() => updateQuantity(item.id, parseInt(item.quantity) - 1)}
                                                                                    className="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 disabled:opacity-50 transition"
                                                                                    disabled={item.quantity <= 1}
                                                                                >
                                                                                    -
                                                                                </button>
                                                                                <span className="px-3 py-1.5 font-bold text-gray-900 min-w-[2rem] text-center bg-white">{item.quantity}</span>
                                                                                <button 
                                                                                    onClick={() => updateQuantity(item.id, parseInt(item.quantity) + 1)}
                                                                                    className="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 transition"
                                                                                >
                                                                                    +
                                                                                </button>
                                                                            </div>

                                                                            <button
                                                                                type="button"
                                                                                onClick={() => removeItem(item.id)}
                                                                                className="text-gray-400 hover:text-[#C41E3A] transition-colors p-2 rounded-full hover:bg-red-50"
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
                                                    ) : (
                                                        <div className="flex flex-col items-center justify-center py-16 text-center">
                                                            <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1} stroke="currentColor" className="w-10 h-10 text-gray-300">
                                                                  <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 5c.07.277-.144.516-.41.488a10.977 10.977 0 0 1-5.26 1.508 10.979 10.979 0 0 1-5.26-1.508c-.266.028-.48-.21-.41-.488l1.263-5a.49.49 0 0 1 .454-.368 18.243 18.243 0 0 0 3.955-.42 18.22 18.22 0 0 0 3.955.42c.174 0 .332.13.454.368Z" />
                                                                </svg>
                                                            </div>
                                                            <h3 className="text-lg font-medium text-gray-900 mb-1">Your cart is empty</h3>
                                                            <p className="text-gray-500 mb-6 max-w-xs mx-auto">Looks like you haven't added anything to your cart yet.</p>
                                                            <button 
                                                                onClick={closeCart}
                                                                className="text-white bg-[#C41E3A] hover:bg-[#a01830] font-bold py-3 px-8 rounded-full shadow-lg shadow-red-100 transition-all transform hover:-translate-y-0.5"
                                                            >
                                                                Start Shopping
                                                            </button>
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        </div>

                                        {cart && cart.items && cart.items.length > 0 && (
                                            <div className="border-t border-gray-200 px-4 py-6 sm:px-6 bg-gray-50">
                                                <div className="flex justify-between text-base font-medium text-gray-900 mb-4">
                                                    <p>Subtotal</p>
                                                    <p className="text-xl font-bold text-[#C41E3A]">${cart.total ? cart.total.toFixed(2) : '0.00'}</p>
                                                </div>
                                                <p className="mt-0.5 text-sm text-gray-500 mb-6">Shipping and taxes calculated at checkout.</p>
                                                <div className="grid gap-3">
                                                    <Link
                                                        href={route('checkout.index')}
                                                        onClick={closeCart}
                                                        className="flex items-center justify-center rounded-md border border-transparent bg-[#C41E3A] px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-[#a01830] transition duration-300"
                                                    >
                                                        Proceed to Checkout
                                                    </Link>
                                                    <Link
                                                        href={route('cart.index')}
                                                        onClick={closeCart}
                                                        className="flex items-center justify-center rounded-md border border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:text-gray-900 transition duration-300"
                                                    >
                                                        View Cart
                                                    </Link>
                                                </div>
                                                <div className="mt-6 flex justify-center text-center text-sm text-gray-500">
                                                    <p>
                                                        or{' '}
                                                        <button
                                                            type="button"
                                                            className="font-medium text-[#C41E3A] hover:text-[#a01830]"
                                                            onClick={closeCart}
                                                        >
                                                            Continue Shopping
                                                            <span aria-hidden="true"> &rarr;</span>
                                                        </button>
                                                    </p>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </div>
            </Dialog>
        </Transition.Root>
    );
}
