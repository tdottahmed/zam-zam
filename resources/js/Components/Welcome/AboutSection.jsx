export default function AboutSection() {
    return (
        <section className="py-20 px-12 grid md:grid-cols-2 gap-16 items-start bg-gray-50">
             {/* About */}
            <div>
                <h2 className="text-3xl font-bold mb-6">Bringing South Asia to Canada</h2>
                <p className="text-gray-600 mb-6 leading-relaxed">
                    Zam Zam Import export Inc is committed to delivering high-quality products from South Asia to the Canadian market. We bridge the gap between authentic ethnic flavors and local retailers, ensuring that communities across Canada have access to the brands they love.
                </p>
                <button className="text-[#C41E3A] font-bold hover:underline">Learn More About Us &rarr;</button>
            </div>

            {/* How It Works */}
            <div>
                <h2 className="text-3xl font-bold mb-8">How It Works</h2>
                <div className="space-y-8">
                    {[
                        { num: 1, title: "Reach Out to Us", link: "Get in Touch" },
                        { num: 2, title: "Explore Our Catalogue", link: "See Catalogue" },
                        { num: 3, title: "Place Your Order", link: "Order Now" }
                    ].map((step) => (
                        <div key={step.num} className="flex gap-6">
                            <div className="flex-shrink-0 h-10 w-10 bg-gray-900 text-white rounded-full flex items-center justify-center font-bold">
                                {step.num}
                            </div>
                            <div>
                                <h3 className="text-xl font-bold mb-1">{step.title}</h3>
                                <a href="#" className="text-[#C41E3A] text-sm font-semibold hover:underline">{step.link}</a>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
