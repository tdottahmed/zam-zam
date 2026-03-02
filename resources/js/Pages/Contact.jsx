import CustomerLayout from '@/Layouts/CustomerLayout';
import { Head, usePage } from '@inertiajs/react';
import Breadcrumb from '@/Components/Breadcrumb';

export default function Contact() {
    const { settings = {} } = usePage().props;
    const address = settings.address || '';
    const addressAlt = settings.address_alt || '';
    const phone = settings.contact_phone || '';
    const cell = settings.contact_cell || '';
    const mapEmbedUrl = settings.map_embed_url || '';
    const emails = [
        settings.contact_email,
        settings.contact_email_alt_1,
        settings.contact_email_alt_2,
    ].filter(Boolean);

    // Build "Open in Google Maps" link from first line of address (or generic search)
    const mapsSearchQuery = address ? address.split('\n')[0].trim() : 'Zam Zam Import Export';
    const openInMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(mapsSearchQuery)}`;

    return (
        <CustomerLayout>
            <Head title="Contact Us" />

            <Breadcrumb 
                title="Contact Us" 
                links={[
                    { label: 'Contact', active: true }
                ]} 
            />

            <div className="max-w-[1920px] mx-auto px-4 lg:px-8 relative z-10 py-12">
                <div className="grid lg:grid-cols-3 gap-8">
                    {/* Contact Info Cards */}
                    <div className="lg:col-span-1 space-y-6">
                        {(address || addressAlt) && (
                            <div className="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex items-start gap-4">
                                <div className="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#C41E3A] flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="font-bold text-gray-900 text-lg mb-1">Our Location</h3>
                                    {address && (
                                        <p className="text-gray-500 text-sm leading-relaxed whitespace-pre-line">{address}</p>
                                    )}
                                    {addressAlt && (
                                        <p className="text-gray-500 text-sm leading-relaxed mt-2 whitespace-pre-line">{addressAlt}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {(phone || cell) && (
                            <div className="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex items-start gap-4">
                                <div className="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#C41E3A] flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="font-bold text-gray-900 text-lg mb-1">Phone</h3>
                                    {phone && <p className="text-gray-500 text-sm mb-1">{phone}</p>}
                                    {cell && <p className="text-gray-500 text-sm mb-1">{cell}</p>}
                                    <p className="text-gray-400 text-xs mt-1">Mon-Fri 9am to 6pm EST</p>
                                </div>
                            </div>
                        )}

                        {emails.length > 0 && (
                            <div className="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex items-start gap-4">
                                <div className="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#C41E3A] flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="font-bold text-gray-900 text-lg mb-1">Email</h3>
                                    {emails.map((email, index) => (
                                        <p key={`contact-email-${index}`} className="text-gray-500 text-sm">
                                            <a href={`mailto:${email}`} className="hover:text-[#C41E3A] transition-colors">
                                                {email}
                                            </a>
                                        </p>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Contact Form */}
                    <div className="lg:col-span-2 bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 lg:p-12">
                        <h2 className="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                        <form className="space-y-6" onSubmit={(e) => e.preventDefault()}>
                            <div className="grid md:grid-cols-2 gap-6">
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-gray-700 uppercase tracking-wide">First Name</label>
                                    <input type="text" className="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent transition" placeholder="John" />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-gray-700 uppercase tracking-wide">Last Name</label>
                                    <input type="text" className="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent transition" placeholder="Doe" />
                                </div>
                            </div>
                            
                            <div className="grid md:grid-cols-2 gap-6">
                                 <div className="space-y-2">
                                    <label className="text-sm font-bold text-gray-700 uppercase tracking-wide">Email Address</label>
                                    <input type="email" className="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent transition" placeholder="john@example.com" />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-gray-700 uppercase tracking-wide">Phone Number</label>
                                    <input type="tel" className="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent transition" placeholder="+1 (555) 000-0000" />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-bold text-gray-700 uppercase tracking-wide">Subject</label>
                                <select className="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent transition">
                                    <option>General Inquiry</option>
                                    <option>Wholesale Partnership</option>
                                    <option>Order Status</option>
                                    <option>Product Feedback</option>
                                </select>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-bold text-gray-700 uppercase tracking-wide">Message</label>
                                <textarea className="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#C41E3A] focus:border-transparent transition h-32" placeholder="How can we help you?"></textarea>
                            </div>

                            <button className="bg-[#C41E3A] text-white px-8 py-4 rounded-lg font-bold uppercase tracking-widest hover:bg-[#a91930] shadow-lg shadow-red-200 transition-all w-full md:w-auto">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

                {/* Map Section */}
                <div className="mt-16 rounded-3xl overflow-hidden h-[400px] w-full bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 shadow-inner relative">
                    {mapEmbedUrl ? (
                        <iframe
                            src={mapEmbedUrl}
                            width="100%"
                            height="100%"
                            style={{ border: 0 }}
                            allowFullScreen=""
                            loading="lazy"
                            referrerPolicy="no-referrer-when-downgrade"
                            title="Our location on the map"
                            className="grayscale-[0.2] hover:grayscale-0 transition-all duration-500"
                        />
                    ) : (
                        <div className="absolute inset-0 flex flex-col items-center justify-center gap-4 p-8 text-center">
                            <div className="w-14 h-14 rounded-2xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-8 h-8">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <div>
                                <p className="font-semibold text-slate-700 dark:text-slate-300">View our location</p>
                                <p className="text-sm text-slate-500 dark:text-slate-400 mt-1">Open in Google Maps for directions</p>
                            </div>
                            <a
                                href={openInMapsUrl}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-2 px-5 py-2.5 bg-[#C41E3A] text-white text-sm font-semibold rounded-xl hover:bg-[#a91930] shadow-lg shadow-red-200/50 transition-all"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Open in Google Maps
                            </a>
                        </div>
                    )}
                </div>
            </div>
        </CustomerLayout>
    );
}
