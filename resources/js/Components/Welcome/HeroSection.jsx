export default function HeroSection() {
    return (
        <section className="relative h-[600px] flex items-center bg-gray-900 text-white">
            {/* Background Placeholder */}
            <div className="absolute inset-0 bg-cover bg-center opacity-60" style={{ backgroundImage: "url('https://images.unsplash.com/photo-1519003722824-194d4455a60c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')" }}></div>
            <div className="absolute inset-0 bg-gradient-to-r from-gray-900 via-transparent to-transparent"></div>
            
            <div className="container mx-auto px-12 relative z-10">
                <div className="max-w-2xl">
                    <h1 className="text-5xl font-bold leading-tight mb-6">
                        Delivering Authentic Ethnic Products Straight to Your Shelves
                    </h1>
                    <p className="text-xl mb-8 text-gray-200">
                        Your 1-stop shop to all Indian, Pakistani and Middle Eastern cuisine.
                    </p>
                    <button className="px-8 py-3 bg-[#C41E3A] text-white font-bold rounded text-lg hover:bg-[#a91930] transition">
                        Explore Our Product Range
                    </button>
                </div>
            </div>
        </section>
    );
}
