import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import ProductCard from '@/Components/Shop/ProductCard';

export default function Index({ products }) {
    return (
        <AuthenticatedLayout title="My Wishlist">
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white">My Wishlist</h2>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Saved items for later.
                        </p>
                    </div>
                </div>

                {products.length === 0 ? (
                    <div className="text-center py-16 bg-white dark:bg-[#1E1E1E] rounded-3xl border border-dashed border-gray-300 dark:border-gray-700">
                        <div className="mx-auto h-20 w-20 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center text-[#C41E3A]">
                            <svg className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 className="mt-4 text-lg font-medium text-gray-900 dark:text-white">Your wishlist is empty</h3>
                        <p className="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                            Save items you love to your wishlist and review them later.
                        </p>
                        <div className="mt-8">
                            <Link 
                                href={route('shop.index')} 
                                className="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-[#C41E3A] hover:bg-[#a01830] shadow-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
                            >
                                Browse Products
                            </Link>
                        </div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        {products.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
