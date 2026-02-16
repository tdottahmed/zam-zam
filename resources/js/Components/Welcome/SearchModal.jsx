import { Fragment, useState, useEffect, useRef } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { Link, router } from '@inertiajs/react';
import axios from 'axios';

export default function SearchModal({ isOpen, onClose }) {
    const [query, setQuery] = useState('');
    const [results, setResults] = useState([]);
    const [loading, setLoading] = useState(false);
    const inputRef = useRef(null);

    // Debounce Search
    useEffect(() => {
        const timeoutId = setTimeout(() => {
            if (query.trim().length > 2) {
                performSearch();
            } else {
                setResults([]);
            }
        }, 300);

        return () => clearTimeout(timeoutId);
    }, [query]);

    const performSearch = async () => {
        setLoading(true);
        try {
            const response = await axios.get(route('shop.index'), {
                params: { search: query },
                headers: { 'Accept': 'application/json' }
            });
            setResults(response.data.data.slice(0, 5));
        } catch (error) {
            console.error("Search failed", error);
        } finally {
            setLoading(false);
        }
    };

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        onClose();
        router.get(route('shop.index'), { search: query });
    };

    return (
        <Transition.Root show={isOpen} as={Fragment}>
            <Dialog as="div" className="relative z-[70]" onClose={onClose} initialFocus={inputRef}>
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-gray-900/40 backdrop-blur-md transition-opacity" />
                </Transition.Child>

                <div className="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-300"
                        enterFrom="opacity-0 scale-95"
                        enterTo="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100 scale-100"
                        leaveTo="opacity-0 scale-95"
                    >
                        <Dialog.Panel className="mx-auto max-w-2xl transform divide-y divide-gray-100 overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 transition-all">
                            <form onSubmit={handleSearchSubmit} className="relative">
                                <svg className="pointer-events-none absolute left-4 top-3.5 h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                                <input
                                    ref={inputRef}
                                    type="text"
                                    className="h-16 w-full border-0 bg-transparent pl-12 pr-12 text-gray-900 placeholder:text-gray-400 focus:ring-0 text-lg sm:text-xl font-medium tracking-tight" // Increased height and font size
                                    placeholder="Search products..."
                                    value={query}
                                    onChange={(e) => setQuery(e.target.value)}
                                />
                                {loading ? (
                                    <div className="absolute right-4 top-5">
                                        <svg className="animate-spin h-6 w-6 text-[#C41E3A]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                ) : query && (
                                     <button 
                                        type="button"
                                        onClick={() => setQuery('')}
                                        className="absolute right-4 top-5 text-gray-400 hover:text-gray-600 focus:outline-none"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                )}
                            </form>

                            {/* Content Area */}
                            {(query === '' || (results.length === 0 && !loading)) && (
                                <div className="py-14 px-6 text-center text-sm sm:px-14">
                                    {query === '' ? (
                                        <div className="animate-fadeIn">
                                            <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 mb-6">
                                                 <svg className="h-8 w-8 text-[#C41E3A]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.046 8.251 8.251 0 0 1 9 2.19M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </div>
                                            <p className="text-lg font-semibold text-gray-900 mb-2">Looking for something?</p>
                                            <div className="flex flex-wrap justify-center gap-2 mt-4">
                                                {['Rice', 'Spices', 'Tea', 'Oil', 'Juice'].map((tag) => (
                                                    <button
                                                        key={tag}
                                                        onClick={() => setQuery(tag)}
                                                        className="px-4 py-1.5 rounded-full bg-gray-50 text-gray-600 text-sm hover:bg-[#C41E3A] hover:text-white transition-colors duration-200 border border-gray-100"
                                                    >
                                                        {tag}
                                                    </button>
                                                ))}
                                            </div>
                                        </div>
                                    ) : (
                                         <div className="animate-fadeIn">
                                            <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 mb-4">
                                                <svg className="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                </svg>
                                            </div>
                                            <h3 className="text-sm font-semibold text-gray-900">No results found</h3>
                                            <p className="mt-1 text-sm text-gray-500">We couldn't find anything matching "{query}".</p>
                                        </div>
                                    )}
                                </div>
                            )}

                            {results.length > 0 && (
                                <ul className="max-h-[60vh] overflow-y-auto scroll-py-2 py-2 text-sm text-gray-800">
                                    {results.map((product) => (
                                        <li key={product.id} className="animate-fadeIn">
                                            <Link
                                                href={route('shop.show', { product: product.id })}
                                                className="group flex cursor-default select-none items-center rounded-md px-4 py-3 hover:bg-gray-50 transition-colors duration-150"
                                                onClick={onClose}
                                            >
                                                <div className="flex h-12 w-12 flex-none items-center justify-center rounded-lg bg-white border border-gray-100 shadow-sm overflow-hidden">
                                                     {product.image ? (
                                                        <img src={product.image} alt={product.name} className="h-full w-full object-cover" />
                                                     ) : (
                                                        <svg className="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                        </svg>
                                                     )}
                                                </div>
                                                <div className="ml-4 flex-auto">
                                                    <p className="font-semibold text-gray-900 group-hover:text-[#C41E3A] transition-colors duration-150">
                                                        {product.name}
                                                    </p>
                                                    <div className="flex items-center gap-2 mt-0.5">
                                                        <span className="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">{product.category ? product.category.name : 'Product'}</span>
                                                        <span className="text-xs font-medium text-[#C41E3A]">
                                                            {product.unit_price} {product.unit ? product.unit.code : ''}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div className="ml-4 flex-none">
                                                    <svg className="h-5 w-5 text-gray-300 group-hover:text-[#C41E3A] transition-colors duration-150 -rotate-45 group-hover:rotate-0 transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                    </svg>
                                                </div>
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            )}

                            {results.length > 0 && (
                                <div className="bg-gray-50 px-4 py-3 sm:px-6 flex items-center justify-between border-t border-gray-100">
                                    <p className="text-xs text-gray-500">
                                        <span className="font-medium text-gray-900">{results.length}+</span> results found
                                    </p>
                                    <button 
                                        onClick={handleSearchSubmit}
                                        className="text-xs font-bold text-[#C41E3A] hover:text-[#a0162e] uppercase tracking-wide flex items-center gap-1 transition-colors"
                                    >
                                        View All Results
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" className="w-4 h-4">
                                            <path fillRule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clipRule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            )}
                        </Dialog.Panel>
                    </Transition.Child>
                </div>
            </Dialog>
        </Transition.Root>
    );
}
