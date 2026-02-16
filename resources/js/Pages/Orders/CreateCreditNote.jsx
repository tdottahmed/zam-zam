import { useForm, usePage, Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useState, useMemo } from 'react';
import StorageImage from '@/Components/StorageImage';

export default function CreateCreditNote({ order }) {
    const { items: orderItems } = order;
    
    // State for filtering
    const [searchTerm, setSearchTerm] = useState('');
    const [filterStatus, setFilterStatus] = useState('all');

    const { data, setData, post, processing, errors } = useForm({
        reason: 'Damaged / Broken', // Default reason
        description: '',
        items: orderItems.map(item => ({
            id: item.id,
            selected: false,
            quantity: item.quantity, // Default to full quantity, user reduces if needed
            reason: '', // specific reason
        })),
    });

    // Update item data helper
    const updateItem = (id, field, value) => {
        const newItems = data.items.map(item => {
            if (item.id === id) {
                // If quantity changes greater than 0, auto select
                let updates = { [field]: value };
                if (field === 'quantity' && value > 0 && !item.selected) {
                    updates.selected = true;
                }
                return { ...item, ...updates };
            }
            return item;
        });
        setData('items', newItems);
    };

    const toggleSelection = (id) => {
        const newItems = data.items.map(item => 
            item.id === id ? { ...item, selected: !item.selected } : item
        );
        setData('items', newItems);
    };

    const toggleSelectAll = (filteredIds) => {
        const allSelected = filteredIds.every(id => data.items.find(i => i.id === id).selected);
        const newItems = data.items.map(item => {
            if (filteredIds.includes(item.id)) {
                return { ...item, selected: !allSelected };
            }
            return item;
        });
        setData('items', newItems);
    };

    // Derived state for display
    const filteredItems = useMemo(() => {
        return orderItems.filter(item => {
            const matchesSearch = item.product_name.toLowerCase().includes(searchTerm.toLowerCase()) || 
                                  item.product.sku?.toLowerCase().includes(searchTerm.toLowerCase());
            return matchesSearch;
        });
    }, [orderItems, searchTerm]);

    const filteredItemIds = filteredItems.map(i => i.id);
    const isAllSelected = filteredItemIds.length > 0 && filteredItemIds.every(id => data.items.find(i => i.id === id).selected);

    // Calculate Totals
    const selectedItemsData = data.items.filter(i => i.selected);
    const totalRefundAmount = selectedItemsData.reduce((acc, selectedItem) => {
        const originalItem = orderItems.find(i => i.id === selectedItem.id);
        return acc + (originalItem.unit_price * selectedItem.quantity);
    }, 0);

    const submit = (e) => {
        e.preventDefault();
        post(route('credit-notes.store', order.id));
    };

    return (
        <AuthenticatedLayout title={`Return Items - Order #${order.id}`}>
            <div className="max-w-8xl mx-auto space-y-6">
                
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-2 text-sm text-gray-500 mb-2">
                             <Link href={route('orders.show', order.id)} className="hover:text-[#C41E3A]">Order #{order.id}</Link>
                             <span>/</span>
                             <span>Returns</span>
                        </div>
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Create Credit Note</h1>
                        <p className="text-gray-500 dark:text-gray-400 mt-1">Select items to return from Order #{order.id}</p>
                    </div>
                    
                    {/* Sticky Summary Card (Large Screens) */}
                    <div className="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 min-w-[300px]">
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-gray-500">Selected Items:</span>
                            <span className="font-medium">{selectedItemsData.length}</span>
                        </div>
                        <div className="flex justify-between items-center text-lg font-bold text-gray-900 dark:text-white">
                            <span>Estimated Refund:</span>
                            <span className="text-[#C41E3A]">${totalRefundAmount.toFixed(2)}</span>
                        </div>
                    </div>
                </div>

                <form onSubmit={submit} className="space-y-6">
                    
                    {/* Controls Bar */}
                    <div className="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row gap-4 justify-between items-center sticky top-0 z-10">
                        <div className="relative w-full sm:w-96">
                            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                placeholder="Search products by name or SKU..."
                                value={searchTerm}
                                onChange={e => setSearchTerm(e.target.value)}
                                className="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                            />
                        </div>
                        <div className="flex items-center gap-3 w-full sm:w-auto">
                            <button
                                type="button"
                                onClick={() => toggleSelectAll(filteredItemIds)}
                                className="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-[#C41E3A]"
                            >
                                {isAllSelected ? 'Deselect Pages' : 'Select All on Page'}
                            </button>
                        </div>
                    </div>

                    {/* Data Table */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead className="bg-gray-50 dark:bg-gray-900/50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3 text-left">
                                            <input
                                                type="checkbox"
                                                checked={isAllSelected}
                                                onChange={() => toggleSelectAll(filteredItemIds)}
                                                className="rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] h-5 w-5"
                                            />
                                        </th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchased</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Qty</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refunding</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    {filteredItems.map((item) => {
                                        const formItem = data.items.find(i => i.id === item.id);
                                        return (
                                            <tr key={item.id} className={formItem.selected ? 'bg-red-50/30 dark:bg-red-900/10' : ''}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <input
                                                        type="checkbox"
                                                        checked={formItem.selected}
                                                        onChange={() => toggleSelection(item.id)}
                                                        className="rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] h-5 w-5"
                                                    />
                                                </td>
                                                <td className="px-6 py-4">
                                                    <div className="flex items-center">
                                                        <div className="h-10 w-10 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden mr-4">
                                                            {item.product?.image && (
                                                                <StorageImage src={item.product.image} alt={item.product.name} className="h-full w-full object-cover" />
                                                            )}
                                                        </div>
                                                        <div>
                                                            <div className="text-sm font-medium text-gray-900 dark:text-white line-clamp-1 max-w-xs" title={item.product_name}>
                                                                {item.product_name}
                                                            </div>
                                                            <div className="text-xs text-gray-500">{item.variant_name || 'Default'}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    ${Number(item.unit_price).toFixed(2)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {item.quantity}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="w-24">
                                                        <input
                                                            type="number"
                                                            min="0"
                                                            max={item.quantity}
                                                            value={formItem.quantity}
                                                            onChange={(e) => updateItem(item.id, 'quantity', parseInt(e.target.value) || 0)}
                                                            className={`block w-full rounded-md shadow-sm sm:text-sm focus:ring-[#C41E3A] focus:border-[#C41E3A] ${
                                                                formItem.quantity > item.quantity ? 'border-red-300 text-red-900' : 'border-gray-300 dark:border-gray-600 dark:bg-gray-700'
                                                            }`}
                                                        />
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    ${(item.unit_price * formItem.quantity).toFixed(2)}
                                                </td>
                                            </tr>
                                        );
                                    })}
                                    {filteredItems.length === 0 && (
                                        <tr>
                                            <td colSpan="6" className="px-6 py-10 text-center text-gray-500">
                                                No items found matching "{searchTerm}"
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Footer / Reason */}
                    <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                             <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Main Reason for Return</label>
                             <select
                                value={data.reason}
                                onChange={e => setData('reason', e.target.value)}
                                className="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                             >
                                <option value="Damaged / Broken">Damaged / Broken</option>
                                <option value="Wrong Item Received">Wrong Item Received</option>
                                <option value="Item Defective">Item Defective</option>
                                <option value="no_longer_needed">No longer needed</option>
                                <option value="other">Other</option>
                             </select>
                             {errors.reason && <p className="mt-1 text-sm text-red-600">{errors.reason}</p>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Notes</label>
                            <textarea
                                rows={3}
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                className="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                placeholder="Any specific details..."
                            />
                        </div>
                    </div>

                    {/* Actions */}
                    <div className="flex justify-end gap-3 pb-10">
                         <Link
                            href={route('orders.show', order.id)}
                            className="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            disabled={processing || selectedItemsData.length === 0}
                            className="px-8 py-3 bg-[#C41E3A] text-white rounded-xl font-bold shadow-lg hover:bg-[#a01830] disabled:opacity-50 disabled:cursor-not-allowed transition transform active:scale-95"
                        >
                            Submit Credit Note
                        </button>
                    </div>

                </form>
            </div>
        </AuthenticatedLayout>
    );
}
