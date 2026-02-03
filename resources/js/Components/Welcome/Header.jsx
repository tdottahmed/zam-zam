import { Link } from '@inertiajs/react';

export default function Header({ auth }) {
    return (
        <nav className="flex items-center justify-between px-12 py-4 bg-white shadow-sm sticky top-0 z-50">
            <div className="flex items-center">
                {/* Logo */}
                <div className="text-2xl font-bold text-[#C41E3A]">
                     <img src="/images/Zam_logo-120x99.png" alt="Zam Zam Import export Inc" className="h-16 w-auto" />
                </div>
            </div>
            <div className="hidden md:flex space-x-8 font-medium text-gray-700">
                <Link href="#" className="text-[#C41E3A]">Home</Link>
                <Link href="#" className="hover:text-[#C41E3A] transition">Shop</Link>
                <Link href="#" className="hover:text-[#C41E3A] transition">Shop by Brand</Link>
                <Link href="#" className="hover:text-[#C41E3A] transition">About Us</Link>
                <Link href="#" className="hover:text-[#C41E3A] transition">Contact us</Link>
                
                {auth.user ? (
                    <Link href={route('dashboard')} className="hover:text-[#C41E3A] transition">Dashboard</Link>
                ) : (
                    <Link href={route('login')} className="hover:text-[#C41E3A] transition">Sign in</Link>
                )}
            </div>
            <div>
                <Link href={route('login')} className="px-6 py-2 bg-[#C41E3A] text-white rounded hover:bg-[#a91930] transition font-medium">
                    Retailer Login
                </Link>
            </div>
        </nav>
    );
}
