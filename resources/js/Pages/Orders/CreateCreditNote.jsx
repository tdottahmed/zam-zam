import { useForm, usePage, Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useState } from 'react';

export default function CreateCreditNote({ order }) {
    const { items } = order;

    const { data, setData, post, processing, errors } = useForm({
        reason: '',
        description: '',
        items: items.map(item => ({
            id: item.id,
            selected: false,
            quantity: 1, // Default to 1
        })),
    });

    const handleItemSelection = (index, selected) => {
        const newItems = [...data.items];
        newItems[index].selected = selected;
        setData('items', newItems);
    };

    const handleQuantityChange = (index, quantity) => {
        const maxQty = items[index].quantity;
        const newQty = Math.min(Math.max(1, parseInt(quantity) || 1), maxQty);
        
        const newItems = [...data.items];
        newItems[index].quantity = newQty;
        // Auto-select if quantity is changed? Maybe. Let's keep it explicit.
        if (newQty > 0 && !newItems[index].selected) {
             newItems[index].selected = true;
        }
        setData('items', newItems);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route('credit-notes.store', order.id));
    };

    return (
        <AuthenticatedLayout title={`Support Request for Order #${order.id}`}>
             <div className="max-w-4xl mx-auto space-y-6">
                {/* Header */}
                 <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                            <Link href={route('orders.show', order.id)} className="hover:text-[#C41E3A] transition-colors">Order #{order.id}</Link>
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
                            <span>Return & Support</span>
                        </div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
                            Create Return / Replacement Request
                        </h2>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                           Select the items you wish to return or replace and provide a reason.
                        </p>
                    </div>
                </div>

                <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                    <form onSubmit={submit} className="p-6 space-y-8">
                        
                        {/* Items Selection */}
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">1. Select Items</h3>
                            {errors.items && <p className="text-sm text-red-600 mb-3">{errors.items}</p>}
                            
                            <div className="space-y-4">
                                {items.map((item, index) => (
                                    <div 
                                        key={item.id} 
                                        className={`flex flex-col sm:flex-row items-start sm:items-center p-4 rounded-xl border transition-all ${
                                            data.items[index].selected 
                                                ? 'border-[#C41E3A] bg-red-50/50 dark:bg-red-900/10' 
                                                : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-[#252525]'
                                        }`}
                                    >
                                        <div className="flex items-center h-5">
                                            <input
                                                id={`item-${item.id}`}
                                                type="checkbox"
                                                checked={data.items[index].selected}
                                                onChange={(e) => handleItemSelection(index, e.target.checked)}
                                                className="h-5 w-5 rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A]"
                                            />
                                        </div>
                                        
                                        <div className="ml-4 flex-1 w-full">
                                            <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                                                <div className="flex items-center gap-4">
                                                     <div className="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white">
                                                        {item.product && item.product.image ? (
                                                            <img src={item.product.image} alt={item.product.name} className="h-full w-full object-cover" />
                                                        ) : (
                                                            <div className="h-full w-full bg-gray-100 dark:bg-gray-800" />
                                                        )}
                                                    </div>
                                                    <div>
                                                        <label htmlFor={`item-${item.id}`} className="font-medium text-gray-900 dark:text-white cursor-pointer select-none">
                                                            {item.product_name}
                                                        </label>
                                                        <p className="text-sm text-gray-500 dark:text-gray-400">
                                                            Purchased: {item.quantity} × ${Number(item.unit_price).toFixed(2)}
                                                        </p>
                                                    </div>
                                                </div>

                                                {data.items[index].selected && (
                                                    <div className="flex items-center gap-3">
                                                        <label className="text-sm font-medium text-gray-700 dark:text-gray-300">Return Qty:</label>
                                                        <div className="flex items-center border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-[#1E1E1E] h-10 w-fit">
                                                            <button 
                                                                type="button"
                                                                onClick={() => handleQuantityChange(index, data.items[index].quantity - 1)}
                                                                className="w-10 h-full flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400 transition"
                                                            >
                                                                -
                                                            </button>
                                                            <span className="w-10 text-center text-sm font-bold text-gray-900 dark:text-white">
                                                                {data.items[index].quantity}
                                                            </span>
                                                            <button 
                                                                type="button"
                                                                onClick={() => handleQuantityChange(index, data.items[index].quantity + 1)}
                                                                className="w-10 h-full flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400 transition"
                                                            >
                                                                +
                                                            </button>
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                         {/* Reason & Details */}
                         <div>
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">2. Reason & Details</h3>
                            <div className="grid grid-cols-1 gap-6">
                                <div>
                                    <label htmlFor="reason" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason for Return</label>
                                    <select
                                        id="reason"
                                        value={data.reason}
                                        onChange={e => setData('reason', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#252525] dark:border-gray-700 dark:text-white py-3"
                                    >
                                        <option value="">Select a reason</option>
                                        <option value="Damaged / Broken">Damaged / Broken</option>
                                        <option value="Wrong Item Received">Wrong Item Received</option>
                                        <option value="Item Defective">Item Defective</option>
                                        <option value="No Longer Needed">No Longer Needed</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    {errors.reason && <p className="mt-2 text-sm text-red-600">{errors.reason}</p>}
                                </div>

                                <div>
                                    <label htmlFor="description" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Details</label>
                                    <textarea
                                        id="description"
                                        rows={4}
                                        value={data.description}
                                        onChange={e => setData('description', e.target.value)}
                                        placeholder="Please provide more details about the issue..."
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#252525] dark:border-gray-700 dark:text-white p-3"
                                    />
                                    {errors.description && <p className="mt-2 text-sm text-red-600">{errors.description}</p>}
                                </div>
                            </div>
                        </div>

                        {/* Submit Button */}
                        <div className="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] shadow-lg shadow-red-100 dark:shadow-none disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:-translate-y-0.5"
                            >
                                {processing ? 'Submitting Request...' : 'Submit Request'}
                            </button>
                        </div>
                    </form>
                </div>
             </div>
        </AuthenticatedLayout>
    );
}
