import React from 'react';
import StorageImage from '@/Components/StorageImage';

export default function OrderItemsTable({
    selectedOrder,
    data, 
    updateItem,
    removeItem
}) {
    // Only display items that the user specifically selected via the autocomplete
    const displayItems = selectedOrder.items.filter(item => {
        return data.items.find(i => i.id === item.id)?.selected === true;
    });

    if (displayItems.length === 0) {
        return (
            <div className="text-center py-12 px-4 bg-white dark:bg-[#1E1E1E] rounded-xl border border-dashed border-gray-300 dark:border-gray-700 shadow-sm mt-6">
                <div className="mx-auto w-12 h-12 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                    <svg className="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 className="text-sm font-medium text-gray-900 dark:text-white">No items added yet</h3>
                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">Search above and select items from this order to add them to your return request.</p>
            </div>
        );
    }

    return (
        <div className="bg-white dark:bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 mt-6 overflow-hidden">
            <div className="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50/50 dark:bg-gray-800/20 flex justify-between items-center">
                <h3 className="font-semibold text-gray-900 dark:text-white">
                    Items to Return <span className="text-gray-400 font-normal ml-1">(Order #{selectedOrder.id})</span>
                </h3>
                <span className="bg-red-50 text-[#C41E3A] dark:bg-red-900/20 text-xs font-bold px-2.5 py-1 rounded-full">{displayItems.length} selected</span>
            </div>

            <div className="overflow-x-auto min-h-[150px]">
                <table className="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead className="bg-white text-xs uppercase text-gray-500 dark:bg-[#1E1E1E] border-b border-gray-200 dark:border-gray-700 shadow-sm">
                        <tr>
                            <th className="px-5 py-3 min-w-[200px] font-semibold">Product</th>
                            <th className="px-4 py-3 text-center w-24 font-semibold">Purchased</th>
                            <th className="px-4 py-3 text-center w-32 font-semibold">Return Qty</th>
                            <th className="px-4 py-3 text-left w-56 font-semibold">Return Reason</th>
                            <th className="px-4 py-3 text-right w-24 font-semibold">Refund</th>
                            <th className="px-4 py-3 text-center w-12"></th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100 dark:divide-gray-800">
                        {displayItems.map((item) => {
                            const formItem = data.items.find(i => i.id === item.id);

                            return (
                                <tr key={item.id} className="bg-white dark:bg-[#1E1E1E] hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors group">
                                    <td className="px-5 py-4 align-middle">
                                        <div className="flex items-center">
                                            <div className="h-12 w-12 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden mr-3 border border-gray-200 dark:border-gray-600 shadow-sm">
                                                {item.product?.image && <StorageImage path={item.product.image} name={item.product.name} className="h-full w-full object-cover" />}
                                            </div>
                                            <div>
                                                <div className="font-bold text-gray-900 dark:text-white line-clamp-1">{item.product_name}</div>
                                                <div className="flex items-center gap-2 mt-0.5">
                                                    <span className="text-xs text-gray-500 font-mono">{item.product?.product_code || '---'}</span>
                                                    <span className="text-xs font-medium text-gray-400">${Number(item.unit_price).toFixed(2)}/ea</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-4 py-4 align-middle text-center font-semibold text-gray-700 dark:text-gray-300">
                                        {item.quantity}
                                    </td>
                                    <td className="px-4 py-4 align-middle">
                                        <div className="flex items-center justify-center">
                                            <div className="relative flex items-center">
                                                <input 
                                                    type="number"
                                                    min="1"
                                                    max={item.quantity}
                                                    value={formItem.quantity}
                                                    onChange={(e) => updateItem(item.id, 'quantity', parseInt(e.target.value) || 0)}
                                                    className={`w-20 text-center font-bold rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 py-1.5 focus:ring-[#C41E3A] focus:border-[#C41E3A] transition-colors ${
                                                        formItem.quantity > item.quantity ? 'border-red-300 text-red-900 bg-red-50 focus:ring-red-500' : 'text-gray-900 dark:text-white bg-white'
                                                    }`}
                                                />
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-4 py-4 align-middle">
                                        <select
                                            value={formItem.reason || ''}
                                            onChange={(e) => updateItem(item.id, 'reason', e.target.value)}
                                            className="w-full rounded-lg border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 py-1.5 focus:ring-[#C41E3A] focus:border-[#C41E3A] text-sm text-gray-900 dark:text-gray-100 transition-colors"
                                        >
                                            <option value="">Select Reason...</option>
                                            <option value="Damaged / Broken">Damaged / Broken</option>
                                            <option value="Wrong Item Received">Wrong Item Received</option>
                                            <option value="Item Defective">Item Defective</option>
                                            <option value="no_longer_needed">No longer needed</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </td>
                                    <td className="px-4 py-4 align-middle text-right font-extrabold text-gray-900 dark:text-white">
                                        ${(item.unit_price * formItem.quantity).toFixed(2)}
                                    </td>
                                    <td className="px-4 py-4 align-middle text-center">
                                        <button 
                                            type="button" 
                                            onClick={() => removeItem(item.id)}
                                            className="text-gray-400 hover:text-[#C41E3A] hover:bg-red-50 dark:hover:bg-red-900/30 p-2 rounded-lg transition-all opacity-0 group-hover:opacity-100 focus:opacity-100"
                                            title="Remove from return"
                                        >
                                            <svg className="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
