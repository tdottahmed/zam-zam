import { useForm, Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useState, useMemo, useEffect } from 'react';
import debounce from 'lodash/debounce';

import ProductSearchPanel from './Components/ProductSearchPanel';
import OrderItemsTable from './Components/OrderItemsTable';
import ReturnSummarySidebar from './Components/ReturnSummarySidebar';

export default function Edit({ creditNote }) {
    const { order, items: initialItems } = creditNote;
    
    const [productSearch, setProductSearch] = useState('');
    const [searchResults, setSearchResults] = useState([]);

    const { data, setData, put, processing, errors } = useForm({
        reason: creditNote.reason || 'Damaged / Broken',
        description: creditNote.admin_notes || '',
        items: order.items.map(oItem => {
            const returnedItem = initialItems.find(i => i.order_item_id === oItem.id);
            return {
                id: oItem.id,
                selected: !!returnedItem, // Explicitly loaded from the draft
                quantity: returnedItem ? returnedItem.credit_quantity : 0,
                reason: returnedItem ? returnedItem.reason : '',
            };
        }),
    });

    // 1. Local Search Logic (Restricted to items already in the locked order)
    const searchProducts = useMemo(() => debounce((query, currentItemsData) => {
        if (!query || query.length < 2) {
            setSearchResults([]);
            return;
        }
        
        const term = query.toLowerCase();
        const matches = order.items.filter(item => {
            // Exclude items already added to the return
            const formItem = currentItemsData.find(i => i.id === item.id);
            if (formItem?.selected) return false;
            
            const nameMatch = item.product_name.toLowerCase().includes(term);
            const skuMatch = item.product?.product_code && item.product.product_code.toLowerCase().includes(term);
            return nameMatch || skuMatch;
        });
        setSearchResults(matches);
    }, 300), [order]);

    useEffect(() => {
        searchProducts(productSearch, data.items);
        return () => searchProducts.cancel();
    }, [productSearch, data.items, searchProducts]);

    const handleProductSelect = (product) => {
        // Local selection - adding a product from the locked order
        const newItems = data.items.map(item => {
            if (item.id === product.id) {
                return { ...item, selected: true, quantity: item.quantity === 0 ? product.quantity : item.quantity };
            }
            return item;
        });
        setData('items', newItems);
        setProductSearch('');
        setSearchResults([]);
    };

    // 2. Form Helpers
    const updateItem = (id, field, value) => {
        const newItems = data.items.map(item => {
            if (item.id === id) {
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

    const removeItem = (id) => {
        const newItems = data.items.map(item => {
            if (item.id === id) {
                return { ...item, selected: false, quantity: 0, reason: '' };
            }
            return item;
        });
        setData('items', newItems);
    };

    const selectedItemsData = data.items.filter(i => i.selected);
    const totalRefundAmount = selectedItemsData.reduce((acc, selectedItem) => {
        const originalItem = order.items.find(i => i.id === selectedItem.id);
        if (!originalItem) return acc;
        return acc + (originalItem.unit_price * selectedItem.quantity);
    }, 0);

    const submit = (e) => {
        e.preventDefault();
        put(route('credit-notes.update', creditNote.id));
    };

    return (
        <AuthenticatedLayout title={`Edit Return Request ${creditNote.credit_note_number}`}>
            <div className="max-w-8xl mx-auto space-y-6">
                
                <div className="flex items-center justify-between">
                    <div>
                        <div className="flex items-center gap-2 text-sm text-gray-500 mb-2">
                             <Link href={route('credit-notes.index')} className="hover:text-[#C41E3A]">Credit Notes</Link>
                             <span>/</span>
                             <span>{creditNote.credit_note_number}</span>
                             <span>/</span>
                             <span>Edit</span>
                        </div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Edit Return Request</h1>
                        <p className="text-sm border-l-2 pl-3 mt-2 font-medium transition-colors border-[#C41E3A] text-gray-600 dark:text-gray-400">
                            Updating draft return specifically for Order #{order.id}.
                        </p>
                    </div>
                </div>

                <form onSubmit={submit} className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {/* Left Column - Product Selection */}
                    <div className="lg:col-span-8 flex flex-col pt-1">
                        
                        {/* Autocomplete box to add any missing items from the same order */}
                        <ProductSearchPanel 
                            productSearch={productSearch}
                            setProductSearch={setProductSearch}
                            isSearching={false}
                            searchResults={searchResults}
                            onSelectProduct={handleProductSelect}
                            selectedOrder={order}
                        />

                        {/* Rendering the table to show only explicitly selected items */}
                        <OrderItemsTable 
                            selectedOrder={order}
                            data={data}
                            updateItem={updateItem}
                            removeItem={removeItem}
                        />
                        
                        {errors.items && <div className="mt-4 bg-red-50 text-red-600 p-4 text-sm rounded-xl border border-red-100 dark:bg-red-900/20 dark:border-red-900/30 dark:text-red-400 font-medium shadow-sm">{errors.items}</div>}

                    </div>

                    {/* Right Column - Context & Summary */}
                    <div className="lg:col-span-4">
                        <ReturnSummarySidebar 
                            selectedOrder={order}
                            data={data}
                            setData={setData}
                            errors={errors}
                            selectedItemsData={selectedItemsData}
                            totalRefundAmount={totalRefundAmount}
                            processing={processing}
                            unlockOrder={null} // Cannot turn off an order when explicitly editing a draft tied to it
                            isEdit={true}
                        />
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
