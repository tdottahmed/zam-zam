import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Edit({ address }) {
    const { data, setData, put, processing, errors } = useForm({
        type: address.type || 'shipping',
        name: address.name || '',
        email: address.email || '',
        phone: address.phone || '',
        address_line_1: address.address_line_1 || '',
        address_line_2: address.address_line_2 || '',
        city: address.city || '',
        state: address.state || '',
        postal_code: address.postal_code || '',
        country: address.country || '',
        is_default: address.is_default || false,
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('addresses.update', address.id));
    };

    return (
        <AuthenticatedLayout title="Edit Address">
            <div className="max-w-3xl mx-auto space-y-6">
                 <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white">Edit Address</h2>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Update your shipping or billing address.
                        </p>
                    </div>
                     <Link
                        href={route('addresses.index')}
                        className="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-[#2A2A2A] dark:text-gray-300 dark:border-gray-700 dark:hover:bg-[#333]"
                    >
                        Cancel
                    </Link>
                </div>

                <div className="bg-white dark:bg-[#1E1E1E] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                    <form onSubmit={submit} className="space-y-6">
                        {/* Address Type */}
                        <div>
                            <label className="text-sm font-medium text-gray-700 dark:text-gray-300">Address Type</label>
                            <div className="mt-2 grid grid-cols-2 gap-3 sm:grid-cols-4">
                                {['shipping', 'billing'].map((type) => (
                                    <div key={type} className="flex items-center">
                                        <input
                                            id={type}
                                            name="type"
                                            type="radio"
                                            checked={data.type === type}
                                            onChange={(e) => setData('type', e.target.value)}
                                            value={type}
                                            className="h-4 w-4 text-[#C41E3A] border-gray-300 focus:ring-[#C41E3A] dark:bg-gray-700 dark:border-gray-600"
                                        />
                                        <label htmlFor={type} className="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
                                            {type}
                                        </label>
                                    </div>
                                ))}
                            </div>
                            {errors.type && <p className="mt-1 text-sm text-red-600">{errors.type}</p>}
                        </div>

                        <div className="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                             {/* Name */}
                            <div className="sm:col-span-2">
                                <label htmlFor="name" className="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                <div className="mt-1">
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        autoComplete="name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.name && <p className="mt-1 text-sm text-red-600">{errors.name}</p>}
                                </div>
                            </div>
                            
                            {/* Email */}
                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700 dark:text-gray-300">Email (Optional)</label>
                                <div className="mt-1">
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        autoComplete="email"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.email && <p className="mt-1 text-sm text-red-600">{errors.email}</p>}
                                </div>
                            </div>

                             {/* Phone */}
                            <div>
                                <label htmlFor="phone" className="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone (Optional)</label>
                                <div className="mt-1">
                                    <input
                                        type="tel"
                                        name="phone"
                                        id="phone"
                                        autoComplete="tel"
                                        value={data.phone}
                                        onChange={(e) => setData('phone', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.phone && <p className="mt-1 text-sm text-red-600">{errors.phone}</p>}
                                </div>
                            </div>
                            
                             {/* Address Line 1 */}
                            <div className="sm:col-span-2">
                                <label htmlFor="address_line_1" className="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 1</label>
                                <div className="mt-1">
                                    <input
                                        type="text"
                                        name="address_line_1"
                                        id="address_line_1"
                                        value={data.address_line_1}
                                        onChange={(e) => setData('address_line_1', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.address_line_1 && <p className="mt-1 text-sm text-red-600">{errors.address_line_1}</p>}
                                </div>
                            </div>

                            {/* Address Line 2 */}
                             <div className="sm:col-span-2">
                                <label htmlFor="address_line_2" className="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 2 (Optional)</label>
                                <div className="mt-1">
                                    <input
                                        type="text"
                                        name="address_line_2"
                                        id="address_line_2"
                                        value={data.address_line_2}
                                        onChange={(e) => setData('address_line_2', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.address_line_2 && <p className="mt-1 text-sm text-red-600">{errors.address_line_2}</p>}
                                </div>
                            </div>

                            {/* City */}
                             <div>
                                <label htmlFor="city" className="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                <div className="mt-1">
                                    <input
                                        type="text"
                                        name="city"
                                        id="city"
                                        value={data.city}
                                        onChange={(e) => setData('city', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.city && <p className="mt-1 text-sm text-red-600">{errors.city}</p>}
                                </div>
                            </div>

                            {/* State */}
                             <div>
                                <label htmlFor="state" className="block text-sm font-medium text-gray-700 dark:text-gray-300">State / Province</label>
                                <div className="mt-1">
                                    <input
                                        type="text"
                                        name="state"
                                        id="state"
                                        value={data.state}
                                        onChange={(e) => setData('state', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.state && <p className="mt-1 text-sm text-red-600">{errors.state}</p>}
                                </div>
                            </div>

                            {/* Postal Code */}
                             <div>
                                <label htmlFor="postal_code" className="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code</label>
                                <div className="mt-1">
                                    <input
                                        type="text"
                                        name="postal_code"
                                        id="postal_code"
                                        value={data.postal_code}
                                        onChange={(e) => setData('postal_code', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.postal_code && <p className="mt-1 text-sm text-red-600">{errors.postal_code}</p>}
                                </div>
                            </div>

                            {/* Country */}
                             <div>
                                <label htmlFor="country" className="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                                <div className="mt-1">
                                    <input
                                        type="text"
                                        name="country"
                                        id="country"
                                        value={data.country}
                                        onChange={(e) => setData('country', e.target.value)}
                                        className="block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] sm:text-sm dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-white"
                                    />
                                    {errors.country && <p className="mt-1 text-sm text-red-600">{errors.country}</p>}
                                </div>
                            </div>

                            <div className="sm:col-span-2">
                                <div className="flex items-center">
                                    <input
                                        id="is_default"
                                        name="is_default"
                                        type="checkbox"
                                        checked={data.is_default}
                                        onChange={(e) => setData('is_default', e.target.checked)}
                                        className="h-4 w-4 text-[#C41E3A] border-gray-300 rounded focus:ring-[#C41E3A] dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <label htmlFor="is_default" className="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                        Set as default address
                                    </label>
                                </div>
                             </div>

                        </div>

                        <div className="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C41E3A] shadow-sm transition-all duration-200"
                            >
                                {processing ? 'Updating...' : 'Update Address'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
