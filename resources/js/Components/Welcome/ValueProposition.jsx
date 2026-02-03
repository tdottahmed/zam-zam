export default function ValueProposition() {
    return (
        <section className="py-20 px-12 bg-white">
            <div className="text-center mb-16">
                <h2 className="text-3xl font-bold mb-4">Why Partner with Zam Zam Import export Inc?</h2>
                <p className="text-gray-600 max-w-2xl mx-auto">
                    We make it easy for you to stock your shelves with the best products from South Asia.
                </p>
            </div>
            <div className="grid md:grid-cols-3 gap-12">
                {[
                    { title: "Reliable Supply Chain", desc: "Efficient delivery to over 50 cities across Canada." },
                    { title: "Diverse Product Range", desc: "Extensive product range tailored to meet your needs." },
                    { title: "Expert Support", desc: "Our team is ready to assist you with personalized support." }
                ].map((feature) => (
                    <div key={feature.title} className="text-center p-6">
                        <div className="h-16 w-16 bg-[#C41E3A]/10 text-[#C41E3A] rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                            ✓
                        </div>
                        <h3 className="text-xl font-bold mb-3">{feature.title}</h3>
                        <p className="text-gray-600">{feature.desc}</p>
                    </div>
                ))}
            </div>
        </section>
    );
}
