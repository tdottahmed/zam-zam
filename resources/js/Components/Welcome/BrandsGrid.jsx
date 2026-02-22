import { Link } from '@inertiajs/react';
import StorageImage from '../StorageImage';

export default function BrandsGrid({ brands = [] }) {
    // Fallback brands if none provided (use image for API consistency)
    const sourceBrands = brands.length > 0 ? brands : [
        "Handi", "Mitchells", "EBM", "Shan", "Nestle", "Dawn Bread", "National", "Ahmed"
    ].map(name => ({ id: name, name, slug: name.toLowerCase().replace(/\s+/g, '-'), image: null }));

    // Duplicate brands to create seamless loop
    const displayBrands = [...sourceBrands, ...sourceBrands, ...sourceBrands];

    return (
        <section className="py-24 bg-gradient-to-b from-white to-gray-50/50 relative overflow-hidden border-t border-gray-100">
            {/* Edge fade for marquee */}
            <div className="absolute top-0 left-0 w-24 md:w-40 h-full bg-gradient-to-r from-white via-white/80 to-transparent z-10 pointer-events-none" />
            <div className="absolute top-0 right-0 w-24 md:w-40 h-full bg-gradient-to-l from-gray-50/50 via-white/80 to-transparent z-10 pointer-events-none" />

            <div className="max-w-[1920px] mx-auto px-4 mb-14 text-center">
                <span className="text-[#C41E3A] font-bold tracking-widest uppercase text-xs mb-3 block">Trusted Partners</span>
                <h2 className="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">
                    Global Brands We Distribute
                </h2>
                <p className="mt-3 text-gray-500 max-w-xl mx-auto text-sm md:text-base">
                    Browse products from the brands you love.
                </p>
            </div>

            <div className="relative w-full overflow-hidden group py-4">
                <div className="flex w-max animate-marquee group-hover:[animation-play-state:paused] items-center gap-0">
                    {displayBrands.map((brand, index) => (
                        <Link
                            href={route('shop.index', { brand: [brand.slug] })}
                            key={`${brand.id}-${index}`}
                            className="flex-shrink-0 w-56 md:w-72 lg:w-80 px-6 md:px-10 py-4 flex items-center justify-center border-r border-gray-100 last:border-0 transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/30 focus:ring-offset-2 rounded-xl mx-1"
                        >
                            <div className="relative w-full h-20 md:h-24 flex items-center justify-center">
                                <StorageImage
                                    path={brand.image}
                                    name={brand.name}
                                    className="max-h-full w-auto object-contain"
                                />
                            </div>
                        </Link>
                    ))}
                </div>
            </div>

            <div className="text-center mt-14">
                <Link
                    href={route('shop.brands')}
                    className="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white border border-gray-200 text-sm font-bold uppercase tracking-widest text-gray-700 hover:text-[#C41E3A] hover:border-[#C41E3A]/30 hover:shadow-md transition-all duration-300 group"
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
