import { Transition } from '@headlessui/react';
import { usePage } from '@inertiajs/react';
import { Fragment, useEffect, useState } from 'react';

export default function Toast() {
    const { flash } = usePage().props;
    const [show, setShow] = useState(false);
    const [message, setMessage] = useState('');
    const [type, setType] = useState('info'); // success, error, warning, info

    useEffect(() => {
        if (flash?.success) {
            setMessage(flash.success);
            setType('success');
            setShow(true);
        } else if (flash?.error) {
            setMessage(flash.error);
            setType('error');
            setShow(true);
        } else if (flash?.warning) {
            setMessage(flash.warning);
            setType('warning');
            setShow(true);
        } else if (flash?.info) {
            setMessage(flash.info);
            setType('info');
            setShow(true);
        }
    }, [flash]);

    useEffect(() => {
        if (show) {
            const timer = setTimeout(() => {
                setShow(false);
                // Clear flash messages manually if needed, but usually Inertia handles this on distinct requests.
                // However, for single page app feel, we just hide it.
            }, 4000); 
            return () => clearTimeout(timer);
        }
    }, [show]);

    const colors = {
        success: 'border-green-500',
        error: 'border-red-500',
        warning: 'border-yellow-500',
        info: 'border-blue-500',
    };

    const icons = {
        success: (
            <svg className="h-6 w-6 text-green-400 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        ),
        error: (
            <svg className="h-6 w-6 text-red-400 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        ),
        warning: (
            <svg className="h-6 w-6 text-yellow-400 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        ),
        info: (
            <svg className="h-6 w-6 text-blue-400 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        ),
    };

    return (
        <div
            aria-live="assertive"
            className="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-[100]"
        >
            <div className="w-full flex flex-col items-center space-y-4 sm:items-end">
                <Transition
                    show={show}
                    as={Fragment}
                    enter="transform ease-out duration-300 transition"
                    enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    enterTo="translate-y-0 opacity-100 sm:translate-x-0"
                    leave="transition ease-in duration-100"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className={`max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden border-l-4 ${colors[type]}`}>
                        <div className="p-4">
                            <div className="flex items-start">
                                <div className="flex-shrink-0">
                                    {icons[type]}
                                </div>
                                <div className="ml-3 w-0 flex-1 pt-0.5">
                                    <p className="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {type.charAt(0).toUpperCase() + type.slice(1)}
                                    </p>
                                    <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {message}
                                    </p>
                                </div>
                                <div className="ml-4 flex-shrink-0 flex">
                                    <button
                                        className="bg-transparent rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        onClick={() => setShow(false)}
                                    >
                                        <span className="sr-only">Close</span>
                                        <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    );
}
