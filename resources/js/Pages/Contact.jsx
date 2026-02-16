import CustomerLayout from '@/Layouts/CustomerLayout';
import { Head } from '@inertiajs/react';
import Breadcrumb from '@/Components/Breadcrumb';

export default function Contact() {
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
                        <div className="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex items-start gap-4">
                            <div className="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#C41E3A] flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 className="font-bold text-gray-900 text-lg mb-1">Our Location</h3>
                                <p className="text-gray-500 text-sm leading-relaxed">
                                    8905 Hwy 50, Unit 7,<br/> Vaughan, ON L4H 5A1,<br/> Canada
                                </p>
                            </div>
                        </div>

                        <div className="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex items-start gap-4">
                            <div className="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#C41E3A] flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 className="font-bold text-gray-900 text-lg mb-1">Phone</h3>
                                <p className="text-gray-500 text-sm mb-1">+1 (416) 746-5550</p>
                                <p className="text-gray-400 text-xs">Mon-Fri 9am to 6pm EST</p>
                            </div>
                        </div>

                         <div className="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex items-start gap-4">
                            <div className="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-[#C41E3A] flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h3 className="font-bold text-gray-900 text-lg mb-1">Email</h3>
                                <p className="text-gray-500 text-sm">hello@superasia.ca</p>
                                <p className="text-gray-500 text-sm">sales@superasia.ca</p>
                            </div>
                        </div>
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
                <div className="mt-16 bg-gray-100 rounded-3xl h-[400px] w-full overflow-hidden relative grayscale hover:grayscale-0 transition-all duration-500">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2878.96678236774!2d-79.664421!3d43.791244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b3d1b6b555555%3A0x6b55555555555555!2s8905%20Hwy%2050%2C%20Vaughan%2C%20ON%20L4H%205A1!5e0!3m2!1sen!2sca!4v1620000000000!5m2!1sen!2sca" 
                        width="100%" 
                        height="100%" 
                        style={{border:0}} 
                        allowFullScreen="" 
                        loading="lazy"
                        referrerPolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </CustomerLayout>
    );
}
