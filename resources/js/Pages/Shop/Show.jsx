import React, { useState } from "react";
import CustomerLayout from "../../Layouts/CustomerLayout";
import { Head, useForm, usePage } from "@inertiajs/react";
import StorageImage from "../../Components/StorageImage";
import useCartStore from "../../Stores/useCartStore";
import Breadcrumb from "../../Components/Breadcrumb";

export default function Show({ product }) {
    const { cart: propsCart } = usePage().props;
    const {
        openCart,
        cart: storeCart,
        updateQuantity,
        removeItem,
    } = useCartStore();
    const cart = storeCart?.items
        ? storeCart
        : { items: propsCart?.items ?? [] };
    const cartItem = cart?.items?.find((item) => item.product_id == product.id);
    const inCartQty = cartItem ? cartItem.quantity : 0;
    const isInCart = inCartQty > 0;

    const { data, setData, post, processing, errors, recentlySuccessful } =
        useForm({
            product_id: product.id,
            quantity: 1,
        });
    const [addLoading, setAddLoading] = useState(false);

    const submitAddToCart = (e) => {
        e.preventDefault();
        setAddLoading(true);
        post(route("cart.add"), {
            preserveScroll: true,
            onSuccess: () => {
                setAddLoading(false);
                openCart();
            },
            onError: () => setAddLoading(false),
        });
    };

    const handleUpdateQty = (newQty) => {
        if (!cartItem) return;
        if (newQty < 1) {
            removeItem(cartItem.id);
            return;
        }
        updateQuantity(cartItem.id, newQty);
    };

    const displayQty = isInCart ? inCartQty : data.quantity;
    const setDisplayQty = (v) => {
        if (isInCart) handleUpdateQty(v);
        else setData("quantity", Math.max(1, v));
    };

    // Price per stock unit (piece/dozen/box) for display; fallback to unit_price
    const pricePerStockUnit =
        product.price_per_stock_unit ?? product.unit_price;
    const stockUnitLabel = product.stock_unit_label?.toLowerCase() ?? "piece";
    const totalPrice = (pricePerStockUnit * displayQty).toFixed(2);

    return (
        <CustomerLayout>
            <Head title={product.name} />

            {/* Breadcrumb */}
            <Breadcrumb
                title={product.name}
                links={[
                    { label: "Shop", href: route("shop.index") },
                    ...(product.category
                        ? [{ label: product.category.name, active: false }]
                        : []),
                    { label: product.name, active: true },
                ]}
            />

            <div className="max-w-7xl mx-auto py-12 px-6 lg:px-12">
                <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-0 lg:gap-12">
                        {/* Product Image */}
                        <div className="p-8 bg-gray-50 flex items-center justify-center min-h-[400px] lg:min-h-[600px]">
                            <div className="w-full max-w-md aspect-square bg-white p-4 rounded-lg shadow-sm">
                                <StorageImage
                                    path={product.image}
                                    name={product.name}
                                    className="w-full h-full object-contain"
                                />
                            </div>
                        </div>

                        {/* Product Details */}
                        <div className="p-8 lg:py-12 lg:pr-12 flex flex-col">
                            {product.brand && (
                                <div className="mb-2">
                                    <span className="text-sm font-bold text-[#C41E3A] uppercase tracking-wider bg-red-50 px-3 py-1 rounded-full">
                                        {product.brand.name}
                                    </span>
                                </div>
                            )}

                            <h1 className="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                                {product.name}
                            </h1>

                            <div className="flex items-center gap-4 mb-6">
                                <span className="text-3xl font-extrabold text-[#C41E3A]">
                                    $
                                    {Number(
                                        product.price_per_stock_unit ??
                                            product.unit_price,
                                    ).toFixed(2)}
                                </span>
                                <span className="text-gray-500 text-lg">
                                    / per{" "}
                                    {product.stock_unit_label?.toLowerCase() ??
                                        "piece"}
                                </span>
                            </div>

                            <div className="prose prose-sm text-gray-600 mb-8 border-t border-b border-gray-100 py-6">
                                <h3 className="text-gray-900 font-semibold mb-2">
                                    Details
                                </h3>
                                {product.notes ? (
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: product.notes,
                                        }}
                                    />
                                ) : (
                                    <p>
                                        No specific details available for this
                                        product.
                                    </p>
                                )}
                            </div>

                            <div className="mt-auto">
                                <div className="flex flex-col gap-4">
                                    <div className="flex flex-col sm:flex-row gap-4 items-end sm:items-center">
                                        <div>
                                            <div className="flex items-center rounded-lg border-2 border-gray-200 bg-gray-50 overflow-hidden">
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        setDisplayQty(
                                                            displayQty - 1,
                                                        )
                                                    }
                                                    disabled={displayQty <= 1}
                                                    className="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-900 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-gray-50 transition-colors"
                                                    aria-label="Decrease quantity"
                                                >
                                                    <svg
                                                        className="w-5 h-5"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth={2.5}
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M20 12H4"
                                                        />
                                                    </svg>
                                                </button>
                                                <span className="w-14 text-center text-lg font-bold text-gray-900 tabular-nums select-none">
                                                    {displayQty}
                                                </span>
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        setDisplayQty(
                                                            displayQty + 1,
                                                        )
                                                    }
                                                    className="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors"
                                                    aria-label="Increase quantity"
                                                >
                                                    <svg
                                                        className="w-5 h-5"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        strokeWidth={2.5}
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M12 4v16m8-8H4"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        {isInCart ? (
                                            <div className="flex-1 w-full flex flex-col sm:flex-row gap-2 sm:items-center">
                                                <span className="inline-flex items-center gap-2 text-green-700 font-semibold text-sm">
                                                    <span className="w-2 h-2 rounded-full bg-green-500" />
                                                    In cart
                                                </span>
                                                <button
                                                    type="button"
                                                    onClick={openCart}
                                                    className="w-full sm:w-auto bg-gray-100 text-gray-800 px-6 py-3 rounded-md font-bold hover:bg-gray-200 transition duration-200"
                                                >
                                                    View cart (${totalPrice})
                                                </button>
                                            </div>
                                        ) : (
                                            <form
                                                onSubmit={submitAddToCart}
                                                className="flex-1 w-full"
                                            >
                                                <input
                                                    type="hidden"
                                                    name="quantity"
                                                    value={data.quantity}
                                                />
                                                <button
                                                    type="submit"
                                                    disabled={
                                                        processing || addLoading
                                                    }
                                                    className="w-full bg-[#C41E3A] text-white px-8 py-3 rounded-md font-bold text-lg hover:bg-[#a01830] transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 disabled:opacity-70"
                                                >
                                                    <span>
                                                        {addLoading ||
                                                        processing
                                                            ? "Adding…"
                                                            : "Add to Cart"}
                                                    </span>
                                                    {Number(totalPrice) > 0 && (
                                                        <span className="text-red-100 font-normal text-base">
                                                            (${totalPrice})
                                                        </span>
                                                    )}
                                                </button>
                                            </form>
                                        )}
                                    </div>
                                    {errors.quantity && (
                                        <div className="text-red-600 text-sm mt-1">
                                            {errors.quantity}
                                        </div>
                                    )}
                                    {errors.product_id && (
                                        <div className="text-red-600 text-sm mt-1">
                                            {errors.product_id}
                                        </div>
                                    )}
                                    {recentlySuccessful && !isInCart && (
                                        <div className="p-4 bg-green-50 text-green-700 rounded-md flex items-center animate-pulse">
                                            <svg
                                                className="w-5 h-5 mr-2 shrink-0"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>
                                            Successfully added to cart!
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </CustomerLayout>
    );
}
