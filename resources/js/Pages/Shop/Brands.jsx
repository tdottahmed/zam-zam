import CustomerLayout from '../../Layouts/CustomerLayout';
import { Head, Link } from '@inertiajs/react';
import StorageImage from '../../Components/StorageImage';
import Breadcrumb from '../../Components/Breadcrumb';

export default function Brands({ brands }) {
    return (
        <CustomerLayout>
            <Head title="Our Brands" />

            <Breadcrumb
                title="Our Trusted Brands"
                links={[{ label: 'Brands', active: true }]}
            />

            <div className="max-w-[1920px] mx-auto py-12 px-4 sm:px-6 lg:px-12">
                <p className="text-center text-gray-500 max-w-2xl mx-auto mb-12 text-sm md:text-base">
                    Explore products from our partner brands. Click any brand to see their full range.
                </p>

                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6 lg:gap-8">
                    {brands.map((brand) => (
                        <Link
                            key={brand.id}
                            href={route('shop.index', { brand: [brand.slug] })}
                            className="group bg-white rounded-2xl p-6 sm:p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:border-[#C41E3A]/20 hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center relative overflow-hidden focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/30 focus:ring-offset-2"
                        >
                            <div className="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-500" />

                            <div className="relative z-10 w-36 h-36 sm:w-40 sm:h-40 mb-5 flex items-center justify-center p-5 bg-gray-50 rounded-2xl group-hover:bg-white group-hover:scale-105 transition-all duration-300 ring-1 ring-gray-100 group-hover:ring-[#C41E3A]/10">
                                <StorageImage
                                    path={brand.image}
                                    name={brand.name}
                                    className="max-h-full max-w-full object-contain"
                                />
                            </div>

                            <div className="relative z-10 text-center">
                                <h3 className="font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors text-sm sm:text-base">
                                    {brand.name}
                                </h3>
                                <span className="mt-2 inline-flex items-center gap-1.5 text-xs text-gray-400 font-medium uppercase tracking-wider opacity-0 transform translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                                    View products
                                    <svg className="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </span>
                            </div>

                            <div className="absolute top-4 right-4 text-gray-300 group-hover:text-[#C41E3A] transform translate-x-1 -translate-y-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 group-hover:translate-y-0 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-5 h-5">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                </svg>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </CustomerLayout>
    );
}
