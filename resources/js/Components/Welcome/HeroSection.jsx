import { Link } from '@inertiajs/react';

export default function HeroSection() {
    return (
        <section className="relative h-[85vh] min-h-[600px] flex items-center overflow-hidden font-sans">
            {/* Background Image with Zoom Effect */}
            <div className="absolute inset-0 z-0">
                <div 
                    className="absolute inset-0 bg-cover bg-center transition-transform duration-[20s] hover:scale-105" 
                    style={{ backgroundImage: "url('https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?q=80&w=2070&auto=format&fit=crop')" }}
                ></div>
                {/* Premium Gradient Overlay */}
                <div className="absolute inset-0 bg-gradient-to-t from-[#111] via-black/60 to-black/30"></div>
            </div>
            
            <div className="relative z-10 w-full max-w-[1920px] mx-auto px-6 lg:px-12 flex flex-col items-center justify-center text-center">
                <div className="max-w-4xl animate-fade-in-up flex flex-col items-center">
                    {/* Badge */}
                    <div className="inline-block px-4 py-1.5 bg-[#C41E3A]/20 border border-[#C41E3A] backdrop-blur-sm rounded-full mb-6">
                        <span className="text-[#ff4d6d] font-bold text-base uppercase tracking-wider">ZamZam Import & Export Inc</span>
                    </div>

                    <h1 className="text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 text-white tracking-tight drop-shadow-lg">
                        Authentic Flavors, <br/>
                        <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#C41E3A] to-[#ff4d6d]">
                            Global Reach.
                        </span>
                    </h1>
                    
                    <p className="text-lg md:text-2xl mb-10 text-gray-200 leading-relaxed font-light max-w-2xl mx-auto">
                        Your premier distributor for authentic Indian, Pakistani,Bangladeshi and Middle Eastern cuisine. We bridge the gap between global brands and Canadian shelves.
                    </p>
                    
                    <div className="flex flex-col sm:flex-row gap-4 justify-center w-full">
                        <Link 
                            href={route('shop.index')}
                            className="group relative px-8 py-4 bg-[#C41E3A] text-white font-bold rounded-full text-lg overflow-hidden shadow-lg shadow-red-900/50 hover:shadow-red-900/70 transition-all duration-300 w-full sm:w-auto text-center"
                        >
                            <span className="relative z-10 flex items-center justify-center gap-2">
                                Explore Products
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-5 h-5 group-hover:translate-x-1 transition-transform">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </span>
                            <div className="absolute inset-0 bg-[#a91930] transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                        </Link>
                        
                        <Link 
                            href={route('shop.brands')}
                            className="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded-full text-lg hover:bg-white/20 transition-all duration-300 w-full sm:w-auto text-center"
                        >
                            View Our Brands
                        </Link>
                    </div>

                     {/* Stats for Trust */}
                     <div className="mt-16 flex flex-wrap justify-center items-center gap-8 md:gap-12 text-white/80 border-t border-white/10 pt-8 w-full">
                        <div>
                            <p className="text-3xl font-bold text-white mb-1">20+</p>
                            <p className="text-xs uppercase tracking-widest opacity-70">Years Experience</p>
                        </div>
                        <div className="w-px h-10 bg-white/20"></div>
                        <div>
                            <p className="text-3xl font-bold text-white mb-1">100%</p>
                            <p className="text-xs uppercase tracking-widest opacity-70">Quality Guaranteed</p>
                        </div>
                        <div className="w-px h-10 bg-white/20 hidden sm:block"></div>
                        <div className="hidden sm:block">
                            <p className="text-3xl font-bold text-white mb-1">Nationwide</p>
                            <p className="text-xs uppercase tracking-widest opacity-70">Distribution Network</p>
                        </div>
                    </div>
                </div>
            </div>
            
             {/* Decorative Elements */}
             <div className="absolute bottom-0 right-0 w-1/3 h-1/2 bg-gradient-to-t from-[#C41E3A]/20 to-transparent blur-3xl rounded-full translate-y-1/2 translate-x-1/2"></div>
        </section>
    );
}
