import React from 'react';

export default function ReturnSummarySidebar({
    data,
    setData,
    errors,
    selectedItemsData,
    totalRefundAmount,
    processing,
    isEdit = false
}) {
    return (
        <div className="space-y-6">
            
            {/* Context/Notes Box */}
            <div className="bg-white dark:bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 transition-all">
                <div className="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 className="font-semibold text-gray-900 dark:text-white">Return Details</h3>
                </div>
                <div className="p-6 space-y-4">
                    <div>
                        <div className="w-full px-4 py-3 border border-[#C41E3A]/20 bg-red-50/30 dark:border-[#C41E3A]/10 dark:bg-red-900/10 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                            <span className="font-semibold text-[#C41E3A]">Multiple Orders Supported:</span> You can search and add products from any of your past purchases into a single return request.
                        </div>
                        {errors.order_id && <span className="text-red-500 text-xs mt-1 block">{errors.order_id}</span>}
                    </div>

                    <div>
                         <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">General Context (Optional)</label>
                         <select
                            value={data.reason}
                            onChange={e => setData('reason', e.target.value)}
                            className="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50"
                         >
                            <option value="Damaged / Broken">Damaged / Broken</option>
                            <option value="Wrong Item Received">Wrong Item Received</option>
                            <option value="Item Defective">Item Defective</option>
                            <option value="no_longer_needed">No longer needed</option>
                            <option value="other">Other</option>
                         </select>
                         <p className="text-xs text-gray-400 mt-1">This sets the overall tone, but be sure to select specific reasons for each item in the table.</p>
                         {errors.reason && <p className="mt-1 text-sm text-red-600 block">{errors.reason}</p>}
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional Notes</label>
                        <textarea
                            rows={3}
                            value={data.description}
                            onChange={e => setData('description', e.target.value)}
                            className="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-50"
                            placeholder="Any specific details..."
                        />
                    </div>
                </div>
            </div>

            {/* Summary Box */}
            <div className="bg-white dark:bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
                <div className="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 rounded-t-xl">
                    <h3 className="font-semibold text-gray-900 dark:text-white">Summary</h3>
                </div>
                <div className="p-6 space-y-3">
                    <div className="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <span>Selected Items:</span>
                        <span className="font-medium bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full text-gray-800 dark:text-gray-200">{selectedItemsData.length}</span>
                    </div>

                    <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>Subtotal</span>
                        <span className="font-medium text-gray-900 dark:text-white">${totalRefundAmount.toFixed(2)}</span>
                    </div>

                    <div className="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-3 mt-2">
                        <span className="text-gray-900 dark:text-white">Total Refund</span>
                        <span className="text-[#C41E3A]">${totalRefundAmount.toFixed(2)}</span>
                    </div>
                    
                    <button
                        type="submit"
                        disabled={processing || selectedItemsData.length === 0}
                        className="w-full mt-6 py-3 px-4 rounded-lg shadow-sm font-bold text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] transition-all disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95"
                    >
                        {isEdit ? 'Update Request' : 'Submit Request'}
                    </button>
                    <p className="text-xs text-center text-gray-400 mt-2">
                        {isEdit ? 'Updates this draft request for review.' : 'This will create a draft request for review.'}
                    </p>
                </div>
            </div>

        </div>
    );
}
