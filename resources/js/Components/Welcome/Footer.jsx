export default function Footer() {
    return (
        <footer className="bg-[#1A1A1A] text-[#BBBBBB] py-16 px-12">
            <div className="grid md:grid-cols-3 gap-12 mb-12">
                 <div>
                    <h3 className="text-white font-bold mb-4 uppercase tracking-wider">About</h3>
                    <p className="mb-6 text-sm leading-relaxed">
                        Discover the true taste of South Asia with Zam Zam Import export Inc. Your trusted partner for authentic ethnic distribution.
                    </p>
                    <div className="flex space-x-4">
                        <span>FB</span> <span>IG</span> <span>LI</span>
                    </div>
                 </div>
                 <div>
                    <h3 className="text-white font-bold mb-4 uppercase tracking-wider">Other Links</h3>
                     <ul className="space-y-2 text-sm">
                        <li><a href="#" className="hover:text-white">Blog</a></li>
                        <li><a href="#" className="hover:text-white">Careers</a></li>
                        <li><a href="#" className="hover:text-white">FAQ's</a></li>
                        <li><a href="#" className="hover:text-white">About Us</a></li>
                        <li><a href="#" className="hover:text-white">Contact Us</a></li>
                     </ul>
                 </div>
                 <div>
                    <h3 className="text-white font-bold mb-4 uppercase tracking-wider">Contact Us</h3>
                    <ul className="space-y-2 text-sm">
                        <li>8905 Hwy 50, Unit 7, Vaughan ON</li>
                        <li>+1 416-746-5550</li>
                        <li>hello@superasia.ca</li>
                    </ul>
                 </div>
            </div>
            <div className="border-t border-gray-800 pt-8 text-center text-xs">
                Copyright © Zam Zam Import export Inc. Powered by Laravel & Inertia.
            </div>
        </footer>
    );
}
