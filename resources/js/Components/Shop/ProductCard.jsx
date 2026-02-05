import { Link, useForm } from '@inertiajs/react';
import StorageImage from '../StorageImage';
import useCartStore from '../../Stores/useCartStore';

export default function ProductCard({ product }) {
    const { openCart } = useCartStore();
    const { data, setData, post, processing, recentlySuccessful } = useForm({
        product_id: product.id,
        quantity: 1,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('cart.add'), {
            preserveScroll: true,
            onSuccess: () => {
                openCart();
            }
        });
    };

    return (
        <div className="bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 border border-transparent hover:border-[#C41E3A] overflow-hidden group flex flex-col h-full relative">
            <Link href={route('shop.show', product.id)} className="relative aspect-square p-4 bg-gray-50 block cursor-pointer">
                <div className="w-full h-full flex items-center justify-center overflow-hidden">
                    <StorageImage
                        path={product.image}
                        name={product.name}
                        className="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500"
                    />
                </div>
            </Link>

            {/* Quick Action Overlay - Now with Quantity */}
            <div className="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-white/95 backdrop-blur-sm p-4 border-t opacity-0 group-hover:opacity-100 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                <form onSubmit={submit} className="flex gap-2">
                    <input 
                        type="number" 
                        min="1" 
                        value={data.quantity}
                        onChange={(e) => setData('quantity', parseInt(e.target.value))}
                        className="w-16 rounded border-gray-300 text-center text-sm focus:border-[#C41E3A] focus:ring-[#C41E3A] p-2"
                        onClick={(e) => e.stopPropagation()} // Prevent link click if wrapped? (It's not wrapped here)
                    />
                    <button 
                        type="submit" 
                        disabled={processing}
                        className={`flex-1 bg-[#C41E3A] text-white px-2 py-2 rounded text-sm font-semibold hover:bg-[#a01830] transition flex justify-center items-center ${processing ? 'opacity-70' : ''}`}
                    >
                        {processing ? 'Adding...' : 'Add'}
                    </button>
                    {recentlySuccessful && (
                        <div className="absolute -top-10 left-0 right-0 text-center">
                            <span className="bg-green-600 text-white text-xs px-2 py-1 rounded shadow-lg">Added!</span>
                        </div>
                    )}
                </form>
            </div>

            <div className="p-4 flex flex-col flex-1 relative bg-white">
                {product.brand && (
                    <span className="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                        {product.brand.name}
                    </span>
                )}
                
                <Link href={route('shop.show', product.id)} className="block">
                    <h3 className="text-gray-900 font-semibold mb-2 line-clamp-2 min-h-[3rem] group-hover:text-[#C41E3A] transition-colors">
                        {product.name}
                    </h3>
                </Link>
                
                <div className="mt-auto pt-4 flex items-center justify-between border-t border-gray-100">
                    <div>
                         {product.unit && (
                            <p className="text-xs text-gray-500 mb-1">
                                {product.unit_value} {product.unit.name}
                            </p>
                        )}
                        <span className="text-lg font-bold text-[#C41E3A]">
                            ${Number(product.unit_price).toFixed(2)}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
