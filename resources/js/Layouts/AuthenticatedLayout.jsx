import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';

export default function AuthenticatedLayout({ header, children }) {
    const user = usePage().props.auth.user;

    const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);
    const [darkMode, setDarkMode] = useState(false);

    useEffect(() => {
        if (darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }, [darkMode]);

    const toggleDarkMode = () => {
        setDarkMode(!darkMode);
    };

    return (
        <div className="min-h-screen bg-gray-50 dark:bg-[#121212] transition-colors duration-300">
            <nav className="bg-white dark:bg-[#1E1E1E] border-b border-gray-100 dark:border-gray-800 shadow-sm sticky top-0 z-50 transition-colors duration-300">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 justify-between">
                        <div className="flex">
                            <div className="flex shrink-0 items-center">
                                <Link href="/">
                                    <ApplicationLogo className="block h-9 w-auto fill-current text-[#C41E3A] drop-shadow-sm" />
                                </Link>
                            </div>

                            <div className="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    href={route('dashboard')}
                                    active={route().current('dashboard')}
                                    className="text-gray-900 dark:text-gray-100 hover:text-[#C41E3A] dark:hover:text-[#C41E3A]"
                                >
                                    Dashboard
                                </NavLink>
                                {user.user_type === 'admin' && (
                                    <a
                                        href={route('admin.dashboard')}
                                        className="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-[#C41E3A] dark:hover:text-[#C41E3A] hover:border-[#C41E3A] focus:outline-none transition duration-150 ease-in-out"
                                    >
                                        Admin Panel
                                    </a>
                                )}
                            </div>
                        </div>

                        <div className="hidden sm:ms-6 sm:flex sm:items-center space-x-4">
                            {/* Dark Mode Toggle */}
                            <button onClick={toggleDarkMode} className="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                {darkMode ? (
                                    <span className="text-yellow-400 text-xl">☀️</span>
                                ) : (
                                    <span className="text-gray-600 dark:text-gray-300 text-xl">🌙</span>
                                )}
                            </button>

                            <div className="relative ms-3">
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <span className="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                className="inline-flex items-center space-x-3 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-1 pl-1 pr-3 transition duration-150 ease-in-out hover:shadow-md focus:outline-none"
                                            >
                                                 {/* Gorgeous Avatar */}
                                                <div className="h-8 w-8 rounded-full bg-gradient-to-tr from-[#C41E3A] to-orange-500 flex items-center justify-center text-white font-bold shadow-sm">
                                                    {user.name.charAt(0).toUpperCase()}
                                                </div>
                                                <div className="text-sm font-medium leading-4 text-gray-700 dark:text-gray-300">
                                                    {user.name}
                                                </div>
                                                <svg
                                                    className="-me-0.5 ms-2 h-4 w-4 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </Dropdown.Trigger>

                                    <Dropdown.Content align='right' contentClasses="py-1 bg-white dark:bg-[#2A2A2A] ring-1 ring-black ring-opacity-5">
                                        <div className="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                            <p className="text-sm text-gray-500 dark:text-gray-400">Signed in as</p>
                                            <p className="text-sm font-medium text-gray-900 dark:text-white truncate">{user.email}</p>
                                        </div>
                                        <Dropdown.Link href={route('profile.edit')} className="hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">
                                            Profile
                                        </Dropdown.Link>
                                        <Dropdown.Link href={route('logout')} method="post" as="button" className="text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400">
                                            Log Out
                                        </Dropdown.Link>
                                    </Dropdown.Content>
                                </Dropdown>
                            </div>
                        </div>

                        {/* Mobile Hamburger */}
                        <div className="-me-2 flex items-center sm:hidden">
                            <button
                                onClick={() => setShowingNavigationDropdown((previousState) => !previousState)}
                                className="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-500 dark:hover:text-gray-300 focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-300 focus:outline-none"
                            >
                                <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        className={!showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        className={showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Mobile Menu */}
                <div className={(showingNavigationDropdown ? 'block' : 'hidden') + ' sm:hidden'}>
                    <div className="space-y-1 pb-3 pt-2 bg-white dark:bg-[#1E1E1E]">
                        <ResponsiveNavLink href={route('dashboard')} active={route().current('dashboard')}>
                            Dashboard
                        </ResponsiveNavLink>
                        {user.user_type === 'admin' && (
                             <a href={route('admin.dashboard')} className="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-[#C41E3A] dark:hover:text-[#C41E3A] hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out">
                                Admin Panel
                             </a>
                        )}
                    </div>

                    <div className="border-t border-gray-200 dark:border-gray-700 pb-1 pt-4 bg-gray-50 dark:bg-[#252525]">
                        <div className="px-4 flex items-center">
                            <div className="shrink-0 me-3">
                                 <div className="h-10 w-10 rounded-full bg-gradient-to-tr from-[#C41E3A] to-orange-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                    {user.name.charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <div>
                                <div className="text-base font-medium text-gray-800 dark:text-gray-200">{user.name}</div>
                                <div className="text-sm font-medium text-gray-500 dark:text-gray-400">{user.email}</div>
                            </div>
                        </div>

                        <div className="mt-3 space-y-1">
                            <ResponsiveNavLink href={route('profile.edit')}>Profile</ResponsiveNavLink>
                            <ResponsiveNavLink method="post" href={route('logout')} as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            {header && (
                <header className="bg-white dark:bg-[#1E1E1E] shadow dark:shadow-gray-900/50 transition-colors duration-300">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </header>
            )}

            <main className="transition-colors duration-300">{children}</main>
        </div>
    );
}
