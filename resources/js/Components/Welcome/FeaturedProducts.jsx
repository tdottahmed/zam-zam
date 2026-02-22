import { useRef } from 'react';
import { Link } from '@inertiajs/react';
import ProductCard from '../Shop/ProductCard';

const MAX_PRODUCTS = 16;
const CARD_WIDTH = 320; // min width per card in carousel

function CarouselRow({ products, rowIndex }) {
    const scrollRef = useRef(null);

    const scroll = (dir) => {
        if (!scrollRef.current) return;
        const step = CARD_WIDTH + 24; // card width + gap
        scrollRef.current.scrollBy({ left: dir * step, behavior: 'smooth' });
    };

    return (
        <div className="relative group/row">
            {/* Left fade */}
            <div className="absolute left-0 top-0 bottom-0 w-16 sm:w-24 bg-gradient-to-r from-white via-white/90 to-transparent z-10 pointer-events-none" />
            {/* Right fade */}
            <div className="absolute right-0 top-0 bottom-0 w-16 sm:w-24 bg-gradient-to-l from-white via-white/90 to-transparent z-10 pointer-events-none" />

            <div
                ref={scrollRef}
                className="flex gap-6 overflow-x-auto overflow-y-hidden scroll-smooth snap-x snap-mandatory py-2 -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-12 lg:px-12 scrollbar-hide"
                style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
            >
                {products.map((product) => (
                    <div
                        key={product.id}
                        className="flex-shrink-0 w-[280px] sm:w-[300px] lg:w-[320px] snap-center"
                    >
                        <ProductCard product={product} />
                    </div>
                ))}
            </div>

            {/* Optional nav arrows - visible on hover */}
            {products.length > 2 && (
                <>
                    <button
                        type="button"
                        onClick={() => scroll(-1)}
                        aria-label="Scroll left"
                        className="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/95 shadow-lg border border-gray-100 flex items-center justify-center text-gray-600 hover:text-[#C41E3A] hover:border-[#C41E3A]/30 opacity-0 group-hover/row:opacity-100 transition-opacity duration-300"
                    >
                        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        onClick={() => scroll(1)}
                        aria-label="Scroll right"
                        className="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/95 shadow-lg border border-gray-100 flex items-center justify-center text-gray-600 hover:text-[#C41E3A] hover:border-[#C41E3A]/30 opacity-0 group-hover/row:opacity-100 transition-opacity duration-300"
                    >
                        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </>
            )}
        </div>
    );
}

export default function FeaturedProducts({ products = [], isBestSelling = false }) {
    const list = (products || []).slice(0, MAX_PRODUCTS);
    if (list.length === 0) return null;

    const half = Math.ceil(list.length / 2);
    const row1 = list.slice(0, half);
    const row2 = list.slice(half);

    const title = isBestSelling ? 'Best Sellers' : 'Featured Products';
    const subtitle = isBestSelling
        ? 'Our most loved items—discover what everyone is buying.'
        : 'Handpicked for you. Scroll to explore and add to cart.';

    return (
        <section className="py-16 md:py-24 relative overflow-hidden">
            {/* Background */}
            <div className="absolute inset-0 bg-gradient-to-b from-gray-50/90 via-white to-white" />
            <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-10%,rgba(196,30,58,0.06),transparent)]" />

            <div className="relative max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-12">
                {/* Section header */}
                <div className="text-center mb-10 md:mb-14">
                    <span className="text-[#C41E3A] font-bold tracking-widest uppercase text-xs mb-3 block">
                        Shop
                    </span>
                    <h2 className="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight">
                        {title}
                    </h2>
                    <p className="mt-3 text-gray-500 max-w-xl mx-auto text-sm md:text-base">
                        {subtitle}
                    </p>
                </div>

                {/* Two-row carousel */}
                <div className="space-y-6 md:space-y-8">
                    <CarouselRow products={row1} rowIndex={0} />
                    {row2.length > 0 && <CarouselRow products={row2} rowIndex={1} />}
                </div>

                {/* CTA */}
                <div className="mt-12 md:mt-16 text-center">
                    <Link
                        href={route('shop.index')}
                        className="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-[#C41E3A] text-white text-sm font-bold uppercase tracking-widest shadow-lg shadow-[#C41E3A]/25 hover:bg-[#a01830] hover:shadow-[#C41E3A]/30 transition-all duration-300"
                    >
                        View All Products
                        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>
            </div>

            <style>{`
                .scrollbar-hide::-webkit-scrollbar { display: none; }
            `}</style>
        </section>
    );
}
