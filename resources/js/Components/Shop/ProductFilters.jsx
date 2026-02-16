import { useState, useEffect, useCallback } from 'react';
import { router } from '@inertiajs/react';
import { debounce } from 'lodash';

// Icons
const ChevronDown = ({ className }) => (
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={className}>
        <path strokeLinecap="round" strokeLinejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
    </svg>
);

const ChevronUp = ({ className }) => (
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={className}>
        <path strokeLinecap="round" strokeLinejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
    </svg>
);

const FilterSection = ({ title, children, isOpen, onToggle }) => (
    <div className="border-b border-gray-100 py-4 last:border-0 layer-transition">
        <button 
            type="button" 
            onClick={onToggle}
            className="flex w-full items-center justify-between text-left group"
        >
            <span className="font-semibold text-gray-900 group-hover:text-[#C41E3A] transition-colors">{title}</span>
            <span className="ml-6 flex items-center">
                {isOpen ? (
                    <ChevronUp className="h-4 w-4 text-gray-400 group-hover:text-[#C41E3A] transition-colors" />
                ) : (
                    <ChevronDown className="h-4 w-4 text-gray-400 group-hover:text-[#C41E3A] transition-colors" />
                )}
            </span>
        </button>
        {isOpen && (
            <div className="mt-4 animate-fadeIn">
                {children}
            </div>
        )}
    </div>
);

const Checkbox = ({ label, checked, onChange, count }) => (
    <label className="flex items-center group cursor-pointer py-1.5 pl-1">
        <div className="relative flex items-center">
            <input
                type="checkbox"
                className="peer h-4 w-4 rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A] transition duration-150 ease-in-out cursor-pointer"
                checked={checked}
                onChange={onChange}
            />
        </div>
        <span className={`ml-3 text-sm group-hover:text-[#C41E3A] transition-colors ${checked ? 'text-gray-900 font-medium' : 'text-gray-600'}`}>
            {label}
        </span>
        {count !== undefined && (
            <span className="ml-auto text-xs text-gray-400 group-hover:text-[#C41E3A] transition-colors">
                ({count})
            </span>
        )}
    </label>
);

export default function ProductFilters({ categories, brands, filters }) {
    // Helper to ensure array
    const toArray = (val) => {
        if (Array.isArray(val)) return val;
        if (val === null || val === undefined || val === '') return [];
        return [val];
    };

    const [search, setSearch] = useState(filters.search || '');
    const [selectedCategories, setSelectedCategories] = useState(toArray(filters.category));
    const [selectedBrands, setSelectedBrands] = useState(toArray(filters.brand));
    const [minPrice, setMinPrice] = useState(filters.min_price || '');
    const [maxPrice, setMaxPrice] = useState(filters.max_price || '');
    
    // Track if strict initial load happened to prevent double firing on mount
    const [isInitialized, setIsInitialized] = useState(false);

    // Section open states
    const [openSections, setOpenSections] = useState({
        categories: true,
        brands: true,
        price: true,
    });

    const toggleSection = (section) => {
        setOpenSections(prev => ({ ...prev, [section]: !prev[section] }));
    };

    // Debounced apply function
    const applyFiltersDebounced = useCallback(
        debounce((newFilters) => {
            const query = { 
                ...filters,
                ...newFilters
            };
            
            // Cleanup empty
            Object.keys(query).forEach(key => {
                if (query[key] === '' || query[key] === null || query[key] === undefined || (Array.isArray(query[key]) && query[key].length === 0)) {
                    delete query[key];
                }
            });

            // Reset pagination
            delete query.page;

            router.get(route('shop.index'), query, {
                preserveState: true,
                preserveScroll: true,
                replace: true, // Use replace to avoid polluting history
            });
        }, 500),
        [] // Empty dependency array for debounce creation
    );

    // Effect to trigger update on state changes
    useEffect(() => {
        if (!isInitialized) {
            setIsInitialized(true);
            return;
        }

        const newFilters = {
            search,
            category: selectedCategories,
            brand: selectedBrands,
            min_price: minPrice,
            max_price: maxPrice,
        };

        applyFiltersDebounced(newFilters);

        // Cleanup debounce on unmount or re-render
        return () => applyFiltersDebounced.cancel();
    }, [search, selectedCategories, selectedBrands, minPrice, maxPrice]);

    const handleCategoryToggle = (slug) => {
        setSelectedCategories(prev => 
            prev.includes(slug) ? prev.filter(c => c !== slug) : [...prev, slug]
        );
    };

    const handleBrandToggle = (slug) => {
        setSelectedBrands(prev => 
            prev.includes(slug) ? prev.filter(b => b !== slug) : [...prev, slug]
        );
    };
    
    const handleClearAll = () => {
        setSearch('');
        setSelectedCategories([]);
        setSelectedBrands([]);
        setMinPrice('');
        setMaxPrice('');
        // Immediate clear, bypass debounce for better UX
        router.get(route('shop.index'));
    };

    const hasActiveFilters = search || selectedCategories.length > 0 || selectedBrands.length > 0 || minPrice || maxPrice;

    return (
        <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
            <div className="flex items-center justify-between mb-6">
                <h2 className="text-lg font-bold text-gray-900">Filters</h2>
                {hasActiveFilters && (
                    <button 
                        onClick={handleClearAll}
                        className="text-xs font-semibold text-[#C41E3A] hover:underline"
                    >
                        Clear All
                    </button>
                )}
            </div>

            {/* Search */}
            <div className="mb-6 relative">
                 <input
                    type="text"
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                    placeholder="Search..."
                    className="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#C41E3A]/20 focus:border-[#C41E3A] transition-all"
                />
                <div className="absolute right-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" className="w-5 h-5">
                      <path fillRule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clipRule="evenodd" />
                    </svg>
                </div>
            </div>

            <div className="space-y-1">
                {/* Categories */}
                <FilterSection 
                    title="Categories" 
                    isOpen={openSections.categories} 
                    onToggle={() => toggleSection('categories')}
                >
                    <div className="space-y-1 max-h-64 overflow-y-auto custom-scrollbar pr-2 pl-1">
                        <Checkbox
                            label="All Categories"
                            checked={selectedCategories.length === 0}
                            onChange={() => setSelectedCategories([])}
                        />
                        {categories.map((cat) => (
                            <Checkbox
                                key={cat.id}
                                label={cat.name}
                                checked={selectedCategories.includes(cat.slug)}
                                onChange={() => handleCategoryToggle(cat.slug)}
                            />
                        ))}
                    </div>
                </FilterSection>

                {/* Brands */}
                <FilterSection 
                    title="Brands" 
                    isOpen={openSections.brands} 
                    onToggle={() => toggleSection('brands')}
                >
                    <div className="space-y-1 max-h-64 overflow-y-auto custom-scrollbar pr-2 pl-1">
                        <Checkbox
                            label="All Brands"
                            checked={selectedBrands.length === 0}
                            onChange={() => setSelectedBrands([])}
                        />
                        {brands.map((brand) => (
                            <Checkbox
                                key={brand.id}
                                label={brand.name}
                                checked={selectedBrands.includes(brand.slug)}
                                onChange={() => handleBrandToggle(brand.slug)}
                            />
                        ))}
                    </div>
                </FilterSection>

                {/* Price Range */}
                <FilterSection 
                    title="Price Range" 
                    isOpen={openSections.price} 
                    onToggle={() => toggleSection('price')}
                >
                    <div className="flex items-center gap-3">
                        <div className="relative w-full">
                            <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">$</span>
                            <input
                                type="number"
                                value={minPrice}
                                onChange={(e) => setMinPrice(e.target.value)}
                                className="w-full pl-6 pr-2 py-2 text-sm bg-gray-50 border border-gray-200 rounded-md focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                placeholder="Min"
                            />
                        </div>
                        <span className="text-gray-400">-</span>
                        <div className="relative w-full">
                            <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">$</span>
                            <input
                                type="number"
                                value={maxPrice}
                                onChange={(e) => setMaxPrice(e.target.value)}
                                className="w-full pl-6 pr-2 py-2 text-sm bg-gray-50 border border-gray-200 rounded-md focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                placeholder="Max"
                            />
                        </div>
                    </div>
                </FilterSection>
            </div>
        </div>
    );
}
