import Header from '@/Components/Welcome/Header';
import Footer from '@/Components/Welcome/Footer';
import Toast from '@/Components/Toast';

export default function CustomerLayout({ children }) {
    return (
        <div className="font-sans text-[#333333] antialiased bg-white selection:bg-[#C41E3A] selection:text-white">
            <Header />
            <main>
                {children}
            </main>
            <Footer />
            <Toast />
        </div>
    );
}
