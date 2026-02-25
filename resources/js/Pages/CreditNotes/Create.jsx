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
    // Search state
    const [productSearch, setProductSearch] = useState('');
    const [searchResults, setSearchResults] = useState([]);
    const [isSearching, setIsSearching] = useState(false);
    
    // Modal state
    const [showOrderModal, setShowOrderModal] = useState(false);
    const [modalProduct, setModalProduct] = useState(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        reason: 'Damaged / Broken',
        description: '',
        items: [],
    });

    // 1. Search Logic
    const searchProducts = useMemo(() => debounce(async (query) => {
        if (!query || query.length < 2) {
            setSearchResults([]);
            return;
        }
        setIsSearching(true);
        try {
            // Always global search across all past orders
            const response = await axios.get(route('credit-notes.search-items'), { params: { q: query } });
            
            // Filter out items already perfectly matching what's selected
            const matches = response.data.filter(product => {
                // If every single order of this product is already in the items list, hide it.
                return !product.orders.every(orderInfo => 
                    data.items.some(formItem => formItem.id === orderInfo.order_item_id && formItem.selected)
                );
            });
            
            setSearchResults(matches);
        } catch (error) {
            console.error("Search failed:", error);
        } finally {
            setIsSearching(false);
        }
    }, 400), [data.items]);

    useEffect(() => {
        searchProducts(productSearch);
        return () => searchProducts.cancel();
    }, [productSearch, searchProducts]);

    // 2. Selection Logic
    const handleProductSelect = (product) => {
        // Find which orders of this product are NOT already selected
        const availableOrders = product.orders.filter(orderInfo => 
             !data.items.some(formItem => formItem.id === orderInfo.order_item_id && formItem.selected)
        );

        if (availableOrders.length === 1) {
            addItem(product, availableOrders[0]);
        } else {
            setModalProduct({ ...product, orders: availableOrders });
            setShowOrderModal(true);
        }
    };

    const handleModalSelect = (orderId, productId) => {
        setShowOrderModal(false);
        const orderInfo = modalProduct.orders.find(o => o.order_id === orderId);
        if (orderInfo) {
            addItem(modalProduct, orderInfo);
        }
        setTimeout(() => setModalProduct(null), 200);
    };

    const addItem = (product, orderInfo) => {
        const existingIndex = data.items.findIndex(i => i.id === orderInfo.order_item_id);
        
        let newItems = [...data.items];
        if (existingIndex >= 0) {
            newItems[existingIndex].selected = true;
            newItems[existingIndex].quantity = 1;
        } else {
            newItems.push({
                // Backend expects order_items.id for "id"
                id: orderInfo.order_item_id, 
                order_id: orderInfo.order_id,
                order_date: orderInfo.order_date,
                product_id: product.product_id,
                product_name: product.product_name,
                product_code: product.product_code,
                image: product.image,
                unit_price: orderInfo.unit_price,
                max_quantity: orderInfo.quantity_bought,
                
                // Form fields
                selected: true,
                quantity: 1, 
                reason: '',
            });
        }
        
        setData('items', newItems);
        setProductSearch('');
        setSearchResults([]);
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
        return acc + (selectedItem.unit_price * selectedItem.quantity);
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
                        <p className="text-sm border-l-2 pl-3 mt-2 font-medium border-[#C41E3A] text-gray-600 dark:text-gray-400">
                            Search and select items from any of your past orders below.
                        </p>
                    </div>
                </div>

                <form onSubmit={submit} className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {/* Left Column - Product Search & Selected Products Table */}
                    <div className="lg:col-span-8 flex flex-col pt-1">
                        
                        <ProductSearchPanel 
                            productSearch={productSearch}
                            setProductSearch={setProductSearch}
                            isSearching={isSearching}
                            searchResults={searchResults}
                            onSelectProduct={handleProductSelect}
                        />

                        <OrderItemsTable 
                            data={data}
                            updateItem={updateItem}
                            removeItem={removeItem}
                        />
                        
                        {errors.items && <div className="mt-4 bg-red-50 text-red-600 p-4 text-sm rounded-xl border border-red-100 dark:bg-red-900/20 dark:border-red-900/30 dark:text-red-400 font-medium shadow-sm">{errors.items}</div>}
                    </div>

                    {/* Right Column - Context & Submission */}
                    <div className="lg:col-span-4">
                        <ReturnSummarySidebar 
                            data={data}
                            setData={setData}
                            errors={errors}
                            selectedItemsData={selectedItemsData}
                            totalRefundAmount={totalRefundAmount}
                            processing={processing}
                            isEdit={false}
                        />
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
