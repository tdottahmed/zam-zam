import { Link, usePage, useForm, Head } from "@inertiajs/react";
import CustomerLayout from "../../Layouts/CustomerLayout";
import StorageImage from "../../Components/StorageImage";
import { useState, useEffect } from "react";
import useCartStore from "../../Stores/useCartStore";
import AddressSelector from "../../Components/Checkout/AddressSelector";
import { Transition } from "@headlessui/react";

export default function Checkout({ addresses = [], shippingMethods = [] }) {
    const { cart: propsCart, auth } = usePage().props;
    const {
        cart: storeCart,
        setCart,
        updateQuantity,
        removeItem: storeRemoveItem,
    } = useCartStore();

    // Sync store with props on mount
    useEffect(() => {
        if (propsCart) setCart(propsCart);
    }, [propsCart, setCart]);

    // Use store data for optimistic updates
    const cart =
        storeCart && storeCart.items
            ? storeCart
            : propsCart || { items: [], total: 0 };

    const [selectedAddressId, setSelectedAddressId] = useState(
        addresses.length > 0 ? addresses[0].id : "new",
    );

    const { data, setData, post, processing, errors } = useForm({
        email: auth.user.email || "",
        phone: auth.user.phone || "",
        shipping_address: {
            name: auth.user.name || "",
            address: "",
            city: "",
            zip: "",
            country: "",
        },
        payment_method: "cod",
        save_address: true,
        address_id: "new", // Track selected address ID
        shipping_method_id:
            shippingMethods.length > 0 ? shippingMethods[0].id : null,
    });

    // Effect to update form data when selected address changes
    useEffect(() => {
        if (selectedAddressId !== "new") {
            const address = addresses.find((a) => a.id === selectedAddressId);
            if (address) {
                setData((prev) => ({
                    ...prev,
                    address_id: address.id, // Set existing address ID
                    save_address: false, // Don't save strictly by default when existing is selected (or maybe we keep it false, user can't toggle it for existing anyway)
                    email: address.email || prev.email,
                    phone: address.phone || prev.phone,
                    shipping_address: {
                        name: address.name,
                        address: address.address_line_1,
                        city: address.city,
                        zip: address.postal_code,
                        country: address.country,
                        // We might need to handle address_line_2 and state if we add them to form later
                    },
                }));
            }
        } else {
            // Optional: Reset to user defaults or keep as is?
            // Keeping as is allows user to "edit" a selected address into a new one without losing data
            // But usually "New Address" implies starting fresh or from account defaults
            if (addresses.length > 0) {
                // If switching from a saved address to new, maybe clear fields?
                // For now let's keep it simple and just let them edit.
                // Actually, better to reset to auth defaults if they switch to new
                setData((prev) => ({
                    ...prev,
                    address_id: "new",
                    save_address: true, // Default to true for new addresses
                    email: auth.user.email || "",
                    phone: auth.user.phone || "",
                    shipping_address: {
                        name: auth.user.name || "",
                        address: "",
                        city: "",
                        zip: "",
                        country: "",
                    },
                }));
            }
        }
    }, [selectedAddressId]);

    const handleUpdateQuantity = (itemId, quantity) => {
        if (quantity < 1) {
            handleRemoveItem(itemId);
            return;
        }
        updateQuantity(itemId, quantity);
    };

    const handleRemoveItem = (itemId) => {
        if (confirm("Are you sure you want to remove this item?")) {
            storeRemoveItem(itemId);
        }
    };

    const submit = (e) => {
        e.preventDefault();
        post(route("checkout.store"));
    };

    return (
        <CustomerLayout>
            <Head title="Checkout" />
            <div className="bg-gray-50/50 min-h-screen py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <form
                        onSubmit={submit}
                        className="flex flex-col lg:flex-row gap-8 lg:gap-12"
                    >
                        {/* Left Column: Forms */}
                        <div className="flex-1 space-y-8">
                            {/* Saved Addresses */}
                            {addresses.length > 0 && (
                                <section className="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
                                    <h2 className="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                        <svg
                                            className="w-5 h-5 text-[#C41E3A]"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            />
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                        Saved Addresses
                                    </h2>
                                    <AddressSelector
                                        addresses={addresses}
                                        selectedAddressId={selectedAddressId}
                                        onChange={setSelectedAddressId}
                                        onAddNew={() =>
                                            setSelectedAddressId("new")
                                        }
                                    />
                                </section>
                            )}

                            {/* Contact & Shipping Info */}
                            <Transition
                                show={true}
                                enter="transition-opacity duration-300"
                                enterFrom="opacity-50"
                                enterTo="opacity-100"
                                leave="transition-opacity duration-150"
                                leaveFrom="opacity-100"
                                leaveTo="opacity-0"
                            >
                                <section
                                    className={`bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 ${selectedAddressId !== "new" ? "opacity-75 grayscale-[0.5] pointer-events-none" : ""}`}
                                >
                                    <div className="flex justify-between items-center mb-6">
                                        <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2">
                                            <svg
                                                className="w-5 h-5 text-[#C41E3A]"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                />
                                            </svg>
                                            {selectedAddressId === "new"
                                                ? "Contact & Shipping"
                                                : "Review Details"}
                                        </h2>
                                        {selectedAddressId !== "new" && (
                                            <span className="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full border border-amber-100">
                                                Saved Address Selected
                                            </span>
                                        )}
                                    </div>

                                    <div className="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                                        <div className="sm:col-span-2">
                                            <label className="block text-sm font-semibold text-gray-700 mb-1">
                                                Email Address
                                            </label>
                                            <input
                                                type="email"
                                                value={data.email}
                                                onChange={(e) =>
                                                    setData(
                                                        "email",
                                                        e.target.value,
                                                    )
                                                }
                                                className="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors py-2.5"
                                                placeholder="you@example.com"
                                            />
                                            {errors.email && (
                                                <p className="mt-1 text-sm text-red-600">
                                                    {errors.email}
                                                </p>
                                            )}
                                        </div>

                                        <div className="sm:col-span-2">
                                            <label className="block text-sm font-semibold text-gray-700 mb-1">
                                                Phone Number
                                            </label>
                                            <input
                                                type="text"
                                                value={data.phone}
                                                onChange={(e) =>
                                                    setData(
                                                        "phone",
                                                        e.target.value,
                                                    )
                                                }
                                                className="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors py-2.5"
                                                placeholder="+1 (555) 000-0000"
                                            />
                                            {errors.phone && (
                                                <p className="mt-1 text-sm text-red-600">
                                                    {errors.phone}
                                                </p>
                                            )}
                                        </div>

                                        <div className="sm:col-span-2 border-t border-gray-100 my-2"></div>

                                        <div className="sm:col-span-2">
                                            <label className="block text-sm font-semibold text-gray-700 mb-1">
                                                Full Name
                                            </label>
                                            <input
                                                type="text"
                                                value={
                                                    data.shipping_address.name
                                                }
                                                onChange={(e) =>
                                                    setData(
                                                        "shipping_address",
                                                        {
                                                            ...data.shipping_address,
                                                            name: e.target
                                                                .value,
                                                        },
                                                    )
                                                }
                                                className="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors py-2.5"
                                            />
                                            {errors[
                                                "shipping_address.name"
                                            ] && (
                                                <p className="mt-1 text-sm text-red-600">
                                                    {
                                                        errors[
                                                            "shipping_address.name"
                                                        ]
                                                    }
                                                </p>
                                            )}
                                        </div>

                                        <div className="sm:col-span-2">
                                            <label className="block text-sm font-semibold text-gray-700 mb-1">
                                                Address
                                            </label>
                                            <input
                                                type="text"
                                                value={
                                                    data.shipping_address
                                                        .address
                                                }
                                                onChange={(e) =>
                                                    setData(
                                                        "shipping_address",
                                                        {
                                                            ...data.shipping_address,
                                                            address:
                                                                e.target.value,
                                                        },
                                                    )
                                                }
                                                className="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors py-2.5"
                                                placeholder="123 Main St"
                                            />
                                            {errors[
                                                "shipping_address.address"
                                            ] && (
                                                <p className="mt-1 text-sm text-red-600">
                                                    {
                                                        errors[
                                                            "shipping_address.address"
                                                        ]
                                                    }
                                                </p>
                                            )}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-1">
                                                City
                                            </label>
                                            <input
                                                type="text"
                                                value={
                                                    data.shipping_address.city
                                                }
                                                onChange={(e) =>
                                                    setData(
                                                        "shipping_address",
                                                        {
                                                            ...data.shipping_address,
                                                            city: e.target
                                                                .value,
                                                        },
                                                    )
                                                }
                                                className="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors py-2.5"
                                            />
                                            {errors[
                                                "shipping_address.city"
                                            ] && (
                                                <p className="mt-1 text-sm text-red-600">
                                                    {
                                                        errors[
                                                            "shipping_address.city"
                                                        ]
                                                    }
                                                </p>
                                            )}
                                        </div>

                                        <div>
                                            <label className="block text-sm font-semibold text-gray-700 mb-1">
                                                ZIP Code
                                            </label>
                                            <input
                                                type="text"
                                                value={
                                                    data.shipping_address.zip
                                                }
                                                onChange={(e) =>
                                                    setData(
                                                        "shipping_address",
                                                        {
                                                            ...data.shipping_address,
                                                            zip: e.target.value,
                                                        },
                                                    )
                                                }
                                                className="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors py-2.5"
                                            />
                                            {errors["shipping_address.zip"] && (
                                                <p className="mt-1 text-sm text-red-600">
                                                    {
                                                        errors[
                                                            "shipping_address.zip"
                                                        ]
                                                    }
                                                </p>
                                            )}
                                        </div>
                                        <div className="sm:col-span-2">
                                            <label className="block text-sm font-semibold text-gray-700 mb-1">
                                                Country
                                            </label>
                                            <input
                                                type="text"
                                                value={
                                                    data.shipping_address
                                                        .country
                                                }
                                                onChange={(e) =>
                                                    setData(
                                                        "shipping_address",
                                                        {
                                                            ...data.shipping_address,
                                                            country:
                                                                e.target.value,
                                                        },
                                                    )
                                                }
                                                className="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#C41E3A] focus:ring-[#C41E3A]/20 transition-colors py-2.5"
                                            />
                                            {errors[
                                                "shipping_address.country"
                                            ] && (
                                                <p className="mt-1 text-sm text-red-600">
                                                    {
                                                        errors[
                                                            "shipping_address.country"
                                                        ]
                                                    }
                                                </p>
                                            )}
                                        </div>

                                        {selectedAddressId === "new" && (
                                            <div className="sm:col-span-2 mt-2">
                                                <label className="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                    <input
                                                        type="checkbox"
                                                        checked={
                                                            data.save_address
                                                        }
                                                        onChange={(e) =>
                                                            setData(
                                                                "save_address",
                                                                e.target
                                                                    .checked,
                                                            )
                                                        }
                                                        className="h-5 w-5 rounded border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A]"
                                                    />
                                                    <span className="text-sm font-medium text-gray-700">
                                                        Save this address for
                                                        next time
                                                    </span>
                                                </label>
                                            </div>
                                        )}
                                    </div>
                                </section>
                            </Transition>

                            {/* Shipping Method Selection (Moved here) */}
                            <section className="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
                                <h2 className="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                    <svg
                                        className="w-5 h-5 text-[#C41E3A]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>
                                    Shipping Method
                                </h2>
                                <div className="grid grid-cols-1 gap-4">
                                    {shippingMethods.map((method) => (
                                        <div
                                            key={method.id}
                                            onClick={() =>
                                                setData(
                                                    "shipping_method_id",
                                                    method.id,
                                                )
                                            }
                                            className={`relative flex items-center justify-between p-4 sm:p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 ${
                                                data.shipping_method_id ===
                                                method.id
                                                    ? "border-[#C41E3A] bg-red-50/30 ring-1 ring-[#C41E3A]/20"
                                                    : "border-gray-100 hover:border-gray-300 hover:bg-gray-50"
                                            }`}
                                        >
                                            <div className="flex items-start gap-4">
                                                <div className="mt-1">
                                                    <input
                                                        type="radio"
                                                        checked={
                                                            data.shipping_method_id ===
                                                            method.id
                                                        }
                                                        onChange={() =>
                                                            setData(
                                                                "shipping_method_id",
                                                                method.id,
                                                            )
                                                        }
                                                        className="h-5 w-5 border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A]"
                                                    />
                                                </div>
                                                <div>
                                                    <span className="block text-base font-semibold text-gray-900">
                                                        {method.name}
                                                    </span>
                                                    {method.estimated_delivery_time && (
                                                        <span className="block text-sm text-gray-500 mt-0.5">
                                                            {
                                                                method.estimated_delivery_time
                                                            }
                                                        </span>
                                                    )}
                                                    {method.description && (
                                                        <p className="text-xs text-gray-400 mt-1">
                                                            {method.description}
                                                        </p>
                                                    )}
                                                </div>
                                            </div>
                                            <span className="text-base font-bold text-gray-900">
                                                $
                                                {Number(method.cost).toFixed(2)}
                                            </span>
                                        </div>
                                    ))}
                                    {shippingMethods.length === 0 && (
                                        <p className="text-gray-500 italic p-4 text-center bg-gray-50 rounded-lg">
                                            No shipping methods available.
                                            Shipping will be calculated later.
                                        </p>
                                    )}
                                    {errors.shipping_method_id && (
                                        <p className="text-sm text-red-600 font-medium">
                                            {errors.shipping_method_id}
                                        </p>
                                    )}
                                </div>
                            </section>

                            {/* Payment Method */}
                            <section className="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100">
                                <h2 className="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                    <svg
                                        className="w-5 h-5 text-[#C41E3A]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                        />
                                    </svg>
                                    Payment Method
                                </h2>
                                <div className="space-y-4">
                                    <label
                                        className={`relative flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 ${data.payment_method === "cod" ? "border-[#C41E3A] bg-red-50/30" : "border-gray-100 hover:border-gray-200"}`}
                                    >
                                        <div className="mt-0.5">
                                            <input
                                                type="radio"
                                                name="payment-method"
                                                checked={
                                                    data.payment_method ===
                                                    "cod"
                                                }
                                                onChange={() =>
                                                    setData(
                                                        "payment_method",
                                                        "cod",
                                                    )
                                                }
                                                className="h-5 w-5 border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A]"
                                            />
                                        </div>
                                        <div>
                                            <span className="block text-sm font-bold text-gray-900">
                                                Cash on Delivery (COD)
                                            </span>
                                            <p className="text-sm text-gray-500 mt-0.5">
                                                Pay exactly what you see when
                                                you receive your order.
                                            </p>
                                        </div>
                                    </label>

                                    <label
                                        className={`relative flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 ${data.payment_method === "bank_transfer" ? "border-[#C41E3A] bg-red-50/30" : "border-gray-100 hover:border-gray-200"}`}
                                    >
                                        <div className="mt-0.5">
                                            <input
                                                type="radio"
                                                name="payment-method"
                                                checked={
                                                    data.payment_method ===
                                                    "bank_transfer"
                                                }
                                                onChange={() =>
                                                    setData(
                                                        "payment_method",
                                                        "bank_transfer",
                                                    )
                                                }
                                                className="h-5 w-5 border-gray-300 text-[#C41E3A] focus:ring-[#C41E3A]"
                                            />
                                        </div>
                                        <div>
                                            <span className="block text-sm font-bold text-gray-900">
                                                Bank Transfer
                                            </span>
                                            <p className="text-sm text-gray-500 mt-0.5">
                                                Direct bank transfer to our
                                                account.
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </section>
                        </div>

                        {/* Right Column: Order Summary */}
                        <div className="lg:w-[400px] flex-shrink-0">
                            <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sm:p-8 sticky top-6">
                                <h2 className="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">
                                    Order Summary
                                </h2>

                                <div className="max-h-[400px] overflow-y-auto pr-2 -mr-2 scrollbar-thin scrollbar-thumb-gray-200">
                                    <ul className="space-y-6">
                                        {cart.items.map((item) => (
                                            <li
                                                key={item.id}
                                                className="flex gap-4 group"
                                            >
                                                <div className="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-gray-100 bg-white p-1">
                                                    <StorageImage
                                                        path={item.image}
                                                        name={item.name}
                                                        className="h-full w-full object-contain object-center"
                                                    />
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <div className="flex justify-between items-start gap-2">
                                                        <div>
                                                            <h3 className="text-sm font-semibold text-gray-900 line-clamp-2 leading-tight group-hover:text-[#C41E3A] transition-colors">
                                                                {item.name}
                                                            </h3>
                                                            {(item.price_per_stock_unit !=
                                                                null &&
                                                                item.stock_unit_label) ||
                                                            item.unit ? (
                                                                <p className="mt-1 text-sm text-gray-500">
                                                                    {item.price_per_stock_unit !=
                                                                        null &&
                                                                    item.stock_unit_label
                                                                        ? `$${Number(item.price_per_stock_unit).toFixed(2)} per ${item.stock_unit_label.toLowerCase()}`
                                                                        : `$${Number(item.unit_price).toFixed(2)} / ${item.unit}`}
                                                                </p>
                                                            ) : null}
                                                        </div>
                                                        <p className="text-sm font-bold text-gray-900 tabular-nums">
                                                            $
                                                            {item.total.toFixed(
                                                                2,
                                                            )}
                                                        </p>
                                                    </div>

                                                    <div className="flex items-center justify-between mt-3">
                                                        <div className="flex items-center border border-gray-200 rounded-md bg-white h-7 shadow-sm">
                                                            <button
                                                                onClick={(
                                                                    e,
                                                                ) => {
                                                                    e.preventDefault();
                                                                    handleUpdateQuantity(
                                                                        item.id,
                                                                        item.quantity -
                                                                            1,
                                                                    );
                                                                }}
                                                                className="px-2 h-full text-gray-500 hover:text-[#C41E3A] disabled:opacity-30 transition hover:bg-gray-50 rounded-l-md"
                                                            >
                                                                -
                                                            </button>
                                                            <span className="px-2 text-xs font-bold text-gray-900 min-w-[1.5rem] text-center">
                                                                {item.quantity}
                                                            </span>
                                                            <button
                                                                onClick={(
                                                                    e,
                                                                ) => {
                                                                    e.preventDefault();
                                                                    handleUpdateQuantity(
                                                                        item.id,
                                                                        item.quantity +
                                                                            1,
                                                                    );
                                                                }}
                                                                className="px-2 h-full text-gray-500 hover:text-[#C41E3A] transition hover:bg-gray-50 rounded-r-md"
                                                            >
                                                                +
                                                            </button>
                                                        </div>

                                                        <button
                                                            type="button"
                                                            onClick={() =>
                                                                handleRemoveItem(
                                                                    item.id,
                                                                )
                                                            }
                                                            className="text-gray-400 hover:text-[#C41E3A] p-1 rounded-md hover:bg-red-50 transition-colors"
                                                            title="Remove item"
                                                        >
                                                            <svg
                                                                className="w-4 h-4"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </div>

                                <div className="mt-8 space-y-4 pt-6 border-t border-gray-100">
                                    {(() => {
                                        const subtotal =
                                            cart.summary?.subtotal ??
                                            (cart.items?.reduce(
                                                (s, i) =>
                                                    s + (i.quantity * (i.unit_price ?? 0)),
                                                0,
                                            ) ?? 0);
                                        const method = shippingMethods.find(
                                            (m) => m.id === data.shipping_method_id,
                                        );
                                        const shippingCost = method
                                            ? Number(method.cost)
                                            : 0;
                                        const total = subtotal + shippingCost;
                                        return (
                                            <>
                                                <div className="flex justify-between text-sm text-gray-600">
                                                    <span>Subtotal</span>
                                                    <span className="font-semibold text-gray-900">
                                                        ${Number(subtotal).toFixed(2)}
                                                    </span>
                                                </div>
                                                <div className="flex justify-between text-sm text-gray-600">
                                                    <span>Shipping</span>
                                                    <span className="font-semibold text-gray-900">
                                                        ${Number(shippingCost).toFixed(2)}
                                                    </span>
                                                </div>
                                                <div className="flex justify-between items-center pt-4 border-t border-gray-100">
                                                    <span className="text-base font-bold text-gray-900">
                                                        Total
                                                    </span>
                                                    <span className="text-2xl font-bold text-[#C41E3A]">
                                                        ${Number(total).toFixed(2)}
                                                    </span>
                                                </div>
                                            </>
                                        );
                                    })()}
                                </div>

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full mt-8 rounded-xl bg-[#C41E3A] px-6 py-4 text-base font-bold text-white shadow-lg shadow-red-200 hover:shadow-red-300 hover:bg-[#a01830] focus:outline-none focus:ring-4 focus:ring-red-100 disabled:opacity-70 disabled:cursor-not-allowed transition-all transform active:scale-[0.98]"
                                >
                                    {processing ? (
                                        <span className="flex items-center justify-center gap-2">
                                            <svg
                                                className="animate-spin h-5 w-5 text-white"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle
                                                    className="opacity-25"
                                                    cx="12"
                                                    cy="12"
                                                    r="10"
                                                    stroke="currentColor"
                                                    strokeWidth="4"
                                                ></circle>
                                                <path
                                                    className="opacity-75"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                ></path>
                                            </svg>
                                            Processing...
                                        </span>
                                    ) : (
                                        "Place Order"
                                    )}
                                </button>

                                <div className="mt-6 flex items-center justify-center gap-2 text-xs text-gray-400">
                                    <svg
                                        className="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                        />
                                    </svg>
                                    Secure Checkout
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </CustomerLayout>
    );
}
