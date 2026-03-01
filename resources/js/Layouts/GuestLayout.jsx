import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center relative" 
             style={{ backgroundImage: "url('/images/login-bg.jpg')" }}>
            
            {/* Overlay */}
            <div className="absolute inset-0 bg-black/40 backdrop-blur-sm z-0"></div>

            <div className="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white/10 shadow-2xl overflow-hidden sm:rounded-xl backdrop-blur-md border border-white/20">
                <div className="flex justify-center mb-8">
                    <Link href="/" className="flex flex-col items-center">
                        <ApplicationLogo className="w-24 h-24 drop-shadow-lg" />
                        <span className="mt-4 text-xl font-bold text-white text-center tracking-wider drop-shadow-md">
                            Zam Zam Import export Inc
                        </span>
                    </Link>
                </div>
                {children}
            </div>
        </div>
    );
}
