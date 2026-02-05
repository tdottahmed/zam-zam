import { Link, usePage, router } from '@inertiajs/react'; // Removed useForm as we'll use router for cleaner actions
import { useState } from 'react'; // Added useState for local loading states if needed
import StorageImage from '../StorageImage';
import useCartStore from '../../Stores/useCartStore';

export default function ProductCard({ product }) {
    const { cart } = usePage().props;
    const { openCart } = useCartStore();
    const [loading, setLoading] = useState(false);

    // Check if product is in cart
    const cartItem = cart?.items?.find(item => item.product_id === product.id);
    const quantity = cartItem ? cartItem.quantity : 0;

    const addToCart = (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (loading) return;
        setLoading(true);

        router.post(route('cart.add'), {
            product_id: product.id,
            quantity: 1
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setLoading(false);
                openCart();
            },
            onError: () => setLoading(false)
        });
    };

    const updateQuantity = (newQty) => {
        if (loading || !cartItem) return;
        
        // If 0, remove
        if (newQty < 1) {
             setLoading(true);
             router.delete(route('cart.destroy', cartItem.id), {
                preserveScroll: true,
                onSuccess: () => setLoading(false),
                onError: () => setLoading(false)
             });
             return;
        }

        setLoading(true);
        router.patch(route('cart.update', cartItem.id), {
            quantity: newQty
        }, {
            preserveScroll: true,
            onSuccess: () => setLoading(false),
            onError: () => setLoading(false)
        });
    };

    return (
        <div className={`bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 border border-transparent overflow-hidden group flex flex-col h-full relative ${quantity > 0 ? 'border-[#C41E3A] ring-1 ring-[#C41E3A] ring-opacity-50' : 'hover:border-[#C41E3A]'}`}>
            
            {/* In Cart Badge */}
            {quantity > 0 && (
                <div className="absolute top-2 right-2 z-20">
                    <span className="bg-[#C41E3A] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm uppercase tracking-wider">
                        In Cart
                    </span>
                </div>
            )}

            <Link href={route('shop.show', product.id)} className="relative aspect-square p-4 bg-gray-50 block cursor-pointer">
                <div className="w-full h-full flex items-center justify-center overflow-hidden">
                    <StorageImage
                        path={product.image}
                        name={product.name}
                        className="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500"
                    />
                </div>
            </Link>

            
            <div className={`absolute inset-x-0 bottom-0 transition-transform duration-300 bg-white/95 backdrop-blur-sm p-4 border-t z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] ${quantity > 0 ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100'}`}>
                {quantity > 0 ? (
                    <div className="flex items-center justify-between w-full bg-[#C41E3A] text-white rounded-lg shadow-md overflow-hidden" onClick={(e) => e.stopPropagation()}>
                        <button 
                            onClick={(e) => { e.stopPropagation(); updateQuantity(quantity - 1); }}
                            className="w-10 h-10 flex items-center justify-center hover:bg-black/10 transition active:bg-black/20"
                            disabled={loading}
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2.5} stroke="currentColor" className="w-4 h-4">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 12h-15" />
                            </svg>
                        </button>
                        
                        <span className="font-bold text-base min-w-[1.5rem] text-center select-none">
                            {loading ? (
                                <svg className="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            ) : quantity}
                        </span>

                        <button 
                             onClick={(e) => { e.stopPropagation(); updateQuantity(quantity + 1); }}
                             className="w-10 h-10 flex items-center justify-center hover:bg-black/10 transition active:bg-black/20"
                             disabled={loading}
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2.5} stroke="currentColor" className="w-4 h-4">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                ) : (
                    <button 
                        onClick={addToCart}
                        disabled={loading}
                        className="w-full bg-white text-gray-900 border border-gray-200 px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-[#C41E3A] hover:text-white hover:border-[#C41E3A] transition-all duration-300 flex items-center justify-center gap-2 group/btn"
                    >
                         {/* Icon */}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-4 h-4 text-gray-400 group-hover/btn:text-white transition-colors">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 5c.07.277-.144.516-.41.488a10.977 10.977 0 0 1-5.26 1.508 10.979 10.979 0 0 1-5.26-1.508c-.266.028-.48-.21-.41-.488l1.263-5a.49.49 0 0 1 .454-.368 18.243 18.243 0 0 0 3.955-.42 18.22 18.22 0 0 0 3.955.42c.174 0 .332.13.454.368Z" />
                        </svg>
                        {loading ? 'Adding...' : 'Add to Cart'}
                    </button>
                )}
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
