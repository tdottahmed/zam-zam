export default function CategoriesGrid({ categories = [] }) {
    // Default categories if none provided
    const defaultCategories = [
        { icon: "🌶️", label: "Spices & Herbs" },
        { icon: "🍚", label: "Rice" },
        { icon: "🥩", label: "Frozen Meat" },
        { icon: "🍬", label: "Snacks & Sweets" },
        { icon: "🥤", label: "Juices" },
        { icon: "🍅", label: "Sauces" }
    ];

    const displayCategories = categories.length > 0 ? categories : defaultCategories;

    return (
        <section className="py-20 px-12 bg-gray-50">
            <div className="text-center mb-12">
                <h2 className="text-3xl font-bold mb-4">Discover The Wide Range of Categories</h2>
                <p className="text-gray-600 max-w-2xl mx-auto">
                    We partner with leading South Asian brands to bring quality products directly to Canadian markets.
                </p>
            </div>
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                {displayCategories.map((cat, index) => (
                    <div key={cat.id || index} className="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition text-center group cursor-pointer border border-transparent hover:border-[#C41E3A]">
                        <div className="text-4xl mb-4 group-hover:scale-110 transition">
                            {/* If dynamic, we might have an image or icon field. For now checking if it's an object with icon or image */}
                            {cat.image ? (
                                <img src={`/storage/${cat.image}`} alt={cat.name} className="h-12 w-auto mx-auto object-contain" />
                            ) : (
                                cat.icon || "📦" // Fallback icon
                            )}
                        </div>
                        <h3 className="font-semibold text-gray-800">{cat.name || cat.label}</h3>
                    </div>
                ))}
            </div>
            <div className="text-center mt-12">
                <button className="text-[#C41E3A] font-semibold hover:underline">Explore All Categories &rarr;</button>
            </div>
        </section>
    );
}
