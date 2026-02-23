import { useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import ApplicationLogo from "@/Components/ApplicationLogo";

export default function Footer() {
    const { pages = [] } = usePage().props;
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
                        <div className="flex gap-4 pt-2">
                            {/* Social Icons - Using SVG directly for better control */}
                            {["facebook", "instagram", "linkedin"].map(
                                (social) => (
                                    <a
                                        key={social}
                                        href="#"
                                        className="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#C41E3A] hover:text-white transition-all duration-300 transform hover:-translate-y-1"
                                    >
                                        <span className="sr-only">
                                            {social}
                                        </span>
                                        {/* Placeholder icons - replace with actual SVGs if available */}
                                        <svg
                                            className="w-5 h-5 fill-current"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle cx="12" cy="12" r="10" />
                                        </svg>
                                    </a>
                                ),
                            )}
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
