import CustomerLayout from '../../Layouts/CustomerLayout';
import { Head, Link } from '@inertiajs/react';
import StorageImage from '../../Components/StorageImage';

export default function Brands({ brands }) {
    return (
        <CustomerLayout>
            <Head title="Our Brands" />

             {/* Header */}
             <div className="bg-gray-50 py-8 px-6 lg:px-12 border-b border-gray-100">
                <div className="max-w-7xl mx-auto text-center">
                     <h1 className="text-4xl font-bold text-gray-900 mb-4">Our Trusted Brands</h1>
                     <p className="text-gray-600 max-w-2xl mx-auto">
                        Explore our wide collection of premium South Asian brands. Click on any brand to see their products.
                     </p>
                </div>
            </div>

            <div className="max-w-7xl mx-auto py-16 px-6 lg:px-12">
                <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                    {brands.map((brand) => (
                        <Link 
                            key={brand.id} 
                            href={route('shop.index', { brand: brand.slug })}
                            className="bg-white p-8 rounded-xl shadow-sm hover:shadow-lg transition-all border border-gray-100 hover:border-[#C41E3A] flex flex-col items-center group text-center h-full justify-center"
                        >
                            <div className="w-24 h-24 mb-6 flex items-center justify-center">
                                <StorageImage 
                                    path={brand.logo} 
                                    name={brand.name} 
                                    className="max-h-full max-w-full object-contain grayscale group-hover:grayscale-0 transition-all duration-500"
                                />
                            </div>
                            <h3 className="font-bold text-gray-800 group-hover:text-[#C41E3A] text-lg">{brand.name}</h3>
                        </Link>
                    ))}
                </div>
            </div>
        </CustomerLayout>
    );
}
