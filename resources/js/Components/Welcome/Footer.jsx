import { useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import ApplicationLogo from "@/Components/ApplicationLogo";

export default function Footer() {
    const { pages = [], settings = {} } = usePage().props;
    const [email, setEmail] = useState("");
    const [message, setMessage] = useState(null);
    const [loading, setLoading] = useState(false);

    const handleNewsletterSubmit = (e) => {
        e.preventDefault();
        if (!email.trim()) return;
        setMessage(null);
        setLoading(true);
        const token = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        fetch(route("newsletter.subscribe"), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": token || "",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({ email: email.trim() }),
        })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const msg =
                        data.message ||
                        data.errors?.email?.[0] ||
                        "Something went wrong. Please try again.";
                    throw new Error(msg);
                }
                return data;
            })
            .then((data) => {
                setMessage({
                    type: "success",
                    text: data.message || "Thanks for subscribing!",
                });
                setEmail("");
            })
            .catch((err) => {
                setMessage({
                    type: "error",
                    text:
                        err.message ||
                        "Something went wrong. Please try again.",
                });
            })
            .finally(() => setLoading(false));
    };
    return (
        <footer className="bg-[#111111] text-gray-300 border-t border-gray-800">
            {/* Main Footer Content */}
            <div className="max-w-[1920px] mx-auto px-4 lg:px-8 py-16 lg:py-24">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
                    {/* Brand Column */}
                    <div className="lg:col-span-4 space-y-6">
                        <div className="flex items-center gap-2">
                            <ApplicationLogo className="h-10 w-auto fill-current text-white" />
                            <span className="text-2xl font-black text-white tracking-tight">
                                ZAM ZAM
                            </span>
                        </div>
                        <p className="text-gray-400 leading-relaxed max-w-sm">
                            Your trusted partner for authentic ethnic
                            distribution. We bring the true taste of South Asia
                            directly to Canadian markets with uncompromised
                            quality.
                        </p>
                        <div className="flex flex-wrap gap-3 pt-2">
                            {[
                                {
                                    key: "social_facebook",
                                    label: "Facebook",
                                    svg: (
                                        <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    ),
                                },
                                {
                                    key: "social_instagram",
                                    label: "Instagram",
                                    svg: (
                                        <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    ),
                                },
                                {
                                    key: "social_linkedin",
                                    label: "LinkedIn",
                                    svg: (
                                        <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    ),
                                },
                                {
                                    key: "social_twitter",
                                    label: "X / Twitter",
                                    svg: (
                                        <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    ),
                                },
                                {
                                    key: "social_youtube",
                                    label: "YouTube",
                                    svg: (
                                        <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    ),
                                },
                            ]
                                .filter((s) => settings?.[s.key])
                                .map((s) => (
                                    <a
                                        key={s.key}
                                        href={settings[s.key]}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        aria-label={s.label}
                                        className="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-[#C41E3A] hover:text-white transition-all duration-300 transform hover:-translate-y-1"
                                    >
                                        {s.svg}
                                    </a>
                                ))}
                        </div>
                    </div>

                    {/* Quick Links */}
                    <div className="lg:col-span-2 lg:col-start-6">
                        <h3 className="text-white font-bold mb-6 text-sm uppercase tracking-widest">
                            Company
                        </h3>
                        <ul className="space-y-4">
                            {[
                                { label: "About Us", href: route("about") },
                                { label: "Shop", href: route("shop.index") },
                                { label: "Brands", href: route("shop.brands") },
                                { label: "Contact", href: route("contact") },
                            ].map((item) => (
                                <li key={item.label}>
                                    <Link
                                        href={item.href}
                                        className="hover:text-[#C41E3A] transition-colors duration-200 block text-sm"
                                    >
                                        {item.label}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="lg:col-span-2">
                        <h3 className="text-white font-bold mb-6 text-sm uppercase tracking-widest">
                            Support
                        </h3>
                        <ul className="space-y-4">
                            {pages.map((page) => (
                                <li key={page.slug}>
                                    <Link
                                        href={route("pages.show", page.slug)}
                                        className="hover:text-[#C41E3A] transition-colors duration-200 block text-sm"
                                    >
                                        {page.title}
                                    </Link>
                                </li>
                            ))}
                            {pages.length === 0 && (
                                <li className="text-gray-600 text-sm">–</li>
                            )}
                        </ul>
                    </div>

                    {/* Newsletter */}
                    <div className="lg:col-span-4 lg:col-start-10 md:col-span-2">
                        <h3 className="text-white font-bold mb-6 text-sm uppercase tracking-widest">
                            Stay Updated
                        </h3>
                        <p className="text-gray-400 text-sm mb-6">
                            Subscribe to our newsletter for the latest products
                            and exclusive offers.
                        </p>
                        <form
                            className="relative"
                            onSubmit={handleNewsletterSubmit}
                        >
                            <input
                                type="email"
                                placeholder="Enter your email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                disabled={loading}
                                required
                                className="w-full bg-gray-800 border-none rounded-lg py-4 pl-4 pr-32 text-white placeholder-gray-500 focus:ring-2 focus:ring-[#C41E3A] transition-all disabled:opacity-70"
                            />
                            <button
                                type="submit"
                                disabled={loading}
                                className="absolute right-1 top-1 bottom-1 bg-[#C41E3A] text-white px-6 rounded-md font-bold text-sm hover:bg-red-700 transition-colors uppercase tracking-wider disabled:opacity-70"
                            >
                                {loading ? "…" : "Join"}
                            </button>
                        </form>
                        {message && (
                            <p
                                className={`mt-2 text-sm ${message.type === "success" ? "text-green-400" : "text-red-400"}`}
                            >
                                {message.text}
                            </p>
                        )}
                    </div>
                </div>
            </div>

            {/* Bottom Bar */}
            <div className="border-t border-gray-800 bg-[#0a0a0a]">
                <div className="max-w-[1920px] mx-auto px-4 lg:px-8 py-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div className="flex flex-col items-center md:items-start gap-1 text-center md:text-left">
                        <p className="text-sm text-gray-500">
                            &copy; {new Date().getFullYear()} Zam Zam Import
                            Export Inc. All rights reserved.
                        </p>
                    </div>
                    <div className="flex gap-8 text-sm text-gray-500">
                        <p className="text-sm text-gray-500">
                            Developed by{" "}
                            <a
                                href="https://nixsoftware.net/"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="hover:text-white transition-colors text-gray-400"
                            >
                                Nix Software
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    );
}
