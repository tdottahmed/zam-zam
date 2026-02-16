import { Link } from '@inertiajs/react';
import StorageImage from '../StorageImage';



export default function BrandsGrid({ brands = [] }) {
    // Fallback brands if none provided
    const sourceBrands = brands.length > 0 ? brands : [
        "Handi", "Mitchells", "EBM", "Shan", "Nestle", "Dawn Bread", "National", "Ahmed"
    ].map(name => ({ id: name, name, slug: name.toLowerCase().replace(/\s+/g, '-'), logo: null }));

    // Duplicate brands to create seamless loop
    const displayBrands = [...sourceBrands, ...sourceBrands, ...sourceBrands];

    return (
        <section className="py-20 bg-white relative overflow-hidden border-t border-gray-100">
             {/* Background Gradients for fade effect */}
            <div className="absolute top-0 left-0 w-32 h-full bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
            <div className="absolute top-0 right-0 w-32 h-full bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

            <div className="max-w-[1920px] mx-auto px-4 mb-12 text-center">
                <span className="text-[#C41E3A] font-bold tracking-widest uppercase text-xs mb-3 block">Trusted Partners</span>
                <h2 className="text-3xl font-black text-gray-900 tracking-tight">
                    Global Brands We Distribute
                </h2>
            </div>

            <div className="relative w-full overflow-hidden group">
                <div className="flex w-max animate-marquee group-hover:[animation-play-state:paused] items-center">
                    {displayBrands.map((brand, index) => (
                        <Link 
                            href={route('shop.index', { brand: [brand.slug] })} 
                            key={`${brand.id}-${index}`}
                            className="flex-shrink-0 w-48 md:w-64 px-8 border-r border-gray-100 last:border-0 flex items-center justify-center grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300 transform hover:scale-110"
                        >
                            <div className="relative w-full aspect-[3/1] flex items-center justify-center">
                                <StorageImage 
                                    path={brand.logo} 
                                    name={brand.name} 
                                    className="max-h-16 w-auto object-contain mix-blend-multiply" 
                                />
                            </div>
                        </Link>
                    ))}
                </div>
            </div>

            <div className="text-center mt-12">
                <Link 
                    href={route('shop.brands')} 
                    className="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-gray-900 hover:text-[#C41E3A] transition-colors group"
                >
                    <span>View All Brands</span>
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
        </section>
    );
}
