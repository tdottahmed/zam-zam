import { Link } from '@inertiajs/react';

export default function BrandsGrid({ brands = [] }) {
    return (
        <section className="py-20 px-12 bg-white">
            <div className="text-center mb-12">
                <h2 className="text-3xl font-bold mb-4">Trusted Brands We Distribute</h2>
                <p className="text-gray-600 max-w-2xl mx-auto">
                    We partner with leading across the world brands to bring quality products directly to Canadian markets.
                </p>
            </div>
            <div className="grid grid-cols-2 md:grid-cols-6 gap-8 items-center justify-items-center opacity-70 grayscale hover:grayscale-0 transition-all duration-500">
                {brands.length > 0 ? (
                    brands.map((brand) => (
                        <div key={brand.id} className="text-xl font-bold text-gray-400 hover:text-[#C41E3A] cursor-pointer text-center">
                            {/* Assuming brand has name and logic for image if needed, for now just name as per old code but dynamic */}
                            {brand.logo ? (
                                <img src={`/storage/${brand.logo}`} alt={brand.name} className="h-16 w-auto object-contain" />
                            ) : (
                                <span>{brand.name}</span>
                            )}
                        </div>
                    ))
                ) : (
                    // Fallback to static if no brands passed (or during dev while empty DB)
                     ["Handi", "Mitchells", "EBM", "Shan", "Nestle", "Dawn Bread"].map((brand) => (
                        <div key={brand} className="text-xl font-bold text-gray-400 hover:text-[#C41E3A] cursor-pointer">
                            {brand}
                        </div>
                    ))
                )}
            </div>
            <div className="text-center mt-12">
                <button className="text-[#C41E3A] font-semibold hover:underline">See All Brands &rarr;</button>
            </div>
        </section>
    );
}
