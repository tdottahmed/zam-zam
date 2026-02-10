import { Link } from '@inertiajs/react';
import StorageImage from '../StorageImage';

export default function CategoriesGrid({ categories = [] }) {
    // Default categories if none provided
    const defaultCategories = [
        { icon: "🌶️", label: "Spices & Herbs", color: "bg-red-100 text-red-600" },
        { icon: "🍚", label: "Rice", color: "bg-amber-100 text-amber-600" },
        { icon: "🥩", label: "Frozen Meat", color: "bg-rose-100 text-rose-600" },
        { icon: "🍬", label: "Snacks & Sweets", color: "bg-pink-100 text-pink-600" },
        { icon: "🥤", label: "Juices", color: "bg-orange-100 text-orange-600" },
        { icon: "🍅", label: "Sauces", color: "bg-green-100 text-green-600" }
    ];

    const displayCategories = categories.length > 0 ? categories : defaultCategories;

    return (
        <section className="py-24 px-4 lg:px-8 bg-gray-50 relative overflow-hidden">
             {/* Decorative Elements */}
            <div className="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-[#C41E3A]/5 rounded-full blur-3xl"></div>
            <div className="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 bg-orange-500/5 rounded-full blur-3xl"></div>

            <div className="max-w-[1920px] mx-auto relative z-10">
                <div className="text-center mb-16">
                    <span className="text-[#C41E3A] font-bold tracking-widest uppercase text-xs mb-3 block animate-fade-in">Our Collections</span>
                    <h2 className="text-4xl md:text-5xl font-black text-gray-900 mb-6 tracking-tight">
                        Shop By Category
                    </h2>
                    <p className="text-gray-500 text-lg max-w-2xl mx-auto leading-relaxed">
                        Explore our extensive range of authentic products, carefully categorized for your shopping convenience.
                    </p>
                </div>

                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 lg:gap-8">
                    {displayCategories.map((cat, index) => (
                        <Link 
                            href={route('shop.index')} 
                            key={cat.id || index} 
                            className="group bg-white rounded-3xl p-6 shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] transition-all duration-300 transform hover:-translate-y-2 border border-transparent hover:border-gray-100 flex flex-col items-center justify-center text-center relative overflow-hidden"
                        >
                            {/* Hover Gradient Background */}
                            <div className="absolute inset-0 bg-gradient-to-br from-gray-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            {/* Icon / Image */}
                            <div className={`relative w-20 h-20 mb-6 rounded-full flex items-center justify-center text-4xl shadow-inner transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3 ${cat.color || "bg-gray-100 text-gray-600"}`}>
                                {cat.image ? (
                                    <StorageImage path={cat.image} name={cat.name || cat.label} className="w-12 h-12 object-contain drop-shadow-sm" />
                                ) : (
                                    <span className="filter drop-shadow-md">{cat.icon || "📦"}</span>
                                )}
                            </div>

                            {/* Text Content */}
                            <div className="relative z-10">
                                <h3 className="font-bold text-gray-900 text-lg mb-1 group-hover:text-[#C41E3A] transition-colors">{cat.name || cat.label}</h3>
                                <p className="text-xs text-gray-400 font-medium uppercase tracking-wider group-hover:text-gray-500 transition-colors">Browse</p>
                            </div>
                            
                            {/* Arrow Indicator */}
                            <div className="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 text-[#C41E3A]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2.5} stroke="currentColor" className="w-5 h-5">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                        </Link>
                    ))}
                </div>

                <div className="text-center mt-16">
                    <Link href={route('shop.index')} className="px-8 py-4 bg-white border-2 border-gray-100 text-gray-900 font-bold rounded-full hover:border-[#C41E3A] hover:bg-[#C41E3A] hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-red-200 transform hover:-translate-y-1">
                        View Full Catalogue
                    </Link>
                </div>
            </div>
        </section>
    );
}
