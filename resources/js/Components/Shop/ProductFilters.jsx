import { useState } from 'react';
import { router } from '@inertiajs/react';

export default function ProductFilters({ categories, brands, filters }) {
    const [search, setSearch] = useState(filters.search || '');
    const [minPrice, setMinPrice] = useState(filters.min_price || '');
    const [maxPrice, setMaxPrice] = useState(filters.max_price || '');

    // Debounce search could be added here, for now using direct submit or enter key would be typical
    // But for a sidebar filter, usually we apply on change or have an apply button. 
    // Let's go with "Apply Filters" button for price, and links for cats/brands.

    const handleSearch = (e) => {
        e.preventDefault();
        applyFilters({ search });
    };

    const handlePriceFilter = (e) => {
        e.preventDefault();
        applyFilters({ min_price: minPrice, max_price: maxPrice });
    };

    const applyFilters = (newFilters) => {
        const query = { ...filters, ...newFilters };
        
        // Cleanup empty values
        Object.keys(query).forEach(key => {
            if (query[key] === '' || query[key] === null || query[key] === undefined) {
                delete query[key];
            }
        });

        // Reset pagination
        delete query.page;

        router.get(route('shop.index'), query, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const isCategoryActive = (slug) => filters.category === slug;
    const isBrandActive = (slug) => filters.brand === slug;

    return (
        <div className="space-y-8">
            {/* Search */}
            <div>
                <h3 className="font-bold text-gray-900 mb-4">Search</h3>
                <form onSubmit={handleSearch}>
                    <div className="relative">
                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Search products..."
                            className="w-full pl-4 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                        />
                        <button type="submit" className="absolute right-2 top-2 text-gray-400 hover:text-[#C41E3A]">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                              <path strokeLinecap="round" strokeLinejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {/* Categories */}
            <div>
                <h3 className="font-bold text-gray-900 mb-4">Categories</h3>
                <div className="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                     <button 
                        onClick={() => applyFilters({ category: null })}
                        className={`block text-sm w-full text-left hover:text-[#C41E3A] ${!filters.category ? 'text-[#C41E3A] font-semibold' : 'text-gray-600'}`}
                    >
                        All Categories
                    </button>
                    {categories.map((cat) => (
                        <button
                            key={cat.id}
                            onClick={() => applyFilters({ category: cat.slug })}
                            className={`block text-sm w-full text-left hover:text-[#C41E3A] ${isCategoryActive(cat.slug) ? 'text-[#C41E3A] font-semibold' : 'text-gray-600'}`}
                        >
                            {cat.name}
                        </button>
                    ))}
                </div>
            </div>

            {/* Brands */}
            <div>
                <h3 className="font-bold text-gray-900 mb-4">Brands</h3>
                <div className="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                     <button 
                        onClick={() => applyFilters({ brand: null })}
                        className={`block text-sm w-full text-left hover:text-[#C41E3A] ${!filters.brand ? 'text-[#C41E3A] font-semibold' : 'text-gray-600'}`}
                    >
                        All Brands
                    </button>
                    {brands.map((brand) => (
                        <button
                            key={brand.id}
                            onClick={() => applyFilters({ brand: brand.slug })}
                            className={`block text-sm w-full text-left hover:text-[#C41E3A] ${isBrandActive(brand.slug) ? 'text-[#C41E3A] font-semibold' : 'text-gray-600'}`}
                        >
                            {brand.name}
                        </button>
                    ))}
                </div>
            </div>

            {/* Price Range */}
            <div>
                <h3 className="font-bold text-gray-900 mb-4">Price Range</h3>
                <form onSubmit={handlePriceFilter} className="space-y-4">
                    <div className="flex gap-4">
                        <div className="w-1/2">
                            <label className="text-xs text-gray-500 mb-1 block">Min</label>
                            <input
                                type="number"
                                value={minPrice}
                                onChange={(e) => setMinPrice(e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                placeholder="0"
                            />
                        </div>
                         <div className="w-1/2">
                            <label className="text-xs text-gray-500 mb-1 block">Max</label>
                            <input
                                type="number"
                                value={maxPrice}
                                onChange={(e) => setMaxPrice(e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                placeholder="1000"
                            />
                        </div>
                    </div>
                    <button type="submit" className="w-full bg-gray-900 text-white py-2 rounded text-sm hover:bg-gray-800 transition">
                        Apply Price
                    </button>
                </form>
            </div>
        </div>
    );
}
