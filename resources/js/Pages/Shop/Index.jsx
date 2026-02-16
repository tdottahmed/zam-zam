import { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import CustomerLayout from '../../Layouts/CustomerLayout';
import { Head, Link } from '@inertiajs/react';
import ProductCard from '../../Components/Shop/ProductCard';
import ProductFilters from '../../Components/Shop/ProductFilters';
import Breadcrumb from '../../Components/Breadcrumb';

export default function ShopIndex({ products, categories, brands, filters }) {
    const [localProducts, setLocalProducts] = useState(products.data);
    const [nextPage, setNextPage] = useState(products.next_page_url);
    const [loading, setLoading] = useState(false);
    const observerTarget = useRef(null);

    useEffect(() => {
        setLocalProducts(products.data);
        setNextPage(products.next_page_url);
    }, [products]);

    useEffect(() => {
        const observer = new IntersectionObserver(
            entries => {
                if (entries[0].isIntersecting && nextPage && !loading) {
                    loadMore();
                }
            },
            { threshold: 0.1, rootMargin: '100px' }
        );

        if (observerTarget.current) {
            observer.observe(observerTarget.current);
        }

        return () => {
            if (observerTarget.current) {
                observer.unobserve(observerTarget.current);
            }
        };
    }, [nextPage, loading]);

    const loadMore = async () => {
        if (!nextPage || loading) return;
        
        setLoading(true);
        try {
            const response = await axios.get(nextPage, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            // Laravel paginator JSON response has data in localProducts.data
            // response.data is the full paginator object
            setLocalProducts(prev => [...prev, ...response.data.data]);
            setNextPage(response.data.next_page_url);
        } catch (error) {
            console.error("Failed to load more products", error);
        } finally {
            setLoading(false);
        }
    };
    return (
        <CustomerLayout>
            <Head title="Shop" />
            
            {/* Header / Breadcrumb Area */}
            <Breadcrumb 
                title="Shop All Products" 
                links={[
                    { label: 'Shop', active: true }
                ]} 
            />

            <div className="max-w-8xl mx-auto py-12 px-6 lg:px-12">
                <div className="flex flex-col lg:flex-row gap-12">
                    {/* Sidebar Filters */}
                    <aside className="w-full lg:w-80 flex-shrink-0">
                        <ProductFilters categories={categories} brands={brands} filters={filters} />
                    </aside>

                    {/* Main Content */}
                    <div className="flex-1">
                        {/* Results Count */}
                        <div className="flex items-center justify-between mb-6">
                            <p className="text-gray-600">
                                Showing <span className="font-semibold text-gray-900">1</span> to <span className="font-semibold text-gray-900">{localProducts.length}</span> of <span className="font-semibold text-gray-900">{products.total}</span> results
                            </p>
                        </div>

                        {/* Product Grid */}
                        {localProducts.length > 0 ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                {localProducts.map((product) => (
                                    <ProductCard key={product.id} product={product} />
                                ))}
                            </div>
                        ) : (
                             <div className="text-center py-20 bg-gray-50 rounded-lg">
                                <p className="text-gray-500 text-lg">No products found matching your criteria.</p>
                                <Link 
                                    href={route('shop.index')} 
                                    className="inline-block mt-4 text-[#C41E3A] font-semibold hover:underline"
                                >
                                    Clear all filters
                                </Link>
                            </div>
                        )}

                        {/* Infinite Scroll Sentinel & Loading State */}
                        <div ref={observerTarget} className="mt-12 py-4 flex justify-center w-full">
                            {loading && (
                                <div className="flex items-center space-x-2 text-[#C41E3A]">
                                    <svg className="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span className="font-medium">Loading more products...</span>
                                </div>
                            )}
                            {!loading && nextPage === null && products.total > 12 && (
                                <p className="text-gray-400 text-sm">You've reached the end!</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </CustomerLayout>
    );
}
