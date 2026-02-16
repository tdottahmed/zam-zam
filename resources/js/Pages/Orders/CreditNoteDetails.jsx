import StorageImage from '@/Components/StorageImage';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function CreditNoteDetails({ creditNote }) {
    const { items } = creditNote;

    // Helper status badge (Reuse or refactor to helper)
    const StatusBadge = ({ status }) => (
        <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium capitalize border ${
            status === 'approved' 
                ? 'bg-green-50 text-green-700 border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-900/30' 
            : status === 'pending' || status === 'draft'
                ? 'bg-yellow-50 text-yellow-700 border-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-900/30'
            : 'bg-red-50 text-red-700 border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30'
        }`}>
            <span className={`w-2 h-2 rounded-full mr-2 ${
                status === 'approved' ? 'bg-green-500' :
                status === 'pending' || status === 'draft' ? 'bg-yellow-500' :
                'bg-red-500'
            }`}></span>
            {status}
        </span>
    );

    return (
        <AuthenticatedLayout title={`Credit Note ${creditNote.credit_note_number}`}>
            <div className="max-w-8xl mx-auto space-y-6">
                 {/* Header / Breadcrumb */}
                 <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div className="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                            <Link href={route('orders.show', creditNote.order_id)} className="hover:text-[#C41E3A] transition-colors">Order #{creditNote.order_id}</Link>
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" /></svg>
                            <span>Credit Note</span>
                        </div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            {creditNote.credit_note_number}
                            <StatusBadge status={creditNote.status} />
                        </h2>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                             Created on {new Date(creditNote.created_at).toLocaleDateString()}
                        </p>
                    </div>
                    {creditNote.status === 'draft' && (
                         <div className="flex gap-3">
                            {/* Actions for draft if needed, e.g. Edit or Delete */}
                         </div>
                    )}
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Main Info */}
                    <div className="md:col-span-2 space-y-6">
                        {/* Reason Card */}
                        <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                            <h3 className="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Reason & Details</h3>
                            <p className="text-lg font-medium text-gray-900 dark:text-white mb-2">{creditNote.reason}</p>
                            {creditNote.admin_notes && (
                                <p className="text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 p-4 rounded-xl text-sm">
                                    "{creditNote.admin_notes}"
                                </p>
                            )}
                        </div>

                        {/* Items Table */}
                         <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white">Items Returned</h3>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                                    <thead className="bg-gray-50 dark:bg-gray-900/50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white dark:bg-[#1E1E1E] divide-y divide-gray-100 dark:divide-gray-800">
                                        {items.map((item) => (
                                            <tr key={item.id}>
                                                <td className="px-6 py-4">
                                                    <div className="flex items-center">
                                                        <div className="h-10 w-10 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden mr-3">
                                                            {item.product?.image && <StorageImage src={item.product.image} alt={item.product.name} className="h-full w-full object-cover" />}
                                                        </div>
                                                        <div>
                                                            <div className="text-sm font-medium text-gray-900 dark:text-white">{item.product?.name}</div>
                                                            {item.reason && <div className="text-xs text-gray-500">Note: {item.reason}</div>}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                    ${Number(item.unit_price).toFixed(2)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                    {item.credit_quantity}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right">
                                                    ${Number(item.line_total).toFixed(2)}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {/* Summary Sidebar */}
                    <div className="space-y-6">
                        <div className="bg-white dark:bg-[#1E1E1E] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Refund Summary</h3>
                            <div className="space-y-3">
                                <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Subtotal</span>
                                    <span>${Number(creditNote.subtotal).toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Tax</span>
                                    <span>${Number(creditNote.tax_amount).toFixed(2)}</span>
                                </div>
                                {(Number(creditNote.shipping_adjustment) !== 0) && (
                                     <div className="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <span>Shipping Adj.</span>
                                        <span>${Number(creditNote.shipping_adjustment).toFixed(2)}</span>
                                    </div>
                                )}
                                <div className="border-t border-gray-100 dark:border-gray-800 pt-3 flex justify-between items-center">
                                    <span className="text-base font-bold text-gray-900 dark:text-white">Total Refund</span>
                                    <span className="text-xl font-bold text-[#C41E3A]">${Number(creditNote.grand_total).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>

                         <div className="bg-blue-50 dark:bg-blue-900/20 rounded-3xl p-6 border border-blue-100 dark:border-blue-900/30">
                            <h4 className="text-blue-800 dark:text-blue-300 font-semibold mb-2">Status Information</h4>
                            <p className="text-sm text-blue-600 dark:text-blue-400">
                                {creditNote.status === 'approved' 
                                    ? 'This credit note has been approved and the amount has been credited to your wallet/account.' 
                                    : 'This request is currently under review. You will be notified once it is processed.'}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
