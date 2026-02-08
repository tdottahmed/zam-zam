import { useState, useEffect } from 'react';
import Header from '@/Components/Welcome/Header';
import Footer from '@/Components/Welcome/Footer';
import DashboardSidebar from '@/Components/DashboardSidebar';
import Toast from '@/Components/Toast';
import { Head, usePage } from '@inertiajs/react';

export default function AuthenticatedLayout({ header, children, title }) {
    const user = usePage().props.auth.user;
    const [darkMode, setDarkMode] = useState(false);

    useEffect(() => {
        if (darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }, [darkMode]);

    return (
        <div className="font-sans text-[#333333] antialiased bg-gray-50/50 dark:bg-[#121212] min-h-screen flex flex-col transition-colors duration-300">
            {title && <Head title={title} />}
            
            <Header />

            <div className="flex flex-1 py-4 pb-12 max-w-[1440px] mx-auto w-full px-4 sm:px-6 lg:px-8 gap-8">
                 {/* Sidebar for Desktop - Sticky positioning */}
                <div className="hidden lg:block w-72 flex-shrink-0">
                     <div className="sticky top-28 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800 bg-white dark:bg-[#1E1E1E]">
                        <DashboardSidebar className="h-auto border-none" />
                    </div>
                </div>

                {/* Main Content Area */}
                <main className="flex-1 w-full min-w-0">
                     
                    {header && (
                        <div className="mb-6">
                            {header}
                        </div>
                    )}
                    {children}
                </main>
            </div>

            <Footer />
            <Toast />
        </div>
    );
}
