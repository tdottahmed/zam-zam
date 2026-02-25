import React, { useEffect, useState } from 'react';

export default function MultiOrderModal({ show, onClose, product, onSelect }) {
    const [isAnimatingOut, setIsAnimatingOut] = useState(false);

    useEffect(() => {
        if (!show) {
            setIsAnimatingOut(false);
        }
    }, [show]);

    const handleClose = () => {
        setIsAnimatingOut(true);
        setTimeout(() => onClose(), 200); // Wait for animation
    };

    if (!show && !isAnimatingOut) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity duration-200">
            <div 
                className={`bg-white dark:bg-[#1E1E1E] rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-gray-100 dark:border-gray-800 transform transition-all duration-200 ${
                    isAnimatingOut || !show ? 'scale-95 opacity-0' : 'scale-100 opacity-100'
                }`}
            >
                <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30">
                    <h3 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg className="w-5 h-5 text-[#C41E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Select Purchase
                    </h3>
                    <button 
                        onClick={handleClose} 
                        className="p-1 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div className="p-6">
                    <p className="text-sm text-gray-600 dark:text-gray-400 mb-5">
                        You have purchased <strong>{product?.product_name}</strong> in multiple orders. Which specific order would you like to return it from?
                    </p>
                    <div className="space-y-3 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                        {product?.orders.map((order) => (
                            <button
                                key={order.order_id}
                                onClick={() => {
                                    setIsAnimatingOut(true);
                                    setTimeout(() => onSelect(order.order_id, product.product_id), 150);
                                }}
                                className="w-full flex items-center justify-between p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[#C41E3A] dark:hover:border-[#C41E3A] hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-all text-left focus:outline-none focus:ring-2 focus:ring-[#C41E3A] group relative overflow-hidden"
                            >
                                <div className="absolute inset-y-0 left-0 w-1 bg-[#C41E3A] scale-y-0 group-hover:scale-y-100 transition-transform origin-center"></div>
                                <div className="pl-2">
                                    <div className="font-semibold text-gray-900 dark:text-white">Order #{order.order_id}</div>
                                    <div className="text-sm text-gray-500 mt-0.5">Purchased on {order.order_date}</div>
                                </div>
                                <div className="text-right">
                                    <div className="inline-flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs font-bold px-2 py-1 rounded-md mb-1">
                                        {order.quantity_bought}x Purchased
                                    </div>
                                    <div className="text-sm font-medium text-gray-900 dark:text-white">${Number(order.unit_price).toFixed(2)} / ea</div>
                                </div>
                            </button>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
