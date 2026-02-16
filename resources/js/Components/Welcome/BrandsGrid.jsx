import { Link } from '@inertiajs/react';
import StorageImage from '../StorageImage';

export default function BrandsGrid({ brands = [] }) {
    // Fallback brands if none provided
    const displayBrands = brands.length > 0 ? brands : [
        "Handi", "Mitchells", "EBM", "Shan", "Nestle", "Dawn Bread"
    ].map(name => ({ id: name, name, slug: name.toLowerCase().replace(/\s+/g, '-'), logo: null }));

    return (
        <section className="py-24 bg-white relative overflow-hidden">
            {/* Background Decorations */}
            <div className="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
            <div className="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
            
            <div className="max-w-[1920px] mx-auto px-4 lg:px-8 relative z-10">
                <div className="text-center mb-16">
                    <span className="text-[#C41E3A] font-bold tracking-widest uppercase text-xs mb-3 block">Trusted Partners</span>
                    <h2 className="text-4xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight">
                        Global Brands We Distribute
                    </h2>
                    <p className="text-gray-500 text-lg max-w-2xl mx-auto leading-relaxed">
                        We partner with world-leading manufacturers to bring authentic, high-quality products directly to Canadian markets.
                    </p>
                </div>

                {/* Modern Grid Layout */}
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 border-t border-l border-gray-100 dark:border-gray-800">
                    {displayBrands.map((brand, index) => (
                        <Link 
                            href={route('shop.index', { brand: [brand.slug] })} 
                            key={brand.id || index} 
                            className="group relative flex items-center justify-center p-8 md:p-12 border-r border-b border-gray-100 bg-white hover:z-10 hover:shadow-[0_0_40px_-10px_rgba(0,0,0,0.1)] transition-all duration-500 ease-out"
                        >
                            <div className="relative w-full aspect-[3/2] flex items-center justify-center grayscale group-hover:grayscale-0 opacity-60 group-hover:opacity-100 transition-all duration-500 transform group-hover:scale-110">
                                <StorageImage 
                                    path={brand.logo} 
                                    name={brand.name} 
                                    className="max-h-16 w-auto object-contain mix-blend-multiply" 
                                />
                            </div>
                            
                            {/* Hover Overlay Effect */}
                            <div className="absolute inset-0 bg-gradient-to-t from-gray-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                        </Link>
                    ))}
                </div>

                <div className="text-center mt-16">
                    <Link 
                        href={route('shop.brands')} 
                        className="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-gray-900 hover:text-[#C41E3A] transition-colors group"
                    >
                        <span>Explore All Brands</span>
                        <svg 
                            className="w-4 h-4 transform transition-transform group-hover:translate-x-1" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor" 
                            strokeWidth="2"
                        >
                            <path strokeLinecap="round" strokeLinejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>
    );
}
