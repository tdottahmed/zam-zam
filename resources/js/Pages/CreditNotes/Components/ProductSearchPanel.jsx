import React, { useState, useRef, useEffect } from 'react';
import StorageImage from '@/Components/StorageImage';

export default function ProductSearchPanel({ 
    productSearch, 
    setProductSearch, 
    isSearching, 
    searchResults, 
    onSelectProduct,
    selectedOrder // To change placeholder dynamically
}) {
    const [isOpen, setIsOpen] = useState(false);
    const wrapperRef = useRef(null);

    useEffect(() => {
        if (searchResults.length > 0) setIsOpen(true);
        else setIsOpen(false);
    }, [searchResults]);

    useEffect(() => {
        function handleClickOutside(event) {
            if (wrapperRef.current && !wrapperRef.current.contains(event.target)) {
                setIsOpen(false);
            }
        }
        document.addEventListener("mousedown", handleClickOutside);
        return () => document.removeEventListener("mousedown", handleClickOutside);
    }, [wrapperRef]);

    return (
        <div ref={wrapperRef} className="relative w-full z-20">
            <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    value={productSearch}
                    onChange={e => {
                        setProductSearch(e.target.value);
                        if (e.target.value.length >= 2) setIsOpen(true);
                    }}
                    onFocus={() => {
                        if (searchResults.length > 0) setIsOpen(true);
                    }}
                    placeholder={selectedOrder ? "Search for another item from this same order..." : "Search for a past purchase by Item Name..."}
                    className="pl-12 w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800/80 shadow-md hover:shadow-lg focus:border-[#C41E3A] focus:ring focus:ring-[#C41E3A] focus:ring-opacity-40 text-base py-4 placeholder-gray-400 transition-all font-medium"
                />
                {isSearching && (
                    <div className="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg className="animate-spin h-5 w-5 text-[#C41E3A]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                )}
            </div>

            {/* Dropdown Suggestions */}
            {isOpen && searchResults.length > 0 && (
                <div className="absolute w-full mt-2 bg-white dark:bg-[#1E1E1E] rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden max-h-96 overflow-y-auto custom-scrollbar">
                    <div className="p-2 border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Matches
                    </div>
                    <div className="divide-y divide-gray-100 dark:divide-gray-800">
                        {searchResults.map((result) => {
                            // Support both global search structure (result.product_id) and local search structure (result.id)
                            const key = result.product_id || result.id;
                            const imagePath = result.image || result.product?.image;
                            const name = result.product_name;
                            const sku = result.product_code || result.product?.product_code;

                            return (
                                <div 
                                    key={key}
                                    onClick={() => {
                                        setIsOpen(false);
                                        onSelectProduct(result);
                                    }}
                                    className="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors group relative overflow-hidden"
                                >
                                    <div className="absolute inset-y-0 left-0 w-1 bg-[#C41E3A] scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                                    <div className="h-12 w-12 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden ml-1 mr-4 border border-gray-200 dark:border-gray-600 shadow-sm">
                                        {imagePath && <StorageImage path={imagePath} name={name} className="h-full w-full object-cover" />}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <h4 className="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-[#C41E3A] transition-colors">
                                            {name}
                                        </h4>
                                        <div className="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                            {sku && <span className="font-mono bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">{sku}</span>}
                                            {result.orders && (
                                                <span className="flex items-center gap-1 font-medium text-gray-600 dark:text-gray-400">
                                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                                    Ordered {result.orders.length} {result.orders.length > 1 ? 'times' : 'time'}
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                    <div className="ml-4 pr-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div className="rounded-full bg-red-50 text-[#C41E3A] dark:bg-red-900/30 p-1.5">
                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            )}
        </div>
    );
}
