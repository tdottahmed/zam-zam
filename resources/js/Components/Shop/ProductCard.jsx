import { Link, usePage, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import StorageImage from '../StorageImage';
import useCartStore from '../../Stores/useCartStore';

export default function ProductCard({ product }) {
    const { cart: propsCart, wishlist = [] } = usePage().props; // wishlist defaults to []
    const { openCart, cart: storeCart, updateQuantity } = useCartStore();
    const [loading, setLoading] = useState(false);

    const isWishlisted = wishlist.includes(product.id);

    // Use store cart for UI
    // Fallback to propsCart if store is empty, but propsCart should be synced by the Layout/Header
    const cart = storeCart && storeCart.items ? storeCart : (propsCart || { items: [] });

    // Check if product is in cart
    // Use semi-strict equality (==) to handle potential string/int mismatch for IDs
    const cartItem = cart?.items?.find(item => item.product_id == product.id);
    const quantity = cartItem ? cartItem.quantity : 0;

    const addToCart = (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        
        if (loading) return;
        setLoading(true);

        // Let's prioritize that.
        
        import('@inertiajs/react').then(({ router }) => {
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
        });
    };

    const handleUpdateQuantity = (newQty) => {
        if (!cartItem) return;
        
        // If 0, remove
        if (newQty < 1) {
             // For remove, we can use the store's removeItem which is optimistic
             // But wait, removeItem expects Item ID. cartItem.id is available.
             useCartStore.getState().removeItem(cartItem.id); 
             return;
        }

        // Optimistic update via store
        updateQuantity(cartItem.id, newQty);
    };

    const toggleWishlist = (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (isWishlisted) {
            router.delete(route('wishlist.destroy', product.id), {
                preserveScroll: true,
            });
        } else {
            router.post(route('wishlist.store'), {
                product_id: product.id
            }, {
                preserveScroll: true,
            });
        }
    };

    return (
        <div className={`group relative flex flex-col h-full bg-white rounded-3xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-500 ease-out border overflow-hidden ${quantity > 0 ? 'border-[#C41E3A] ring-1 ring-[#C41E3A] ring-opacity-50' : 'border-transparent hover:border-gray-100'}`}>
            
            {/* Image Area with Gradient Overlay on Hover */}
            <div className="relative aspect-[4/5] bg-gray-50 overflow-hidden">
                <Link href={route('shop.show', product.id)} className="block w-full h-full p-6 cursor-pointer">
                    <StorageImage
                        path={product.image}
                        name={product.name}
                        className="w-full h-full object-contain mix-blend-multiply transition-transform duration-700 ease-in-out group-hover:scale-110 will-change-transform"
                    />
                </Link>
                
                {/* Floating Badges */}
                <div className="absolute top-4 left-4 right-4 flex justify-between items-start z-10 pointers-events-none">
                    {/* Unit Badge (Red Pill) */}
                    {product.unit && (
                        <span className="bg-[#C41E3A] text-white shadow-lg shadow-[#C41E3A]/20 text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider backdrop-blur-sm">
                            {product.unit.name}
                        </span>
                    )}
                    
                    {/* Wishlist Button (Aesthetic) */}
                    <button 
                        onClick={toggleWishlist}
                        className={`w-8 h-8 rounded-full backdrop-blur-md shadow-sm border flex items-center justify-center transition-colors duration-200 group/heart ${isWishlisted ? 'bg-[#C41E3A] border-[#C41E3A] text-white' : 'bg-white/80 border-white/50 text-gray-400 hover:text-[#C41E3A] hover:bg-white'}`}
                    >
                        <svg className="w-4 h-4 transition-transform group-hover/heart:scale-110" fill={isWishlisted ? "currentColor" : "none"} viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>

                {/* In Cart Indicator (Subtle overlay) */}
                {quantity > 0 && (
                    <div className="absolute top-4 right-14 bg-black/5 backdrop-blur-md border border-white/20 text-gray-900 text-[10px] font-bold px-2.5 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                        In Cart
                    </div>
                )}
            </div>

            {/* Content Area */}
            <div className="flex flex-col flex-1 p-5 relative bg-white">
                <div className="flex-1">
                    {product.brand && (
                        <h4 className="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                            {product.brand.name}
                        </h4>
                    )}
                    
                    <Link href={route('shop.show', product.id)} className="block group-hover:text-[#C41E3A] transition-colors duration-200">
                        <h3 className="text-gray-900 font-bold text-[15px] leading-snug line-clamp-2 min-h-[2.5rem]">
                            {product.name}
                        </h3>
                    </Link>
                </div>

                <div className="mt-4 flex items-center justify-between">
                    <div className="flex flex-col">
                        <span className="text-xl font-black text-gray-900 tracking-tight">
                            ${Number(product.unit_price).toFixed(2)}
                        </span>
                    </div>

                    {/* Add Button / Counter */}
                    <div className="relative z-20">
                         {quantity > 0 ? (
                            <div className="flex items-center bg-[#C41E3A] text-white rounded-full shadow-lg shadow-[#C41E3A]/30 p-1 h-10 ring-2 ring-offset-1 ring-[#C41E3A] animate-in fade-in zoom-in duration-200">
                                <button 
                                    onClick={(e) => { e.stopPropagation(); handleUpdateQuantity(parseInt(quantity) - 1); }}
                                    className="w-8 h-8 rounded-full flex items-center justify-center hover:bg-black/10 transition-colors"
                                    disabled={loading}
                                >
                                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M20 12H4" />
                                    </svg>
                                </button>
                                <span className="font-bold text-sm min-w-[1.5rem] text-center px-1 select-none tabular-nums">
                                    {quantity}
                                </span>
                                <button 
                                     onClick={(e) => { e.stopPropagation(); handleUpdateQuantity(parseInt(quantity) + 1); }}
                                     className="w-8 h-8 rounded-full flex items-center justify-center hover:bg-black/10 transition-colors"
                                     disabled={loading}
                                >
                                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        ) : (
                            <button 
                                onClick={addToCart}
                                disabled={loading}
                                className="h-10 w-10 rounded-full bg-gray-100 hover:bg-[#C41E3A] hover:text-white hover:shadow-lg hover:shadow-[#C41E3A]/30 text-gray-900 transition-all duration-300 flex items-center justify-center group/btn"
                                title="Add to Cart"
                            >
                                <svg className="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
