import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import StorageImage from "@/Components/StorageImage";
import { Head, Link, useForm } from "@inertiajs/react";
import { useState, useEffect, useCallback, useRef } from "react";

const defaultShipping = {
    name: "",
    email: "",
    phone: "",
    address: "",
    city: "",
    postal_code: "",
    country: "",
};

function mapAddressToShipping(addr) {
    return {
        name: addr?.name ?? "",
        email: addr?.email ?? "",
        phone: addr?.phone ?? "",
        address: [addr?.address_line_1, addr?.address_line_2]
            .filter(Boolean)
            .join(", "),
        city: addr?.city ?? "",
        postal_code: addr?.postal_code ?? "",
        country: addr?.country ?? "",
    };
}

export default function Create({
    categories = [],
    brands = [],
    addresses = [],
}) {
    const [categoryId, setCategoryId] = useState("");
    const [brandId, setBrandId] = useState("");
    const [searchQuery, setSearchQuery] = useState("");
    const [searchInput, setSearchInput] = useState("");
    const [products, setProducts] = useState([]);
    const [pagination, setPagination] = useState({
        current_page: 1,
        last_page: 1,
    });
    const [loading, setLoading] = useState(false);
    const [cart, setCart] = useState([]);
    const [shipping, setShipping] = useState(defaultShipping);
    const productsScrollRef = useRef(null);
    const loadMoreSentinelRef = useRef(null);

    const fetchProducts = useCallback(
        (page = 1, append = false) => {
            setLoading(true);
            const params = new URLSearchParams({ page });
            if (searchQuery) params.set("q", searchQuery);
            if (categoryId) params.set("category_id", categoryId);
            if (brandId) params.set("brand_id", brandId);
            fetch(route("orders.search-products") + "?" + params.toString(), {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
                .then((r) => r.json())
                .then((res) => {
                    setProducts((prev) =>
                        append
                            ? [...prev, ...(res.data || [])]
                            : res.data || [],
                    );
                    setPagination({
                        current_page: res.current_page,
                        last_page: res.last_page ?? 1,
                    });
                })
                .catch(() => setProducts([]))
                .finally(() => setLoading(false));
        },
        [searchQuery, categoryId, brandId],
    );

    useEffect(() => {
        fetchProducts(1, false);
    }, [fetchProducts]);

    useEffect(() => {
        const t = setTimeout(() => setSearchQuery(searchInput), 350);
        return () => clearTimeout(t);
    }, [searchInput]);

    // Infinite scroll: load next page when sentinel is visible inside the products scroll area
    useEffect(() => {
        const root = productsScrollRef.current;
        const sentinel = loadMoreSentinelRef.current;
        if (
            !root ||
            !sentinel ||
            pagination.current_page >= pagination.last_page
        )
            return;
        const observer = new IntersectionObserver(
            (entries) => {
                if (
                    entries[0]?.isIntersecting &&
                    !loading &&
                    pagination.current_page < pagination.last_page
                ) {
                    fetchProducts(pagination.current_page + 1, true);
                }
            },
            { root, rootMargin: "80px", threshold: 0 },
        );
        observer.observe(sentinel);
        return () => observer.disconnect();
    }, [
        loading,
        pagination.current_page,
        pagination.last_page,
        fetchProducts,
        products.length,
    ]);

    const addToCart = (product, quantity = 1) => {
        const qty = Math.max(1, parseInt(quantity, 10) || 1);
        const unit_price = parseFloat(product.price) || 0;
        const qty_per_box = parseFloat(product.pcs_in_ctn) || 1;
        const box_price = parseFloat(product.box_price) || (unit_price * qty_per_box);

        if (unit_price <= 0) return;
        setCart((prev) => {
            const existing = prev.find((i) => i.product_id === product.id);
            if (existing) {
                return prev.map((i) => {
                    if (i.product_id === product.id) {
                        if (i.calc_type === 'box') {
                            const newBoxes = i.boxes + qty;
                            return { ...i, boxes: newBoxes, quantity: newBoxes * i.qty_per_box };
                        } else {
                            return { ...i, quantity: i.quantity + qty };
                        }
                    }
                    return i;
                });
            }
            return [
                ...prev,
                {
                    product_id: product.id,
                    name: product.name,
                    code: product.product_code,
                    unit_price,
                    box_price,
                    qty_per_box,
                    calc_type: 'box',
                    boxes: qty,
                    quantity: qty * qty_per_box,
                },
            ];
        });
    };

    const removeFromCart = (index) => {
        setCart((prev) => prev.filter((_, i) => i !== index));
    };

    const updateCartQuantity = (index, val) => {
        const v = Math.max(0.01, parseFloat(val) || 0.01);
        setCart((prev) =>
            prev.map((item, i) => {
                if (i !== index) return item;
                if (item.calc_type === 'box') {
                    return { ...item, boxes: v, quantity: v * item.qty_per_box };
                } else {
                    return { ...item, quantity: v };
                }
            })
        );
    };

    const updateCalcType = (index, type) => {
        setCart((prev) =>
            prev.map((item, i) => {
                if (i !== index) return item;
                if (type === item.calc_type) return item;
                
                let newBoxes = item.boxes;
                let newQty = item.quantity;
                
                if (type === 'box') {
                    // switching to box
                    newBoxes = Math.max(0.01, newQty / item.qty_per_box);
                } else {
                    // switching to quantity
                    newQty = Math.max(0.01, newBoxes * item.qty_per_box);
                }
                
                return { ...item, calc_type: type, boxes: newBoxes, quantity: newQty };
            })
        );
    };

    const fillShipping = (address) => {
        setShipping(mapAddressToShipping(address));
    };

    const subtotal = cart.reduce((sum, i) => {
        const price = i.calc_type === 'box' ? parseFloat(i.box_price) : parseFloat(i.unit_price);
        const qty = i.calc_type === 'box' ? parseFloat(i.boxes) : parseFloat(i.quantity);
        return sum + price * qty;
    }, 0);

    const { data, setData, post, processing, errors } = useForm({
        items: [],
        shipping_address: defaultShipping,
        notes: "",
    });

    useEffect(() => {
        setData({
            items: cart.map((i) => ({
                product_id: i.product_id,
                quantity: Math.max(1, Math.round(i.calc_type === 'box' ? i.boxes * i.qty_per_box : i.quantity)),
                unit_price: i.calc_type === 'box' ? Number(i.box_price / (i.qty_per_box || 1)).toFixed(4) : Number(i.unit_price).toFixed(4),
            })),
            shipping_address: shipping,
            notes: data.notes,
        });
    }, [cart, shipping]);

    const submit = (e) => {
        e.preventDefault();
        post(route("orders.store"), {
            transform: () => ({
                items: cart.map((i) => ({
                    product_id: i.product_id,
                    quantity: Math.max(1, Math.round(i.calc_type === 'box' ? i.boxes * i.qty_per_box : i.quantity)),
                    unit_price: i.calc_type === 'box' ? Number(i.box_price / (i.qty_per_box || 1)).toFixed(4) : Number(i.unit_price).toFixed(4),
                })),
                shipping_address: shipping,
                notes: data.notes,
            }),
        });
    };

    const canSubmit =
        cart.length > 0 &&
        shipping.name &&
        shipping.email &&
        shipping.address &&
        shipping.city &&
        shipping.postal_code &&
        shipping.country;

    return (
        <AuthenticatedLayout title="Create order">
            <Head title="Create order" />
            <div className="space-y-6">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                            <Link
                                href={route("orders.index")}
                                className="hover:text-[#C41E3A] transition-colors"
                            >
                                Orders
                            </Link>
                            <span>/</span>
                            <span>Create order</span>
                        </div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white">
                            Create order
                        </h2>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Search products, add quantities, and submit your
                            bulk order.
                        </p>
                    </div>
                    <Link
                        href={route("orders.index")}
                        className="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 dark:bg-[#2A2A2A] dark:border-gray-700 dark:text-gray-300 dark:hover:bg-[#333]"
                    >
                        <svg
                            className="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Back to orders
                    </Link>
                </div>

                <form
                    onSubmit={submit}
                    className="grid grid-cols-1 lg:grid-cols-12 gap-6"
                >
                    {/* Left: Products only */}
                    <div className="lg:col-span-7">
                        <div className="bg-white dark:bg-[#1E1E1E] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                            <div className="px-4 py-3 sm:px-5 border-b border-gray-100 dark:border-gray-800">
                                <h3 className="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span className="flex h-7 w-7 items-center justify-center rounded-lg bg-[#C41E3A]/10 text-[#C41E3A]">
                                        <svg
                                            className="h-3.5 w-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                            />
                                        </svg>
                                    </span>
                                    Products
                                </h3>
                                <div className="mt-3 flex flex-col sm:flex-row gap-2">
                                    <select
                                        value={categoryId}
                                        onChange={(e) =>
                                            setCategoryId(e.target.value)
                                        }
                                        className="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2.5 py-1.5 text-sm focus:ring-[#C41E3A] focus:border-[#C41E3A] sm:w-36"
                                    >
                                        <option value="">All categories</option>
                                        {categories.map((c) => (
                                            <option key={c.id} value={c.id}>
                                                {c.name}
                                            </option>
                                        ))}
                                    </select>
                                    <select
                                        value={brandId}
                                        onChange={(e) =>
                                            setBrandId(e.target.value)
                                        }
                                        className="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2.5 py-1.5 text-sm focus:ring-[#C41E3A] focus:border-[#C41E3A] sm:w-36"
                                    >
                                        <option value="">All brands</option>
                                        {brands.map((b) => (
                                            <option key={b.id} value={b.id}>
                                                {b.name}
                                            </option>
                                        ))}
                                    </select>
                                    <div className="relative flex-1">
                                        <span className="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg
                                                className="h-3.5 w-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth={2}
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            value={searchInput}
                                            onChange={(e) =>
                                                setSearchInput(e.target.value)
                                            }
                                            placeholder="Search by name or SKU…"
                                            className="w-full pl-8 pr-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white text-sm focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                ref={productsScrollRef}
                                className="p-3 overflow-y-auto max-h-[min(70vh,520px)]"
                            >
                                <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-2">
                                    {loading && products.length === 0 ? (
                                        <div className="col-span-full flex flex-col items-center justify-center py-10 text-gray-500">
                                            <svg
                                                className="animate-spin h-6 w-6 text-[#C41E3A] mb-2"
                                                xmlns="http://www.w3.org/2000/svg"
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
                                                />
                                                <path
                                                    className="opacity-75"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                                />
                                            </svg>
                                            <span className="text-xs">
                                                Loading products…
                                            </span>
                                        </div>
                                    ) : products.length === 0 ? (
                                        <div className="col-span-full py-10 text-center text-gray-500 dark:text-gray-400 text-xs">
                                            No products found. Try different
                                            filters.
                                        </div>
                                    ) : (
                                        products.map((product) => {
                                            const price =
                                                parseFloat(product.price) || 0;
                                            const inStock =
                                                (product.quantity ?? 0) > 0;
                                            return (
                                                <div
                                                    key={product.id}
                                                    className="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-[#2A2A2A] overflow-hidden flex flex-col"
                                                >
                                                    <div className="aspect-square bg-gray-100 dark:bg-gray-800 flex items-center justify-center relative">
                                                        {product.image ? (
                                                            <StorageImage
                                                                path={
                                                                    product.image
                                                                }
                                                                name={
                                                                    product.name
                                                                }
                                                                className="h-full w-full object-cover"
                                                            />
                                                        ) : (
                                                            <svg
                                                                className="h-6 w-6 text-gray-300 dark:text-gray-600"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth={
                                                                        1.5
                                                                    }
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                                />
                                                            </svg>
                                                        )}
                                                        <span
                                                            className={`absolute top-1 right-1 text-[9px] font-semibold px-1 py-0.5 rounded ${inStock ? "bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400" : "bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400"}`}
                                                        >
                                                            {inStock
                                                                ? product.quantity
                                                                : "0"}
                                                        </span>
                                                    </div>
                                                    <div className="p-1.5 flex flex-col flex-1 min-w-0">
                                                        <span className="text-[9px] font-mono text-gray-500 dark:text-gray-400 truncate block">
                                                            {
                                                                product.product_code
                                                            }
                                                        </span>
                                                        <span className="text-xs font-semibold text-gray-900 dark:text-white line-clamp-2 leading-tight">
                                                            {product.name}
                                                        </span>
                                                        <div className="mt-1 flex items-center justify-between gap-1">
                                                            <span className="text-xs font-bold text-[#C41E3A]">
                                                                $
                                                                {price.toFixed(
                                                                    2,
                                                                )}
                                                            </span>
                                                            <button
                                                                type="button"
                                                                onClick={() =>
                                                                    addToCart(
                                                                        product,
                                                                    )
                                                                }
                                                                disabled={
                                                                    !inStock ||
                                                                    price <= 0
                                                                }
                                                                className="shrink-0 inline-flex items-center justify-center w-6 h-6 rounded-md bg-[#C41E3A] text-white hover:bg-[#a01830] disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                                title="Add to order"
                                                            >
                                                                <svg
                                                                    className="w-3 h-3"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        strokeLinecap="round"
                                                                        strokeLinejoin="round"
                                                                        strokeWidth={
                                                                            2
                                                                        }
                                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                                                    />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    )}
                                </div>
                                {/* Sentinel for infinite scroll */}
                                {pagination.current_page <
                                    pagination.last_page &&
                                    products.length > 0 && (
                                        <div
                                            ref={loadMoreSentinelRef}
                                            className="h-4 flex items-center justify-center py-2"
                                        >
                                            {loading && (
                                                <span className="text-xs text-gray-400">
                                                    Loading more…
                                                </span>
                                            )}
                                        </div>
                                    )}
                            </div>
                        </div>
                        {/* Shipping */}
                        <div className="bg-white dark:bg-[#1E1E1E] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                            <div className="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                                <h3 className="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span className="flex h-7 w-7 items-center justify-center rounded-lg bg-[#C41E3A]/10 text-[#C41E3A]">
                                        <svg
                                            className="h-3.5 w-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            />
                                        </svg>
                                    </span>
                                    Shipping address
                                </h3>
                                {addresses.length > 0 && (
                                    <div className="mt-2">
                                        <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                            Use saved address
                                        </label>
                                        <select
                                            onChange={(e) => {
                                                const id = e.target.value;
                                                const addr = addresses.find(
                                                    (a) =>
                                                        a.id ===
                                                        parseInt(id, 10),
                                                );
                                                if (addr) fillShipping(addr);
                                            }}
                                            className="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2.5 py-1.5 text-xs"
                                        >
                                            <option value="">
                                                Select an address…
                                            </option>
                                            {addresses.map((a) => (
                                                <option key={a.id} value={a.id}>
                                                    {a.type || "Address"} –{" "}
                                                    {[a.address_line_1, a.city]
                                                        .filter(Boolean)
                                                        .join(", ")}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                )}
                            </div>
                            <div className="p-3 grid grid-cols-1 gap-2">
                                <div>
                                    <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                        Full name *
                                    </label>
                                    <input
                                        type="text"
                                        value={shipping.name}
                                        onChange={(e) =>
                                            setShipping((s) => ({
                                                ...s,
                                                name: e.target.value,
                                            }))
                                        }
                                        className="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2 py-1.5 text-xs focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                        required
                                    />
                                    {errors["shipping_address.name"] && (
                                        <p className="mt-0.5 text-[10px] text-red-500">
                                            {errors["shipping_address.name"]}
                                        </p>
                                    )}
                                </div>
                                <div>
                                    <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                        Email *
                                    </label>
                                    <input
                                        type="email"
                                        value={shipping.email}
                                        onChange={(e) =>
                                            setShipping((s) => ({
                                                ...s,
                                                email: e.target.value,
                                            }))
                                        }
                                        className="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2 py-1.5 text-xs focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                        required
                                    />
                                    {errors["shipping_address.email"] && (
                                        <p className="mt-0.5 text-[10px] text-red-500">
                                            {errors["shipping_address.email"]}
                                        </p>
                                    )}
                                </div>
                                <div>
                                    <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                        Phone
                                    </label>
                                    <input
                                        type="text"
                                        value={shipping.phone}
                                        onChange={(e) =>
                                            setShipping((s) => ({
                                                ...s,
                                                phone: e.target.value,
                                            }))
                                        }
                                        className="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2 py-1.5 text-xs focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                        Address *
                                    </label>
                                    <input
                                        type="text"
                                        value={shipping.address}
                                        onChange={(e) =>
                                            setShipping((s) => ({
                                                ...s,
                                                address: e.target.value,
                                            }))
                                        }
                                        className="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2 py-1.5 text-xs focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                        required
                                    />
                                    {errors["shipping_address.address"] && (
                                        <p className="mt-0.5 text-[10px] text-red-500">
                                            {errors["shipping_address.address"]}
                                        </p>
                                    )}
                                </div>
                                <div className="grid grid-cols-2 gap-2">
                                    <div>
                                        <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                            City *
                                        </label>
                                        <input
                                            type="text"
                                            value={shipping.city}
                                            onChange={(e) =>
                                                setShipping((s) => ({
                                                    ...s,
                                                    city: e.target.value,
                                                }))
                                            }
                                            className="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2 py-1.5 text-xs focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                            required
                                        />
                                        {errors["shipping_address.city"] && (
                                            <p className="mt-0.5 text-[10px] text-red-500">
                                                {
                                                    errors[
                                                        "shipping_address.city"
                                                    ]
                                                }
                                            </p>
                                        )}
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                            Postal code *
                                        </label>
                                        <input
                                            type="text"
                                            value={shipping.postal_code}
                                            onChange={(e) =>
                                                setShipping((s) => ({
                                                    ...s,
                                                    postal_code: e.target.value,
                                                }))
                                            }
                                            className="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2 py-1.5 text-xs focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                            required
                                        />
                                        {errors[
                                            "shipping_address.postal_code"
                                        ] && (
                                            <p className="mt-0.5 text-[10px] text-red-500">
                                                {
                                                    errors[
                                                        "shipping_address.postal_code"
                                                    ]
                                                }
                                            </p>
                                        )}
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                        Country *
                                    </label>
                                    <input
                                        type="text"
                                        value={shipping.country}
                                        onChange={(e) =>
                                            setShipping((s) => ({
                                                ...s,
                                                country: e.target.value,
                                            }))
                                        }
                                        className="w-full rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-2 py-1.5 text-xs focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                        required
                                    />
                                    {errors["shipping_address.country"] && (
                                        <p className="mt-0.5 text-[10px] text-red-500">
                                            {errors["shipping_address.country"]}
                                        </p>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Right: Shipping + Cart + Summary */}
                    <div className="lg:col-span-5">
                        <div className="lg:sticky lg:top-6 space-y-6">
                            <div className="bg-white dark:bg-[#1E1E1E] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                                <div className="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/30">
                                    <h3 className="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                        <svg
                                            className="h-4 w-4 text-gray-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                        Order summary
                                    </h3>
                                    <span className="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {cart.length} item(s)
                                    </span>
                                </div>
                                <div className="max-h-[280px] overflow-y-auto p-4 space-y-3">
                                    {cart.length === 0 ? (
                                        <p className="text-sm text-gray-500 dark:text-gray-400 py-6 text-center">
                                            Add products from the list on the
                                            left.
                                        </p>
                                    ) : (
                                        cart.map((item, index) => (
                                            <div
                                                key={`${item.product_id}-${index}`}
                                                className="flex flex-col gap-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#2A2A2A] p-3"
                                            >
                                                <div className="flex justify-between items-start gap-2">
                                                    <div className="min-w-0">
                                                        <p className="text-sm font-semibold text-gray-900 dark:text-white line-clamp-2">
                                                            {item.name}
                                                        </p>
                                                        <p className="text-[10px] font-mono text-gray-500">
                                                            {item.code}
                                                        </p>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            removeFromCart(
                                                                index,
                                                            )
                                                        }
                                                        className="shrink-0 p-1 rounded text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20"
                                                        title="Remove"
                                                    >
                                                        <svg
                                                            className="w-4 h-4"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                strokeLinecap="round"
                                                                strokeLinejoin="round"
                                                                strokeWidth={2}
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div className="flex items-center gap-4 bg-gray-100 dark:bg-gray-800 p-2 rounded-lg text-xs font-semibold mt-1 mb-1">
                                                    <label className="flex items-center gap-1.5 cursor-pointer">
                                                        <input 
                                                            type="radio" 
                                                            name={`calc_type_${index}`}
                                                            checked={item.calc_type === 'box'}
                                                            onChange={() => updateCalcType(index, 'box')}
                                                            className="text-[#C41E3A] focus:ring-[#C41E3A]" 
                                                        />
                                                        Box
                                                    </label>
                                                    <label className="flex items-center gap-1.5 cursor-pointer">
                                                        <input 
                                                            type="radio" 
                                                            name={`calc_type_${index}`}
                                                            checked={item.calc_type === 'quantity'}
                                                            onChange={() => updateCalcType(index, 'quantity')}
                                                            className="text-[#C41E3A] focus:ring-[#C41E3A]" 
                                                        />
                                                        Quantity
                                                    </label>
                                                    {item.calc_type === 'box' && (
                                                        <span className="ml-auto text-[10px] text-gray-400 font-mono bg-white dark:bg-gray-900 px-1.5 py-0.5 rounded shadow-sm border border-gray-200 dark:border-gray-700">
                                                            PC's In: {item.qty_per_box}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="flex items-center justify-between gap-2 flex-wrap">
                                                    <div className="flex items-center rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden bg-white dark:bg-[#1E1E1E]">
                                                        <button
                                                            type="button"
                                                            onClick={() =>
                                                                updateCartQuantity(
                                                                    index,
                                                                    (item.calc_type === 'box' ? item.boxes : item.quantity) -
                                                                        1,
                                                                )
                                                            }
                                                            className="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                        >
                                                            <svg
                                                                className="w-3.5 h-3.5"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth={
                                                                        2
                                                                    }
                                                                    d="M20 12H4"
                                                                />
                                                            </svg>
                                                        </button>
                                                        <input
                                                            type="text"
                                                            min={0.01}
                                                            value={
                                                                item.calc_type === 'box' ? item.boxes : item.quantity
                                                            }
                                                            onChange={(e) =>
                                                                updateCartQuantity(
                                                                    index,
                                                                    e.target
                                                                        .value,
                                                                )
                                                            }
                                                            className="w-12 h-8 text-center border-0 bg-transparent text-sm font-semibold text-gray-900 dark:text-white focus:ring-0 px-0"
                                                        />
                                                        <button
                                                            type="button"
                                                            onClick={() =>
                                                                updateCartQuantity(
                                                                    index,
                                                                    (item.calc_type === 'box' ? item.boxes : item.quantity) +
                                                                        1,
                                                                )
                                                            }
                                                            className="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                        >
                                                            <svg
                                                                className="w-3.5 h-3.5"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    strokeLinecap="round"
                                                                    strokeLinejoin="round"
                                                                    strokeWidth={
                                                                        2
                                                                    }
                                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                                                />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <span className="text-sm font-bold text-gray-900 dark:text-white">
                                                        $
                                                        {(
                                                            (item.calc_type === 'box' ? item.box_price : item.unit_price) *
                                                            (item.calc_type === 'box' ? item.boxes : item.quantity)
                                                        ).toFixed(2)}
                                                    </span>
                                                </div>
                                                <p className="text-xs text-gray-500">
                                                    ${(item.calc_type === 'box' ? item.box_price : item.unit_price).toFixed(2)} ×{" "}
                                                    {item.calc_type === 'box' ? item.boxes : item.quantity} {item.calc_type === 'box' ? 'Bxs' : 'Units'}
                                                </p>
                                            </div>
                                        ))
                                    )}
                                </div>
                                <div className="border-t border-gray-100 dark:border-gray-800 p-4 space-y-3">
                                    <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <span>Subtotal</span>
                                        <span className="font-semibold text-gray-900 dark:text-white">
                                            ${subtotal.toFixed(2)}
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-base font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-100 dark:border-gray-800">
                                        <span>Total</span>
                                        <span className="text-[#C41E3A]">
                                            ${subtotal.toFixed(2)}
                                        </span>
                                    </div>
                                    <div>
                                        <label className="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            Notes (optional)
                                        </label>
                                        <textarea
                                            value={data.notes}
                                            onChange={(e) =>
                                                setData("notes", e.target.value)
                                            }
                                            rows={2}
                                            placeholder="Special instructions…"
                                            className="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2A2A2A] text-gray-900 dark:text-white px-3 py-2 text-sm resize-none focus:ring-[#C41E3A] focus:border-[#C41E3A]"
                                        />
                                    </div>
                                    {!canSubmit && cart.length > 0 && (
                                        <p className="text-xs text-amber-600 dark:text-amber-400">
                                            Fill in all required shipping fields
                                            to place the order.
                                        </p>
                                    )}
                                    {!canSubmit && cart.length === 0 && (
                                        <p className="text-xs text-gray-500">
                                            Add at least one product to
                                            continue.
                                        </p>
                                    )}
                                    <button
                                        type="submit"
                                        disabled={!canSubmit || processing}
                                        className="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-[#C41E3A] hover:bg-[#a01830] focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        {processing
                                            ? "Placing order…"
                                            : "Place order"}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
