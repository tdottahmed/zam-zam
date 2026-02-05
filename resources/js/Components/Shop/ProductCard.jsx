import { Link } from '@inertiajs/react';
import StorageImage from '../StorageImage';

export default function ProductCard({ product }) {
    return (
        <div className="bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 border border-transparent hover:border-[#C41E3A] overflow-hidden group flex flex-col h-full">
            <div className="relative aspect-square p-4 bg-gray-50">
               <div className="w-full h-full flex items-center justify-center overflow-hidden">
                    <StorageImage
                        path={product.image}
                        name={product.name}
                        className="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500"
                    />
               </div>
                
                {/* Quick Action Overlay (Future enhancement) */}
                <div className="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-white/90 backdrop-blur-sm p-4 border-t flex justify-center opacity-0 group-hover:opacity-100">
                    <button className="bg-[#C41E3A] text-white px-4 py-2 rounded text-sm font-semibold hover:bg-[#a01830] transition w-full">
                        Add to Cart
                    </button>
                </div>
            </div>

            <div className="p-4 flex flex-col flex-1">
                {product.brand && (
                    <span className="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                        {product.brand.name}
                    </span>
                )}
                
                <h3 className="text-gray-900 font-semibold mb-2 line-clamp-2 min-h-[3rem] group-hover:text-[#C41E3A] transition-colors">
                    {product.name}
                </h3>
                
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
