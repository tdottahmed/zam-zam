
import React from 'react';
import CustomerLayout from '../../Layouts/CustomerLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import StorageImage from '../../Components/StorageImage';

export default function Show({ product }) {
    const { data, setData, post, processing, errors, recentlySuccessful } = useForm({
        product_id: product.id,
        quantity: 1,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('cart.add'), {
            preserveScroll: true,
            onSuccess: () => {
                // Optional: show toast or notification (handled by layout usually)
            }
        });
    };

    // Calculate total price based on quantity
    const totalPrice = (product.unit_price * data.quantity).toFixed(2);

    return (
        <CustomerLayout>
            <Head title={product.name} />
            
            {/* Breadcrumb */}
            <div className="bg-gray-50 py-6 px-6 lg:px-12 border-b border-gray-100">
                <div className="max-w-7xl mx-auto">
                    <div className="text-sm text-gray-500">
                        <Link href="/" className="hover:text-[#C41E3A]">Home</Link>
                        <span className="mx-2">/</span>
                        <Link href={route('shop.index')} className="hover:text-[#C41E3A]">Shop</Link>
                        {product.category && (
                            <>
                                <span className="mx-2">/</span>
                                <span className="text-gray-900">{product.category.name}</span>
                            </>
                        )}
                        <span className="mx-2">/</span>
                        <span className="text-gray-900 font-medium truncate max-w-[200px] inline-block align-bottom">{product.name}</span>
                    </div>
                </div>
            </div>

            <div className="max-w-7xl mx-auto py-12 px-6 lg:px-12">
                <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-0 lg:gap-12">
                        {/* Product Image */}
                        <div className="p-8 bg-gray-50 flex items-center justify-center min-h-[400px] lg:min-h-[600px]">
                             <div className="w-full max-w-md aspect-square bg-white p-4 rounded-lg shadow-sm">
                                <StorageImage
                                    path={product.image}
                                    name={product.name}
                                    className="w-full h-full object-contain"
                                />
                             </div>
                        </div>

                        {/* Product Details */}
                        <div className="p-8 lg:py-12 lg:pr-12 flex flex-col">
                             {product.brand && (
                                <div className="mb-2">
                                    <span className="text-sm font-bold text-[#C41E3A] uppercase tracking-wider bg-red-50 px-3 py-1 rounded-full">
                                        {product.brand.name}
                                    </span>
                                </div>
                            )}
                            
                            <h1 className="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                                {product.name}
                            </h1>

                            <div className="flex items-center gap-4 mb-6">
                                <span className="text-3xl font-extrabold text-[#C41E3A]">
                                    ${Number(product.unit_price).toFixed(2)}
                                </span>
                                {product.unit && (
                                    <span className="text-gray-500 text-lg">
                                        / per {product.unit.name}
                                    </span>
                                )}
                            </div>

                             <div className="prose prose-sm text-gray-600 mb-8 border-t border-b border-gray-100 py-6">
                                <h3 className="text-gray-900 font-semibold mb-2">Details</h3>
                                <p>{product.notes || 'No specific details available for this product.'}</p>
                                <div className="mt-4 grid grid-cols-2 gap-4">
                                     <div>
                                        <span className="text-gray-500 block text-xs uppercase tracking-wider">Product Code</span>
                                        <span className="font-medium text-gray-900">{product.product_code || 'N/A'}</span>
                                     </div>
                                     <div>
                                        <span className="text-gray-500 block text-xs uppercase tracking-wider">Unit Value</span>
                                        <span className="font-medium text-gray-900">{product.unit_value} {product.unit?.name}</span>
                                     </div>
                                </div>
                            </div>

                            <div className="mt-auto">
                                <form onSubmit={submit} className="flex flex-col gap-4">
                                    <div className="flex flex-col sm:flex-row gap-4 items-end sm:items-center">
                                         <div className="w-full sm:w-32">
                                            <label htmlFor="quantity" className="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                            <div className="relative">
                                                <input
                                                    id="quantity"
                                                    type="number"
                                                    min="1"
                                                    value={data.quantity}
                                                    onChange={e => setData('quantity', parseInt(e.target.value))}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-20 text-center text-lg font-semibold"
                                                />
                                            </div>
                                         </div>
                                         
                                         <div className="flex-1 w-full">
                                             <label className="block text-sm font-medium text-gray-700 mb-1 invisible hidden sm:block">Action</label>
                                            <button
                                                type="submit"
                                                disabled={processing}
                                                className="w-full bg-[#C41E3A] text-white px-8 py-3 rounded-md font-bold text-lg hover:bg-[#a01830] transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                                            >
                                                <span>Add to Cart</span>
                                                {totalPrice > 0 && <span className="text-red-100 font-normal text-base">(${totalPrice})</span>}
                                            </button>
                                         </div>
                                    </div>
                                    {errors.quantity && <div className="text-red-600 text-sm mt-1">{errors.quantity}</div>}
                                    {errors.product_id && <div className="text-red-600 text-sm mt-1">{errors.product_id}</div>}
                                    
                                    {recentlySuccessful && (
                                        <div className="p-4 bg-green-50 text-green-700 rounded-md mt-2 flex items-center animate-pulse">
                                            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
                                            Successfully added to cart!
                                        </div>
                                    )}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </CustomerLayout>
    );
}
