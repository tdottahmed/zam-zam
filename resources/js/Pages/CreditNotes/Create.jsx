import { useForm, Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useState, useMemo, useEffect } from 'react';
import axios from 'axios';
import debounce from 'lodash/debounce';

import ProductSearchPanel from './Components/ProductSearchPanel';
import MultiOrderModal from './Components/MultiOrderModal';
import OrderItemsTable from './Components/OrderItemsTable';
import ReturnSummarySidebar from './Components/ReturnSummarySidebar';

export default function Create() {
    // Current locked order
    const [selectedOrder, setSelectedOrder] = useState(null);
    
    // Search state
    const [productSearch, setProductSearch] = useState('');
    const [searchResults, setSearchResults] = useState([]);
    const [isSearching, setIsSearching] = useState(false);
    
    // Modal state
    const [showOrderModal, setShowOrderModal] = useState(false);
    const [modalProduct, setModalProduct] = useState(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        order_id: '',
        reason: 'Damaged / Broken',
        description: '',
        items: [],
    });

    // 1. Search Logic
    const searchProducts = useMemo(() => debounce(async (query, currentOrder, currentFormItems) => {
        if (!query || query.length < 2) {
            setSearchResults([]);
            return;
        }
        setIsSearching(true);
        try {
            if (!currentOrder) {
                // Global search across all past orders
                const response = await axios.get(route('credit-notes.search-items'), { params: { q: query } });
                setSearchResults(response.data);
            } else {
                // Local search restricted to the currently locked order
                const term = query.toLowerCase();
                const matches = currentOrder.items.filter(item => {
                    // Exclude items already added to the return
                    const formItem = currentFormItems.find(i => i.id === item.id);
                    if (formItem?.selected) return false;
                    
                    const nameMatch = item.product_name.toLowerCase().includes(term);
                    const skuMatch = item.product?.product_code && item.product.product_code.toLowerCase().includes(term);
                    return nameMatch || skuMatch;
                });
                setSearchResults(matches);
            }
        } catch (error) {
            console.error("Search failed:", error);
        } finally {
            setIsSearching(false);
        }
    }, 400), []);

    useEffect(() => {
        searchProducts(productSearch, selectedOrder, data.items);
        return () => searchProducts.cancel();
    }, [productSearch, selectedOrder, data.items, searchProducts]);

    // 2. Selection Logic
    const handleProductSelect = async (product) => {
        if (!selectedOrder) {
            // Global selection
            if (product.orders.length === 1) {
                await lockOrder(product.orders[0].order_id, product.product_id);
            } else {
                setModalProduct(product);
                setShowOrderModal(true);
            }
        } else {
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
        }
    };

    const handleModalSelect = async (orderId, productId) => {
        setShowOrderModal(false);
        setTimeout(async () => {
            setModalProduct(null);
            await lockOrder(orderId, productId);
        }, 200);
    };

    const lockOrder = async (orderId, initialProductId) => {
        try {
            // Fetch full order details
            const response = await axios.get(route('credit-notes.order-items', orderId));
            const fullOrder = response.data;
            
            setSelectedOrder(fullOrder);
            
            // Set basic structure for all items, but only mark the initial one as `selected: true`
            setData(data => ({
                ...data,
                order_id: fullOrder.id,
                items: fullOrder.items.map(item => ({
                    id: item.id,
                    selected: item.product_id === initialProductId,
                    quantity: item.product_id === initialProductId ? item.quantity : 0,
                    reason: '', // specific item reason
                }))
            }));
            
            setProductSearch('');
            setSearchResults([]);
        } catch (error) {
            console.error("Failed to load order:", error);
            alert("Failed to load order details. Please try again.");
        }
    };

    const unlockOrder = () => {
        if(confirm("Are you sure you want to change orders? This will clear your current return selections.")) {
            setSelectedOrder(null);
            setProductSearch('');
            setSearchResults([]);
            reset();
        }
    };

    // 3. Form Helpers updating quantity/reason ONLY
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
        const originalItem = selectedOrder?.items.find(i => i.id === selectedItem.id);
        if (!originalItem) return acc;
        return acc + (originalItem.unit_price * selectedItem.quantity);
    }, 0);

    const submit = (e) => {
        e.preventDefault();
        post(route('credit-notes.store'));
    };

    return (
        <AuthenticatedLayout title="Create Return Request">
            
            <MultiOrderModal 
                show={showOrderModal}
                onClose={() => setShowOrderModal(false)}
                product={modalProduct}
                onSelect={handleModalSelect}
            />

            <div className="max-w-8xl mx-auto space-y-6">
                
                <div className="flex items-center justify-between">
                    <div>
                        <div className="flex items-center gap-2 text-sm text-gray-500 mb-2">
                             <Link href={route('credit-notes.index')} className="hover:text-[#C41E3A]">Credit Notes</Link>
                             <span>/</span>
                             <span>Create</span>
                        </div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Create Return Request</h1>
                        {/* Dynamic subtitle based on state */}
                        <p className="text-sm border-l-2 pl-3 mt-2 font-medium transition-colors border-[#C41E3A] text-gray-600 dark:text-gray-400">
                            {!selectedOrder 
                                ? "Step 1: Search for an item you've previously purchased below." 
                                : `Step 2: Adjust quantities and provide reasons for the items returning from Order #${selectedOrder.id}.`
                            }
                        </p>
                    </div>
                </div>

                <form onSubmit={submit} className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {/* Left Column - Product Search & Selected Products Table */}
                    <div className="lg:col-span-8 flex flex-col pt-1">
                        
                        {/* Always show the autocomplete box */}
                        <ProductSearchPanel 
                            productSearch={productSearch}
                            setProductSearch={setProductSearch}
                            isSearching={isSearching}
                            searchResults={searchResults}
                            onSelectProduct={handleProductSelect}
                            selectedOrder={selectedOrder}
                        />

                        {/* Rendering the table ONLY if an order is locked. It filters to show only explicitly selected items */}
                        {selectedOrder && (
                            <OrderItemsTable 
                                selectedOrder={selectedOrder}
                                data={data}
                                updateItem={updateItem}
                                removeItem={removeItem}
                            />
                        )}
                        
                        {errors.items && <div className="mt-4 bg-red-50 text-red-600 p-4 text-sm rounded-xl border border-red-100 dark:bg-red-900/20 dark:border-red-900/30 dark:text-red-400 font-medium shadow-sm">{errors.items}</div>}
                    </div>

                    {/* Right Column - Context & Submission */}
                    <div className="lg:col-span-4">
                        <ReturnSummarySidebar 
                            selectedOrder={selectedOrder}
                            data={data}
                            setData={setData}
                            errors={errors}
                            selectedItemsData={selectedItemsData}
                            totalRefundAmount={totalRefundAmount}
                            processing={processing}
                            unlockOrder={unlockOrder}
                            isEdit={false}
                        />
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
