import CustomerLayout from '../../Layouts/CustomerLayout';
import { Head, Link } from '@inertiajs/react';
import StorageImage from '../../Components/StorageImage';
import Breadcrumb from '../../Components/Breadcrumb';

export default function Brands({ brands }) {
    return (
        <CustomerLayout>
            <Head title="Our Brands" />

             {/* Header / Breadcrumb Area */}
             <Breadcrumb 
                title="Our Trusted Brands" 
                links={[
                    { label: 'Brands', active: true }
                ]} 
            />

            <div className="max-w-[1920px] mx-auto py-12 px-6 lg:px-12">
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 lg:gap-8">
                    {brands.map((brand) => (
                        <Link 
                            key={brand.id} 
                            href={route('shop.index', { brand: [brand.slug] })}
                            className="group bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:border-[#C41E3A]/20 transition-all duration-300 flex flex-col items-center justify-center relative overflow-hidden"
                        >
                            {/* Hover Background Effect */}
                            <div className="absolute inset-0 bg-gradient-to-br from-gray-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            <div className="relative z-10 w-32 h-32 mb-6 flex items-center justify-center p-4 bg-gray-50 rounded-full group-hover:bg-white group-hover:scale-110 transition-all duration-300">
                                <StorageImage 
                                    path={brand.image} 
                                    name={brand.name} 
                                    className="max-h-full max-w-full object-contain grayscale group-hover:grayscale-0 transition-all duration-500 mix-blend-multiply"
                                />
                            </div>
                            
                            <div className="relative z-10 text-center">
                                <h3 className="font-bold text-gray-900 group-hover:text-[#C41E3A] transition-colors">{brand.name}</h3>
                                <div className="mt-2 text-xs text-gray-400 font-medium uppercase tracking-widest opacity-0 transform translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                                    View Products
                                </div>
                            </div>

                             {/* Arrow Indicator */}
                             <div className="absolute top-4 right-4 text-gray-300 group-hover:text-[#C41E3A] transform translate-x-2 -translate-y-2 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 group-hover:translate-y-0 transition-all duration-300">
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
