import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage, router } from '@inertiajs/react';

export default function Index({ addresses }) {
    const { auth } = usePage().props;

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this address?')) {
            router.delete(route('addresses.destroy', id));
        }
    };

    const handleSetDefault = (id) => {
        router.patch(route('addresses.set-default', id));
    };

    return (
        <AuthenticatedLayout title="My Addresses">
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white">Addresses</h2>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Manage your shipping and billing addresses.
                        </p>
                    </div>
                     <Link
                        href={route('addresses.create')}
                        className="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors border border-transparent rounded-xl bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] shadow-sm transform hover:-translate-y-0.5"
                    >
                        <svg className="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Address
                    </Link>
                </div>

                {addresses.length === 0 ? (
                    <div className="text-center py-12 bg-white dark:bg-[#1E1E1E] rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                        <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 className="mt-2 text-sm font-medium text-gray-900 dark:text-white">No addresses</h3>
                        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding a new address.</p>
                        <div className="mt-6">
                            <Link
                                href={route('addresses.create')}
                                className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A]"
                            >
                                <svg className="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fillRule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clipRule="evenodd" />
                                </svg>
                                Add Address
                            </Link>
                        </div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {addresses.map((address) => (
                            <div 
                                key={address.id} 
                                className={`group relative flex flex-col justify-between bg-white dark:bg-[#1E1E1E] rounded-3xl p-6 transition-all duration-300 ${
                                    address.is_default 
                                        ? 'border-2 border-[#C41E3A] shadow-md shadow-[#C41E3A]/10' 
                                        : 'border border-gray-100 dark:border-gray-800 hover:border-gray-200 dark:hover:border-gray-700 hover:shadow-xl hover:-translate-y-1'
                                }`}
                            >
                                {address.is_default && (
                                    <div className="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#C41E3A] text-white text-[10px] font-bold uppercase tracking-widest py-1 px-3 rounded-full shadow-sm z-10">
                                        Default {address.type}
                                    </div>
                                )}
                                
                                <div>
                                    <div className="flex items-start justify-between mb-4">
                                        <div className={`h-12 w-12 rounded-2xl flex items-center justify-center transition-colors ${
                                            address.type === 'shipping' 
                                                ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40' 
                                                : 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/40'
                                        }`}>
                                            {address.type === 'shipping' ? (
                                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                            ) : (
                                                 <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                            )}
                                        </div>
                                        <div className="flex space-x-2">
                                            <Link
                                                href={route('addresses.edit', address.id)}
                                                className="p-2 text-gray-400 hover:text-[#C41E3A] dark:hover:text-white transition-colors rounded-full hover:bg-gray-50 dark:hover:bg-gray-800"
                                                title="Edit Address"
                                            >
                                                 <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </Link>
                                            <button
                                                onClick={() => handleDelete(address.id)}
                                                className="p-2 text-gray-400 hover:text-red-600 transition-colors rounded-full hover:bg-red-50 dark:hover:bg-red-900/20"
                                                title="Delete Address"
                                            >
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">
                                        {address.name}
                                    </h3>

                                    <div className="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                        <p className="line-clamp-2 leading-relaxed">
                                            {address.address_line_1}
                                            {address.address_line_2 && <>, <br/>{address.address_line_2}</>}
                                        </p>
                                        <p className="font-medium">
                                            {address.city}, {address.state} {address.postal_code}
                                        </p>
                                        <p className="flex items-center text-gray-500 dark:text-gray-500 pt-2">
                                            <span className="w-4 h-4 mr-2 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-[10px]">🏳️</span>
                                            {address.country}
                                        </p>
                                        {address.phone && (
                                            <p className="flex items-center text-gray-500 dark:text-gray-500">
                                                <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                                {address.phone}
                                            </p>
                                        )}
                                    </div>
                                </div>

                                {!address.is_default && (
                                    <button
                                        onClick={() => handleSetDefault(address.id)}
                                        className="w-full mt-6 py-2.5 px-4 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:border-[#C41E3A] hover:text-[#C41E3A] dark:hover:border-[#C41E3A] dark:hover:text-[#C41E3A] bg-transparent hover:bg-[#C41E3A]/5 transition-all duration-200 flex items-center justify-center gap-2 group-hover:block"
                                    >
                                        Set as Default
                                    </button>
                                )}
                                
                                {address.is_default && (
                                     <div className="mt-6 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 text-sm font-semibold border border-green-100 dark:border-green-900/30">
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" /></svg>
                                        Primary Address
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
