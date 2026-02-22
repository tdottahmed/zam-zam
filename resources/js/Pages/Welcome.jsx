import CustomerLayout from '@/Layouts/CustomerLayout';
import { Head } from '@inertiajs/react';
import HeroSection from '@/Components/Welcome/HeroSection';
import FeaturedProducts from '@/Components/Welcome/FeaturedProducts';
import BrandsGrid from '@/Components/Welcome/BrandsGrid';
import CategoriesGrid from '@/Components/Welcome/CategoriesGrid';
import ValueProposition from '@/Components/Welcome/ValueProposition';
import CallToAction from '@/Components/Welcome/CallToAction';

export default function Welcome({ auth, brands, categories, featuredProducts = [], featuredProductsIsBestSelling = false }) {
    return (
        <CustomerLayout auth={auth}>
            <Head title="Zam Zam Import export Inc - Landing Page" />
            <HeroSection />
            <FeaturedProducts
                products={featuredProducts}
                isBestSelling={featuredProductsIsBestSelling}
            />
            <BrandsGrid brands={brands} />
            <CategoriesGrid categories={categories} />
            <ValueProposition />
            <CallToAction />
        </CustomerLayout>
    );
}
