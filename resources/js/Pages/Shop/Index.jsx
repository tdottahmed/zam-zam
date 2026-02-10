import CustomerLayout from '../../Layouts/CustomerLayout';
import { Head, Link } from '@inertiajs/react';
import ProductCard from '../../Components/Shop/ProductCard';
import ProductFilters from '../../Components/Shop/ProductFilters';

export default function ShopIndex({ products, categories, brands, filters }) {
    return (
        <CustomerLayout>
            <Head title="Shop" />
            
            {/* Header / Breadcrumb Area */}
            <div className="bg-gray-50 py-8 px-6 lg:px-12 border-b border-gray-100">
                <div className="max-w-8xl mx-auto">
                     <h1 className="text-3xl font-bold text-gray-900 mb-2">Shop All Products</h1>
                     <div className="text-sm text-gray-500">
                        <Link href="/" className="hover:text-[#C41E3A]">Home</Link>
                        <span className="mx-2">/</span>
                        <span className="text-gray-900">Shop</span>
                     </div>
                </div>
            </div>

            <div className="max-w-8xl mx-auto py-12 px-6 lg:px-12">
                <div className="flex flex-col lg:flex-row gap-12">
                    {/* Sidebar Filters */}
                    <aside className="w-full lg:w-64 flex-shrink-0">
                        <ProductFilters categories={categories} brands={brands} filters={filters} />
                    </aside>

                    {/* Main Content */}
                    <div className="flex-1">
                        {/* Results Count & Sorting (Sorting placeholder for now) */}
                        <div className="flex items-center justify-between mb-6">
                            <p className="text-gray-600">
                                Showing <span className="font-semibold text-gray-900">{products.from || 0}</span> to <span className="font-semibold text-gray-900">{products.to || 0}</span> of <span className="font-semibold text-gray-900">{products.total}</span> results
                            </p>
                            {/* Sorting could go here */}
                        </div>

                        {/* Product Grid */}
                        {products.data.length > 0 ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                {products.data.map((product) => (
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

                        {/* Pagination */}
                        {products.links && products.links.length > 3 && (
                            <div className="mt-12 flex justify-center">
                                <div className="flex gap-2">
                                    {products.links.map((link, key) => (
                                        link.url === null ? (
                                            <span key={key} className="px-4 py-2 text-gray-400 border border-gray-200 rounded text-sm" dangerouslySetInnerHTML={{ __html: link.label }} />
                                        ) : (
                                            <Link
                                                key={key}
                                                href={link.url}
                                                className={`px-4 py-2 border rounded text-sm transition ${link.active ? 'bg-[#C41E3A] text-white border-[#C41E3A]' : 'border-gray-200 text-gray-700 hover:border-[#C41E3A] hover:text-[#C41E3A]'}`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                            />
                                        )
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </CustomerLayout>
    );
}
