import CustomerLayout from "@/Layouts/CustomerLayout";
import { Head, Link } from "@inertiajs/react";
import Breadcrumb from "@/Components/Breadcrumb";

export default function Page({ page }) {
    return (
        <CustomerLayout>
            <Head title={page.title} />

            <Breadcrumb
                title={page.title}
                links={[{ label: page.title, active: true }]}
            />

            {/* Hero strip */}
            <div className="bg-[#111111] py-16 relative overflow-hidden">
                {/* Dot-grid background */}
                <div
                    className="absolute inset-0 opacity-[0.04]"
                    style={{
                        backgroundImage:
                            "radial-gradient(#fff 1px, transparent 1px)",
                        backgroundSize: "28px 28px",
                    }}
                />
                {/* Red accent line */}
                <div className="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#C41E3A] to-transparent opacity-60" />
                <div className="max-w-4xl mx-auto px-4 lg:px-8 relative z-10 text-center">
                    <span className="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest text-[#C41E3A] bg-[#C41E3A]/10 border border-[#C41E3A]/20 mb-5">
                        Information
                    </span>
                    <h1 className="text-4xl md:text-5xl font-black text-white leading-tight mb-4">
                        {page.title}
                    </h1>
                    {page.updated_at && (
                        <p className="text-gray-500 text-sm">
                            Last updated: {page.updated_at}
                        </p>
                    )}
                </div>
            </div>

            {/* Content */}
            <div className="bg-white py-16 lg:py-24">
                <div className="max-w-4xl mx-auto px-4 lg:px-8">
                    {page.content ? (
                        <div
                            className="page-content"
                            dangerouslySetInnerHTML={{ __html: page.content }}
                        />
                    ) : (
                        <p className="text-gray-400 text-center py-16">
                            No content available for this page yet.
                        </p>
                    )}
                </div>
            </div>

            {/* CTA strip */}
            <div className="bg-gray-50 border-t border-gray-100 py-16">
                <div className="max-w-4xl mx-auto px-4 lg:px-8 text-center">
                    <p className="text-gray-500 mb-6 text-sm">
                        Still have questions? We&apos;re happy to help.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-3 justify-center">
                        <Link
                            href={route("contact")}
                            className="inline-flex items-center justify-center gap-2 bg-[#C41E3A] text-white px-7 py-3 rounded-full font-bold text-sm uppercase tracking-widest hover:bg-[#a91930] transition-all shadow-lg shadow-red-100 hover:-translate-y-0.5 transform"
                        >
                            Contact Us
                        </Link>
                        <Link
                            href={route("shop.index")}
                            className="inline-flex items-center justify-center gap-2 bg-white text-gray-800 border-2 border-gray-200 px-7 py-3 rounded-full font-bold text-sm uppercase tracking-widest hover:border-[#C41E3A] hover:text-[#C41E3A] transition-all hover:-translate-y-0.5 transform"
                        >
                            Browse Shop
                        </Link>
                    </div>
                </div>
            </div>

            {/* Quill-compatible content styling */}
            <style>{`
                .page-content { color: #374151; font-size: 1rem; line-height: 1.8; }
                .page-content h1 { font-size: 2rem; font-weight: 800; color: #111827; margin: 2rem 0 1rem; }
                .page-content h2 { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 2rem 0 0.875rem; padding-bottom: 0.5rem; border-bottom: 2px solid #f3f4f6; }
                .page-content h3 { font-size: 1.15rem; font-weight: 700; color: #374151; margin: 1.5rem 0 0.625rem; }
                .page-content p { margin-bottom: 1rem; }
                .page-content ul, .page-content ol { margin: 0.75rem 0 1rem 1.5rem; }
                .page-content ul { list-style-type: disc; }
                .page-content ol { list-style-type: decimal; }
                .page-content li { margin-bottom: 0.375rem; }
                .page-content a { color: #C41E3A; text-decoration: underline; text-decoration-color: #C41E3A40; transition: color 0.15s; }
                .page-content a:hover { color: #a91930; }
                .page-content blockquote { border-left: 4px solid #C41E3A; padding: 0.75rem 1.25rem; background: #fff5f5; border-radius: 0 0.5rem 0.5rem 0; color: #6b7280; font-style: italic; margin: 1.25rem 0; }
                .page-content pre { background: #f9fafb; border: 1px solid #e5e7eb; padding: 1.25rem; border-radius: 0.75rem; font-size: 0.825rem; overflow-x: auto; margin: 1.25rem 0; }
                .page-content code { background: #f3f4f6; padding: 0.15rem 0.4rem; border-radius: 0.25rem; font-size: 0.85em; }
                .page-content strong { font-weight: 700; color: #111827; }
                .page-content em { font-style: italic; }
                .page-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
                .page-content th { background: #C41E3A; color: #fff; font-weight: 700; padding: 0.875rem 1rem; text-align: left; font-size: 0.875rem; }
                .page-content td { padding: 0.75rem 1rem; border-bottom: 1px solid #f3f4f6; font-size: 0.9rem; }
                .page-content tr:nth-child(even) td { background: #fafafa; }
                .page-content tr:last-child td { border-bottom: none; }
                .page-content hr { border: none; border-top: 2px solid #f3f4f6; margin: 2rem 0; }
            `}</style>
        </CustomerLayout>
    );
}
