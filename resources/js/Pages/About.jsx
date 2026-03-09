import CustomerLayout from '@/Layouts/CustomerLayout';
import { Head, Link } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import Breadcrumb from '@/Components/Breadcrumb';

export default function About({ aboutSettings }) {
    return (
        <CustomerLayout>
            <Head title="About Us" />

            <Breadcrumb 
                title="About Us" 
                links={[
                    { label: 'About', active: true }
                ]} 
            />
            {/* Our Story Section */}
            <section className="py-20 bg-white">
                <div className="max-w-[1920px] mx-auto px-4 lg:px-8">
                    <div className="grid lg:grid-cols-2 gap-16 items-center">
                        <div className="relative">
                            <div className="absolute -top-4 -left-4 w-24 h-24 bg-[#C41E3A]/10 rounded-full blur-3xl"></div>
                            <img 
                                src={aboutSettings?.about_us_image ? `/storage/${aboutSettings.about_us_image}` : "https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=2574&auto=format&fit=crop"} 
                                alt="Warehouse Operations" 
                                className="rounded-3xl shadow-2xl relative z-10 w-full object-cover h-[500px]"
                            />
                            {aboutSettings?.about_us_badge_text && (
                                <div className="absolute -bottom-6 -right-6 bg-white p-8 rounded-2xl shadow-xl z-20 max-w-xs hidden md:block">
                                    <p className="text-[#C41E3A] font-black text-4xl mb-1">{aboutSettings.about_us_badge_text}</p>
                                    <p className="text-gray-600 font-bold uppercase tracking-wide text-xs">{aboutSettings.about_us_badge_subtext}</p>
                                </div>
                            )}
                        </div>
                        <div className="space-y-8">
                            <div>
                                <h2 className="text-3xl lg:text-4xl font-black text-gray-900 mb-6">{aboutSettings?.about_us_heading || 'Our Story'}</h2>
                                <div className="space-y-4 text-gray-600 text-lg leading-relaxed">
                                    {aboutSettings?.about_us_description_1 && <p>{aboutSettings.about_us_description_1}</p>}
                                    {aboutSettings?.about_us_description_2 && <p>{aboutSettings.about_us_description_2}</p>}
                                    {aboutSettings?.about_us_description_3 && <p>{aboutSettings.about_us_description_3}</p>}
                                </div>
                            </div>
                            
                            <div className="grid grid-cols-2 gap-6">
                                <div className="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                                    <h3 className="font-bold text-gray-900 text-lg mb-2">{aboutSettings?.about_us_feature_1_title || 'Authenticity'}</h3>
                                    <p className="text-gray-500 text-sm">{aboutSettings?.about_us_feature_1_desc || '100% genuine products sourced directly from manufacturers.'}</p>
                                </div>
                                <div className="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                                    <h3 className="font-bold text-gray-900 text-lg mb-2">{aboutSettings?.about_us_feature_2_title || 'Reliability'}</h3>
                                    <p className="text-gray-500 text-sm">{aboutSettings?.about_us_feature_2_desc || 'Consistent supply chain and timely deliveries you can count on.'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Mission & Vision */}
            <section className="py-24 bg-[#111111] text-white relative overflow-hidden">
                {/* Background Pattern */}
                <div className="absolute inset-0 opacity-5" style={{ backgroundImage: 'radial-gradient(#444 1px, transparent 1px)', backgroundSize: '30px 30px' }}></div>
                
                <div className="max-w-[1920px] mx-auto px-4 lg:px-8 relative z-10">
                    <div className="text-center mb-16">
                        <span className="text-[#C41E3A] font-bold tracking-widest uppercase text-xs mb-3 block">Why We Exist</span>
                        <h2 className="text-3xl md:text-5xl font-black mb-6">Our Mission & Vision</h2>
                        <p className="text-gray-400 max-w-2xl mx-auto text-lg">Driving excellence in food distribution through innovation, integrity, and partnership.</p>
                    </div>

                    <div className="grid md:grid-cols-3 gap-8">
                        {[
                            {
                                title: "Our Mission",
                                desc: "To provide the highest quality ethnic food products to distinct cultural communities across Canada, ensuring a taste of home in every bite.",
                                icon: (
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                )
                            },
                             {
                                title: "Our Vision",
                                desc: "To be the undisputed leader in ethnic food distribution, recognized for our operational excellence and customer-centric approach.",
                                icon: (
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                )
                            },
                             {
                                title: "Our Values",
                                desc: "Integrity, Quality, Reliability, and Community. These core values guide every decision we make and every partnership we build.",
                                icon: (
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                )
                            },
                        ].map((item, index) => (
                            <div key={index} className="bg-[#1A1A1A] p-10 rounded-3xl border border-gray-800 hover:border-[#C41E3A] transition-all duration-300 group">
                                <div className="w-16 h-16 bg-gray-800 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:bg-[#C41E3A] transition-colors duration-300 shadow-lg">
                                    {item.icon}
                                </div>
                                <h3 className="text-2xl font-bold mb-4">{item.title}</h3>
                                <p className="text-gray-400 leading-relaxed text-sm">{item.desc}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

             {/* Stats Section */}
             <div className="py-20 bg-[#C41E3A] text-white">
                <div className="max-w-[1920px] mx-auto px-4 lg:px-8">
                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center divide-x divide-white/20">
                        {[
                            { number: "500+", label: "Products" },
                            { number: "250+", label: "Retail Partners" },
                            { number: "15+", label: "Years Experience" },
                            { number: "100%", label: "Satisfaction" },
                        ].map((stat, i) => (
                            <div key={i} className="p-4">
                                <div className="text-4xl md:text-5xl font-black mb-2">{stat.number}</div>
                                <div className="text-white/80 font-medium tracking-wider uppercase text-sm">{stat.label}</div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* CTA */}
             <div className="py-24 bg-gray-50 text-center px-4">
                <div className="max-w-3xl mx-auto">
                    <h2 className="text-3xl md:text-4xl font-black text-gray-900 mb-6">Ready to Partner With Us?</h2>
                    <p className="text-gray-500 text-lg mb-10">
                        Join hundreds of retailers across Canada who trust Zam Zam Import Export for their supply needs.
                    </p>
                    <div className="flex flex-col md:flex-row justify-center gap-4">
                         <Link 
                            href={route('contact')} 
                            className="bg-[#C41E3A] text-white px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest hover:bg-[#a91930] shadow-lg shadow-red-200 transition-all transform hover:-translate-y-1"
                        >
                            Contact Sales
                        </Link>
                         <Link 
                            href={route('shop.index')} 
                            className="bg-white text-gray-900 border-2 border-gray-200 px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest hover:border-[#C41E3A] hover:text-[#C41E3A] transition-all transform hover:-translate-y-1"
                        >
                            View Products
                        </Link>
                    </div>
                </div>
            </div>

        </CustomerLayout>
    );
}
