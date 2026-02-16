import { Link } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function Footer() {
    return (
        <footer className="bg-[#111111] text-gray-300 border-t border-gray-800">
            {/* Main Footer Content */}
            <div className="max-w-[1920px] mx-auto px-4 lg:px-8 py-16 lg:py-24">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
                    {/* Brand Column */}
                    <div className="lg:col-span-4 space-y-6">
                        <div className="flex items-center gap-2">
                            <ApplicationLogo className="h-10 w-auto fill-current text-white" />
                            <span className="text-2xl font-black text-white tracking-tight">ZAM ZAM</span>
                        </div>
                        <p className="text-gray-400 leading-relaxed max-w-sm">
                            Your trusted partner for authentic ethnic distribution. We bring the true taste of South Asia directly to Canadian markets with uncompromised quality.
                        </p>
                        <div className="flex gap-4 pt-2">
                             {/* Social Icons - Using SVG directly for better control */}
                            {['facebook', 'instagram', 'linkedin'].map((social) => (
                                <a 
                                    key={social} 
                                    href="#" 
                                    className="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-[#C41E3A] hover:text-white transition-all duration-300 transform hover:-translate-y-1"
                                >
                                    <span className="sr-only">{social}</span>
                                    {/* Placeholder icons - replace with actual SVGs if available */}
                                    <svg className="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                    </svg>
                                </a>
                            ))}
                        </div>
                    </div>

                    {/* Quick Links */}
                    <div className="lg:col-span-2 lg:col-start-6">
                        <h3 className="text-white font-bold mb-6 text-sm uppercase tracking-widest">Company</h3>
                        <ul className="space-y-4">
                            {['About Us', 'Careers', 'Our Team', 'Contact'].map((item) => (
                                <li key={item}>
                                    <Link href="#" className="hover:text-[#C41E3A] transition-colors duration-200 block text-sm">
                                        {item}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="lg:col-span-2">
                        <h3 className="text-white font-bold mb-6 text-sm uppercase tracking-widest">Support</h3>
                        <ul className="space-y-4">
                            {['FAQ', 'Shipping', 'Returns', 'Privacy Policy'].map((item) => (
                                <li key={item}>
                                    <Link href="#" className="hover:text-[#C41E3A] transition-colors duration-200 block text-sm">
                                        {item}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Newsletter */}
                    <div className="lg:col-span-4 lg:col-start-10 md:col-span-2">
                        <h3 className="text-white font-bold mb-6 text-sm uppercase tracking-widest">Stay Updated</h3>
                        <p className="text-gray-400 text-sm mb-6">Subscribe to our newsletter for the latest products and exclusive offers.</p>
                        <form className="relative" onSubmit={(e) => e.preventDefault()}>
                            <input 
                                type="email" 
                                placeholder="Enter your email" 
                                className="w-full bg-gray-800 border-none rounded-lg py-4 pl-4 pr-32 text-white placeholder-gray-500 focus:ring-2 focus:ring-[#C41E3A] transition-all"
                            />
                            <button 
                                type="submit" 
                                className="absolute right-1 top-1 bottom-1 bg-[#C41E3A] text-white px-6 rounded-md font-bold text-sm hover:bg-red-700 transition-colors uppercase tracking-wider"
                            >
                                Join
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {/* Bottom Bar */}
            <div className="border-t border-gray-800 bg-[#0a0a0a]">
                <div className="max-w-[1920px] mx-auto px-4 lg:px-8 py-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p className="text-sm text-gray-500">
                        &copy; {new Date().getFullYear()} Zam Zam Import Export Inc. All rights reserved.
                    </p>
                    <div className="flex gap-8 text-sm text-gray-500">
                        <a href="#" className="hover:text-white transition-colors">Terms of Service</a>
                        <a href="#" className="hover:text-white transition-colors">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    );
}
