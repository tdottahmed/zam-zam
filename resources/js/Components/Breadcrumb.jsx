import { Link } from '@inertiajs/react';

export default function Breadcrumb({ title, links = [] }) {
    return (
        <div className="relative bg-white overflow-hidden border-b border-gray-100">
            {/* Dynamic Background Shapes */}
            <div className="absolute inset-0 w-full h-full overflow-hidden pointer-events-none">
                {/* Red Gradient Hero Blob - Left */}
                <div className="absolute -top-[50%] -left-[10%] w-[50%] h-[200%] bg-gradient-to-r from-red-50/40 to-transparent rotate-12 blur-3xl opacity-60 animate-pulse" />
                
                {/* Red Gradient Hero Blob - Right */}
                <div className="absolute top-0 right-0 w-[40%] h-[150%] bg-gradient-to-l from-red-50/40 to-transparent -rotate-12 blur-3xl opacity-60" />

                {/* Floating Orbs */}
                <div className="absolute top-[20%] left-[15%] w-32 h-32 bg-red-100/40 rounded-full blur-2xl mix-blend-multiply animate-blob" />
                <div className="absolute top-[40%] right-[15%] w-48 h-48 bg-gray-100/60 rounded-full blur-3xl mix-blend-multiply animate-blob animation-delay-2000" />
                <div className="absolute bottom-0 left-[40%] w-64 h-64 bg-red-50/30 rounded-full blur-3xl mix-blend-multiply animate-blob animation-delay-4000" />
                
                {/* Geometric Grid Pattern */}
                <div className="absolute inset-0 opacity-[0.03]" style={{ backgroundImage: 'radial-gradient(#C41E3A 1px, transparent 1px)', backgroundSize: '32px 32px' }}></div>
            </div>

            <div className="max-w-[1920px] mx-auto px-6 lg:px-12 relative z-10 py-16 lg:py-20 text-center">
                <div className="flex flex-col items-center justify-center gap-4">
                    {/* Breadcrumb Links */}
                    <nav className="flex items-center justify-center text-sm font-medium text-gray-500 mb-4 animate-fade-in-up bg-white/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-100/50 shadow-sm inline-flex">
                        <Link href="/" className="hover:text-[#C41E3A] transition-colors flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="w-4 h-4">
                              <path strokeLinecap="round" strokeLinejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Home
                        </Link>
                        {links.map((link, index) => (
                            <div key={index} className="flex items-center">
                                <svg className="h-4 w-4 text-gray-300 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                </svg>
                                {link.href ? (
                                    <Link href={link.href} className="hover:text-[#C41E3A] transition-colors">{link.label}</Link>
                                ) : (
                                    <span className="text-[#C41E3A] font-semibold" aria-current="page">
                                        {link.label}
                                    </span>
                                )}
                            </div>
                        ))}
                    </nav>
                    
                    {/* Title with Decorative Elements */}
                    <div className="relative inline-block">
                         <h1 className="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight leading-tight mb-2 animate-fade-in-up animation-delay-100">
                             {title}
                        </h1>
                        {/* Underline Decoration */}
                        <div className="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-[#C41E3A] rounded-full opacity-90">
                            <div className="absolute top-0 right-[-10px] w-2 h-full bg-[#C41E3A] rounded-full opacity-50"></div>
                            <div className="absolute top-0 left-[-10px] w-2 h-full bg-[#C41E3A] rounded-full opacity-50"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
