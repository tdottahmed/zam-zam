import { Link } from '@inertiajs/react';

export default function ValueProposition() {
    const features = [
        { 
            title: "Nationwide Reach", 
            desc: "Efficient delivery network covering over 50+ major cities across Canada.",
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
            )
        },
        { 
            title: "Premium Quality", 
            desc: "Authentic, high-grade products sourced directly from trusted manufacturers.",
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            )
        },
        { 
            title: "Expert Support", 
            desc: "Dedicated team available 24/7 to assist with your business needs.",
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            )
        },
        { 
            title: "Competitive Pricing", 
            desc: "Best market rates for bulk orders ensuring high margins for you.",
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            )
        }
    ];

    const stats = [
        { value: "15+", label: "Years Experience" },
        { value: "500+", label: "Happy Clients" },
        { value: "50+", label: "Cities Covered" },
        { value: "1000+", label: "Products" },
    ];

    return (
        <section className="relative py-24 bg-white overflow-hidden">
             {/* Subtle Decorative Elements */}
            <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-red-50 rounded-full blur-3xl opacity-60 -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div className="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gray-50 rounded-full blur-3xl opacity-60 translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
            
            <div className="max-w-[1920px] mx-auto px-4 lg:px-8 relative z-10">
                <div className="text-center max-w-4xl mx-auto mb-20">
                    <span className="text-[#C41E3A] font-bold tracking-widest uppercase text-xs mb-4 block">Why Choose Us</span>
                    <h2 className="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">
                        Partner with Canada's Leading <span className="text-[#C41E3A]">Distributor</span>
                    </h2>
                    <p className="text-gray-600 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
                        We bridge the gap between authentic South Asian manufacturers and Canadian retailers, ensuring a seamless supply chain enabling your business to thrive.
                    </p>
                    
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link 
                            href={route('register')} 
                            className="inline-flex items-center justify-center px-8 py-4 bg-[#C41E3A] text-white font-bold rounded-xl hover:bg-[#a91930] transition-all duration-300 shadow-lg shadow-red-100 transform hover:-translate-y-0.5"
                        >
                            Become a Partner
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-5 h-5 ml-2">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </Link>
                        <Link 
                            href={route('shop.index')} 
                            className="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-bold border-2 border-gray-200 rounded-xl hover:border-[#C41E3A] hover:text-[#C41E3A] transition-all duration-300"
                        >
                            View Products
                        </Link>
                    </div>
                </div>

                {/* Features Grid */}
                <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
                    {features.map((feature, idx) => (
                        <div 
                            key={idx} 
                            className="bg-white border border-gray-100 p-8 rounded-3xl shadow-sm hover:shadow-xl hover:shadow-red-50/50 hover:border-[#C41E3A]/20 transition-all duration-300 group text-center"
                        >
                            <div className="w-16 h-16 bg-red-50 text-[#C41E3A] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-[#C41E3A] group-hover:text-white transition-colors duration-300 transform group-hover:rotate-3">
                                {feature.icon}
                            </div>
                            <h3 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#C41E3A] transition-colors">{feature.title}</h3>
                            <p className="text-gray-500 text-sm leading-relaxed">{feature.desc}</p>
                        </div>
                    ))}
                </div>

                {/* Stats Section with Divider */}
                <div className="relative">
                    <div className="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-[#C41E3A] rounded-full mb-12"></div>
                    <div className="pt-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-100/0 md:divide-gray-100">
                        {stats.map((stat, idx) => (
                            <div key={idx} className="group cursor-default p-4 hover:bg-gray-50 rounded-2xl transition-colors duration-300">
                                <div className="text-4xl md:text-5xl font-black text-gray-900 mb-2 group-hover:text-[#C41E3A] transition-colors duration-300">
                                    {stat.value}
                                </div>
                                <div className="text-gray-400 text-sm uppercase tracking-widest font-bold group-hover:text-gray-600 transition-colors">
                                    {stat.label}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
