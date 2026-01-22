import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center relative" 
             style={{ backgroundImage: "url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')" }}>
            
            {/* Overlay */}
            <div className="absolute inset-0 bg-black/40 backdrop-blur-sm z-0"></div>

            <div className="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white/10 shadow-2xl overflow-hidden sm:rounded-xl backdrop-blur-md border border-white/20">
                <div className="flex justify-center mb-8">
                    <Link href="/">
                        <ApplicationLogo className="w-20 h-20 fill-current text-white drop-shadow-lg" />
                    </Link>
                </div>
                {children}
            </div>
        </div>
    );
}
