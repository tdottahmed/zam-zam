import { Link } from '@inertiajs/react';

export default function CallToAction() {
    return (
        <section className="relative py-24 px-4 lg:px-8 bg-[#C41E3A] overflow-hidden">
            {/* Background Decorations (Pattern blocks) */}
            <div className="absolute inset-0 opacity-10">
                <svg className="h-full w-full text-white" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor" />
                </svg>
            </div>
            
            <div className="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2"></div>
            <div className="absolute bottom-0 right-0 w-96 h-96 bg-black/10 rounded-full blur-3xl translate-y-1/2 translate-x-1/2"></div>

            <div className="max-w-4xl mx-auto relative z-10 text-center">
                <span className="inline-block px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-sm">
                    Exclusive Partner Benefits
                </span>
                <h2 className="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight tracking-tight">
                    Ready to Expand Your <br className="hidden lg:block"/>
                    <span className="text-transparent bg-clip-text bg-gradient-to-r from-red-100 to-white">Product Inventory?</span>
                </h2>
                <p className="text-red-100 text-lg md:text-xl leading-relaxed mb-10 max-w-2xl mx-auto font-medium">
                    Join thousands of retailers across Canada. Access our exclusive wholesale pricing and premium catalog today.
                </p>
                
                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                    <button className="inline-flex items-center justify-center px-10 py-5 bg-white text-[#C41E3A] font-bold rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.2)] transform hover:-translate-y-1 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-6 h-6 mr-2 group-hover:scale-110 transition-transform">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download Full Catalog
                    </button>
                    <Link 
                        href={route('register')} 
                        className="inline-flex items-center justify-center px-10 py-5 bg-[#A01830] border border-white/10 text-white font-bold rounded-2xl hover:bg-[#8B1529] hover:border-white/20 transition-all duration-300 shadow-lg transform hover:-translate-y-1 backdrop-blur-sm"
                    >
                        Apply for Account
                    </Link>
                </div>
            </div>
        </section>
    );
}
