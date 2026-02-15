import React from 'react';
import { RadioGroup } from '@headlessui/react';

export default function AddressSelector({ addresses, selectedAddressId, onChange, onAddNew }) {
    return (
        <div className="space-y-4">
            <RadioGroup value={selectedAddressId} onChange={onChange}>
                <RadioGroup.Label className="sr-only">Select an address</RadioGroup.Label>
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    {addresses.map((address) => (
                        <RadioGroup.Option
                            key={address.id}
                            value={address.id}
                            className={({ active, checked }) =>
                                `${active ? 'ring-2 ring-[#C41E3A] ring-offset-2' : ''}
                                ${checked ? 'bg-[#C41E3A] text-white border-transparent' : 'bg-white text-gray-900 border-gray-200 hover:bg-gray-50'}
                                relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none transition-all`
                            }
                        >
                            {({ checked }) => (
                                <>
                                    <span className="flex flex-1">
                                        <span className="flex flex-col">
                                            <RadioGroup.Label as="span" className="block text-sm font-bold">
                                                {address.name}
                                            </RadioGroup.Label>
                                            <RadioGroup.Description as="span" className={`mt-1 flex items-center text-sm ${checked ? 'text-white' : 'text-gray-500'}`}>
                                                {address.address_line_1}, {address.city}, {address.postal_code}
                                            </RadioGroup.Description>
                                            <RadioGroup.Description as="span" className={`mt-2 text-xs font-medium ${checked ? 'text-red-100' : 'text-gray-500'}`}>
                                                {address.phone}
                                            </RadioGroup.Description>
                                        </span>
                                    </span>
                                    {checked && (
                                        <svg className="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                    )}
                                </>
                            )}
                        </RadioGroup.Option>
                    ))}
                    
                    {/* Add New Address Option */}
                    <div 
                        onClick={onAddNew}
                        className={`
                            ${selectedAddressId === 'new' ? 'ring-2 ring-[#C41E3A] ring-offset-2 border-[#C41E3A] bg-red-50' : 'border-gray-200 border-dashed hover:border-[#C41E3A] hover:bg-red-50/50'}
                            relative flex cursor-pointer rounded-xl border-2 p-4 shadow-sm focus:outline-none transition-all items-center justify-center text-center
                        `}
                    >
                        <div className="flex flex-col items-center justify-center py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`w-8 h-8 mb-2 ${selectedAddressId === 'new' ? 'text-[#C41E3A]' : 'text-gray-400'}`}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span className={`block text-sm font-bold ${selectedAddressId === 'new' ? 'text-[#C41E3A]' : 'text-gray-500'}`}>
                                Add New Address
                            </span>
                        </div>
                    </div>
                </div>
            </RadioGroup>
        </div>
    );
}
