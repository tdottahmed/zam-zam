import { Link, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import useCartStore from '../../Stores/useCartStore';

export default function Header() {
    const { auth, cart } = usePage().props;
    const [isSearchOpen, setIsSearchOpen] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    
    // Zustand Store
    const { count, setCount } = useCartStore();

    // Sync Inertia props with Zustand
    useEffect(() => {
        if (cart?.count !== undefined) {
             setCount(cart.count);
        }
    }, [cart]);
    
    // Prevent scrolling when menu/search is open
    if (typeof window !== 'undefined') {
        if (isMobileMenuOpen || isSearchOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }
    }

    return (
        <>
            <header className="sticky top-0 z-50 w-full font-sans">
                {/* Top Bar */}
                <div className="bg-[#111111] text-white py-2 px-4 lg:px-8 text-[11px] uppercase tracking-widest font-medium border-b border-gray-800">
                    <div className="max-w-[1920px] mx-auto flex justify-between items-center">
                        <p className="hidden md:block opacity-80">Leading South Asian Product Distributor in Canada</p>
                        <div className="flex gap-6 items-center w-full md:w-auto justify-between md:justify-end">
                             <div className="flex items-center space-x-2 opacity-80 hover:text-[#C41E3A] hover:opacity-100 transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-4 h-4">
                                  <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                                <span>+1 (123) 456-7890</span>
                             </div>
                             <Link href="#" className="opacity-80 hover:text-[#C41E3A] hover:opacity-100 transition">Partner With Us</Link>
                        </div>
                    </div>
                </div>
                
                {/* Main Navigation */}
                <nav className="bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 relative z-50">
                    <div className="max-w-[1920px] mx-auto px-4 lg:px-8">
                        <div className="flex items-center justify-between h-20">
                            
                            {/* Mobile Menu Toggle (Left) */}
                             <div className="lg:hidden flex items-center">
                                <button
                                    onClick={() => setIsMobileMenuOpen(true)}
                                    className="p-2 -ml-2 text-gray-700 hover:text-[#C41E3A] transition focus:outline-none"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-7 h-7">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    </svg>
                                </button>
                            </div>

                            {/* Logo (Center on Mobile, Left on Desktop) */}
                            <div className="flex-shrink-0 flex items-center justify-center lg:justify-start flex-1 lg:flex-none">
                                <Link href="/">
                                    <img src="/images/Zam_logo-120x99.png" alt="Zam Zam" className="h-14 lg:h-16 w-auto object-contain" />
                                </Link>
                            </div>

                            {/* Desktop Navigation */}
                            <div className="hidden lg:flex items-center space-x-8 xl:space-x-12 px-8">
                                {[
                                    { label: 'Home', route: 'home' },
                                    { label: 'Shop', route: 'shop.index' },
                                    { label: 'Brands', route: 'shop.brands' },
                                    { label: 'About Us', route: null },
                                    { label: 'Contact', route: null },
                                ].map((item) => (
                                    <Link 
                                        key={item.label}
                                        href={item.route ? route(item.route) : '#'} 
                                        className={`text-sm font-bold uppercase tracking-wide transition-colors duration-300 relative group py-2
                                            ${item.route && route().current(item.route) ? 'text-[#C41E3A]' : 'text-gray-800 hover:text-[#C41E3A]'}
                                        `}
                                    >
                                        {item.label}
                                        <span className={`absolute bottom-0 left-0 w-full h-0.5 bg-[#C41E3A] transform origin-left transition-transform duration-300 
                                            ${item.route && route().current(item.route) ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'}
                                        `}></span>
                                    </Link>
                                ))}
                            </div>

                            {/* Right Actions */}
                            <div className="flex items-center justify-end space-x-2 lg:space-x-5 flex-1 lg:flex-none">
                                {/* Search Button */}
                                <button         
                                    onClick={() => setIsSearchOpen(true)}
                                    className="p-2 text-gray-500 hover:text-[#C41E3A] transition rounded-full hover:bg-gray-50 bg-gray-50/50 lg:bg-transparent"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </button>

                                {/* Cart Icon (Shared Desktop/Mobile placement logic slightly different) */}
                                <div className="relative">
                                     {/* Desktop Cart */}
                                    <Link href="#" className="hidden lg:flex p-2 text-gray-700 hover:text-[#C41E3A] transition relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                          <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 5c.07.277-.144.516-.41.488a10.977 10.977 0 0 1-5.26 1.508 10.979 10.979 0 0 1-5.26-1.508c-.266.028-.48-.21-.41-.488l1.263-5a.49.49 0 0 1 .454-.368 18.243 18.243 0 0 0 3.955-.42 18.22 18.22 0 0 0 3.955.42c.174 0 .332.13.454.368Z" />
                                        </svg>
                                        {count > 0 && (
                                            <span className="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-[#C41E3A] rounded-full">
                                                {count}
                                            </span>
                                        )}
                                    </Link>

                                    {/* Mobile Cart */}
                                     {/* Keeping existing logic or replacing? User said "add a cartCounter icon". I will add it here visible for mobile too if needed, or stick to the layout */}
                                </div>


                                {/* Auth Buttons */}
                                <div className="hidden lg:block">
                                    {auth.user ? (
                                        <Link href={route('dashboard')} className="flex items-center gap-2 text-gray-700 hover:text-[#C41E3A] text-sm font-bold uppercase tracking-wide ml-4">
                                             <div className="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-[#C41E3A]">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                </svg>
                                             </div>
                                            <span>Account</span>
                                        </Link>
                                    ) : (
                                        <Link href={route('login')} className="ml-4 bg-[#C41E3A] text-white px-7 py-2.5 rounded-full font-bold text-xs uppercase tracking-wider shadow-lg shadow-red-100 hover:shadow-red-200 hover:bg-[#a91930] transform hover:-translate-y-0.5 transition-all duration-300">
                                            Login
                                        </Link>
                                    )}
                                </div>
                                
                                {/* Mobile Cart & Menu Buttons */}
                                <div className="lg:hidden flex items-center gap-2">
                                    <Link href="#" className="p-2 text-gray-700 relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 5c.07.277-.144.516-.41.488a10.977 10.977 0 0 1-5.26 1.508 10.979 10.979 0 0 1-5.26-1.508c-.266.028-.48-.21-.41-.488l1.263-5a.49.49 0 0 1 .454-.368 18.243 18.243 0 0 0 3.955-.42 18.22 18.22 0 0 0 3.955.42c.174 0 .332.13.454.368Z" />
                                        </svg>
                                        {count > 0 && (
                                            <span className="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-[#C41E3A] rounded-full">
                                                {count}
                                            </span>
                                        )}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            {/* Mobile Menu Overlay (Slide Over) */}
            <div className={`fixed inset-0 z-[60] lg:hidden transition-opacity duration-300 ${isMobileMenuOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'}`}>
                 {/* Backdrop */}
                <div 
                    className={`absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 ${isMobileMenuOpen ? 'opacity-100' : 'opacity-0'}`} 
                    onClick={() => setIsMobileMenuOpen(false)}
                />
                
                {/* Drawer */}
                <div className={`absolute top-0 left-0 h-full w-[80%] max-w-[300px] bg-white shadow-2xl transform transition-transform duration-300 flex flex-col ${isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'}`}>
                    <div className="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <img src="/images/Zam_logo-120x99.png" alt="Zam Zam" className="h-10 w-auto" />
                        <button 
                            onClick={() => setIsMobileMenuOpen(false)}
                            className="p-2 bg-white rounded-full text-gray-500 hover:text-red-600 shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div className="flex-1 overflow-y-auto py-6 px-6 space-y-2">
                        {[
                            { label: 'Home', route: 'home' },
                            { label: 'Shop All Products', route: 'shop.index' },
                            { label: 'Browse Brands', route: 'shop.brands' },
                            { label: 'About Us', route: null },
                            { label: 'Contact Support', route: null },
                        ].map((item) => (
                            <Link 
                                key={item.label}
                                href={item.route ? route(item.route) : '#'} 
                                className={`block px-4 py-3 rounded-lg text-base font-semibold transition-colors
                                    ${item.route && route().current(item.route) ? 'bg-red-50 text-[#C41E3A]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#C41E3A]'}
                                `}
                                onClick={() => setIsMobileMenuOpen(false)}
                            >
                                {item.label}
                            </Link>
                        ))}
                    </div>

                    <div className="p-6 border-t border-gray-100 bg-gray-50">
                        {auth.user ? (
                            <Link 
                                href={route('dashboard')} 
                                className="flex items-center justify-center w-full bg-[#333] text-white py-3 rounded-lg font-bold uppercase tracking-wide text-sm hover:bg-[#C41E3A] transition"
                                onClick={() => setIsMobileMenuOpen(false)}
                            >
                                Dashboard
                            </Link>
                        ) : (
                             <Link 
                                href={route('login')} 
                                className="flex items-center justify-center w-full bg-[#C41E3A] text-white py-3 rounded-lg font-bold uppercase tracking-wide text-sm hover:bg-[#a91930] transition"
                                onClick={() => setIsMobileMenuOpen(false)}
                            >
                                Retailer Login
                            </Link>
                        )}
                        <p className="text-center text-xs text-gray-400 mt-4">© 2024 Zam Zam Import Export</p>
                    </div>
                </div>
            </div>

            {/* Search Overlay (Full Screen) */}
            <div className={`fixed inset-0 z-[70] transition-all duration-300 ${isSearchOpen ? 'opacity-100 visible' : 'opacity-0 invisible'}`}>
                {/* Backdrop */}
                <div className="absolute inset-0 bg-white/95 backdrop-blur-xl"></div>
                
                <div className="absolute inset-0 flex flex-col items-center justify-center p-6 w-full max-w-4xl mx-auto">
                    <button 
                        onClick={() => setIsSearchOpen(false)}
                        className="absolute top-8 right-8 p-3 bg-gray-100 rounded-full text-gray-500 hover:text-red-600 hover:bg-red-50 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div className="w-full space-y-8 animate-fade-in-up">
                        <h2 className="text-3xl font-bold text-center text-gray-900">What are you looking for?</h2>
                        
                        <form action={route('shop.index')} method="GET" className="relative w-full shadow-2xl rounded-2xl">
                             <input 
                                type="text"
                                name="search" 
                                autoFocus={isSearchOpen}
                                placeholder="Search for products, brands, or categories..." 
                                className="w-full bg-white text-gray-900 text-xl md:text-2xl font-medium px-8 py-6 rounded-2xl border-2 border-transparent focus:border-[#C41E3A] focus:ring-0 placeholder:text-gray-300 transition-all"
                            />
                            <button type="submit" className="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-[#C41E3A] text-white rounded-xl hover:bg-[#a91930] transition shadow-lg shadow-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </button>
                        </form>

                        <div className="text-center space-y-4">
                            <p className="text-gray-500 text-sm uppercase tracking-wide font-semibold">Popular Searches</p>
                            <div className="flex flex-wrap justify-center gap-3">
                                {['Basmati Rice', 'Shan Spices', 'Mango Juices', 'Frozen Paratha', 'Tea'].map((term) => (
                                    <Link 
                                        key={term}
                                        href={route('shop.index', { search: term })}
                                        className="px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm hover:bg-[#C41E3A] hover:text-white transition cursor-pointer"
                                        onClick={() => setIsSearchOpen(false)}
                                    >
                                        {term}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
