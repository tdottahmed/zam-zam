import { Link, Head } from '@inertiajs/react';

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    return (
        <>
            <Head title="Zam Zam Import export Inc - Landing Page" />
            <div className="font-sans text-[#333333] antialiased bg-white selection:bg-[#C41E3A] selection:text-white">
                
                {/* Header */}
                <nav className="flex items-center justify-between px-12 py-4 bg-white shadow-sm sticky top-0 z-50">
                    <div className="flex items-center">
                        {/* Logo */}
                        <div className="text-2xl font-bold text-[#C41E3A]">
                             <img src="/images/Zam_logo-120x99.png" alt="Zam Zam Import export Inc" className="h-16 w-auto" />
                        </div>
                    </div>
                    <div className="hidden md:flex space-x-8 font-medium text-gray-700">
                        <Link href="#" className="text-[#C41E3A]">Home</Link>
                        <Link href="#" className="hover:text-[#C41E3A] transition">Shop</Link>
                        <Link href="#" className="hover:text-[#C41E3A] transition">Shop by Brand</Link>
                        <Link href="#" className="hover:text-[#C41E3A] transition">About Us</Link>
                        <Link href="#" className="hover:text-[#C41E3A] transition">Contact us</Link>
                        
                        {auth.user ? (
                            <Link href={route('dashboard')} className="hover:text-[#C41E3A] transition">Dashboard</Link>
                        ) : (
                            <Link href={route('login')} className="hover:text-[#C41E3A] transition">Sign in</Link>
                        )}
                    </div>
                    <div>
                        <Link href={route('login')} className="px-6 py-2 bg-[#C41E3A] text-white rounded hover:bg-[#a91930] transition font-medium">
                            Retailer Login
                        </Link>
                    </div>
                </nav>

                {/* Hero Section */}
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

                {/* Brands Grid */}
                <section className="py-20 px-12 bg-white">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold mb-4">Trusted Brands We Distribute</h2>
                        <p className="text-gray-600 max-w-2xl mx-auto">
                            We partner with leading across the world brands to bring quality products directly to Canadian markets.
                        </p>
                    </div>
                    <div className="grid grid-cols-2 md:grid-cols-6 gap-8 items-center justify-items-center opacity-70 grayscale hover:grayscale-0 transition-all duration-500">
                        {["Handi", "Mitchells", "EBM", "Shan", "Nestle", "Dawn Bread"].map((brand) => (
                            <div key={brand} className="text-xl font-bold text-gray-400 hover:text-[#C41E3A] cursor-pointer">
                                {brand}
                            </div>
                        ))}
                    </div>
                    <div className="text-center mt-12">
                        <button className="text-[#C41E3A] font-semibold hover:underline">See All Brands &rarr;</button>
                    </div>
                </section>

                {/* Categories Icon Grid */}
                <section className="py-20 px-12 bg-gray-50">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold mb-4">Discover The Wide Range of Categories</h2>
                        <p className="text-gray-600 max-w-2xl mx-auto">
                            We partner with leading South Asian brands to bring quality products directly to Canadian markets.
                        </p>
                    </div>
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                        {[
                            { icon: "🌶️", label: "Spices & Herbs" },
                            { icon: "🍚", label: "Rice" },
                            { icon: "🥩", label: "Frozen Meat" },
                            { icon: "🍬", label: "Snacks & Sweets" },
                            { icon: "🥤", label: "Juices" },
                            { icon: "🍅", label: "Sauces" }
                        ].map((cat) => (
                            <div key={cat.label} className="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition text-center group cursor-pointer border border-transparent hover:border-[#C41E3A]">
                                <div className="text-4xl mb-4 group-hover:scale-110 transition">{cat.icon}</div>
                                <h3 className="font-semibold text-gray-800">{cat.label}</h3>
                            </div>
                        ))}
                    </div>
                    <div className="text-center mt-12">
                        <button className="text-[#C41E3A] font-semibold hover:underline">Explore All Categories &rarr;</button>
                    </div>
                </section>

                {/* Value Proposition */}
                <section className="py-20 px-12 bg-white">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl font-bold mb-4">Why Partner with Zam Zam Import export Inc?</h2>
                        <p className="text-gray-600 max-w-2xl mx-auto">
                            We make it easy for you to stock your shelves with the best products from South Asia.
                        </p>
                    </div>
                    <div className="grid md:grid-cols-3 gap-12">
                        {[
                            { title: "Reliable Supply Chain", desc: "Efficient delivery to over 50 cities across Canada." },
                            { title: "Diverse Product Range", desc: "Extensive product range tailored to meet your needs." },
                            { title: "Expert Support", desc: "Our team is ready to assist you with personalized support." }
                        ].map((feature) => (
                            <div key={feature.title} className="text-center p-6">
                                <div className="h-16 w-16 bg-[#C41E3A]/10 text-[#C41E3A] rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                                    ✓
                                </div>
                                <h3 className="text-xl font-bold mb-3">{feature.title}</h3>
                                <p className="text-gray-600">{feature.desc}</p>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Full Width Call to Action Banner */}
                <section className="py-16 px-12 bg-[#C41E3A] text-white text-center md:text-left flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h2 className="text-3xl font-bold mb-2">Download Our Complete Product Catalog</h2>
                        <p className="text-red-100 max-w-xl">Browse through our extensive collection of South Asian products.</p>
                    </div>
                    <div className="mt-8 md:mt-0">
                        <button className="px-8 py-3 border-2 border-white text-white font-bold rounded hover:bg-white hover:text-[#C41E3A] transition">
                            Download Catalog
                        </button>
                    </div>
                </section>

                {/* About Us & How It Works */}
                <section className="py-20 px-12 grid md:grid-cols-2 gap-16 items-start bg-gray-50">
                     {/* About */}
                    <div>
                        <h2 className="text-3xl font-bold mb-6">Bringing South Asia to Canada</h2>
                        <p className="text-gray-600 mb-6 leading-relaxed">
                            Zam Zam Import export Inc is committed to delivering high-quality products from South Asia to the Canadian market. We bridge the gap between authentic ethnic flavors and local retailers, ensuring that communities across Canada have access to the brands they love.
                        </p>
                        <button className="text-[#C41E3A] font-bold hover:underline">Learn More About Us &rarr;</button>
                    </div>

                    {/* How It Works */}
                    <div>
                        <h2 className="text-3xl font-bold mb-8">How It Works</h2>
                        <div className="space-y-8">
                            {[
                                { num: 1, title: "Reach Out to Us", link: "Get in Touch" },
                                { num: 2, title: "Explore Our Catalogue", link: "See Catalogue" },
                                { num: 3, title: "Place Your Order", link: "Order Now" }
                            ].map((step) => (
                                <div key={step.num} className="flex gap-6">
                                    <div className="flex-shrink-0 h-10 w-10 bg-gray-900 text-white rounded-full flex items-center justify-center font-bold">
                                        {step.num}
                                    </div>
                                    <div>
                                        <h3 className="text-xl font-bold mb-1">{step.title}</h3>
                                        <a href="#" className="text-[#C41E3A] text-sm font-semibold hover:underline">{step.link}</a>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="bg-[#1A1A1A] text-[#BBBBBB] py-16 px-12">
                    <div className="grid md:grid-cols-3 gap-12 mb-12">
                         <div>
                            <h3 className="text-white font-bold mb-4 uppercase tracking-wider">About</h3>
                            <p className="mb-6 text-sm leading-relaxed">
                                Discover the true taste of South Asia with Zam Zam Import export Inc. Your trusted partner for authentic ethnic distribution.
                            </p>
                            <div className="flex space-x-4">
                                <span>FB</span> <span>IG</span> <span>LI</span>
                            </div>
                         </div>
                         <div>
                            <h3 className="text-white font-bold mb-4 uppercase tracking-wider">Other Links</h3>
                             <ul className="space-y-2 text-sm">
                                <li><a href="#" className="hover:text-white">Blog</a></li>
                                <li><a href="#" className="hover:text-white">Careers</a></li>
                                <li><a href="#" className="hover:text-white">FAQ's</a></li>
                                <li><a href="#" className="hover:text-white">About Us</a></li>
                                <li><a href="#" className="hover:text-white">Contact Us</a></li>
                             </ul>
                         </div>
                         <div>
                            <h3 className="text-white font-bold mb-4 uppercase tracking-wider">Contact Us</h3>
                            <ul className="space-y-2 text-sm">
                                <li>8905 Hwy 50, Unit 7, Vaughan ON</li>
                                <li>+1 416-746-5550</li>
                                <li>hello@superasia.ca</li>
                            </ul>
                         </div>
                    </div>
                    <div className="border-t border-gray-800 pt-8 text-center text-xs">
                        Copyright © Zam Zam Import export Inc. Powered by Laravel & Inertia.
                    </div>
                </footer>
            </div>
        </>
    );
}
