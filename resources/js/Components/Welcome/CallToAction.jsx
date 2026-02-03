export default function CallToAction() {
    return (
        <section className="py-16 px-12 bg-[#C41E3A] text-white text-center md:text-left flex flex-col md:flex-row items-center justify-between">
            <div>
                <h2 className="text-3xl font-bold mb-2">Download Our Complete Product Catalog</h2>
                <p className="text-red-100 max-w-xl">Browse through our extensive collection of South Asian products.</p>
            </div>
            <div className="mt-8 md:mt-0">
                <button className="px-8 py-3 border-2 border-white text-white font-bold rounded hover:bg-white hover:text-[#C41E3A] transition">
                    Download Catalog
                </button>
            </div>
        </section>
    );
}
