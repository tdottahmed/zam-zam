import CustomerLayout from '@/Layouts/CustomerLayout';
import { Head } from '@inertiajs/react';
import HeroSection from '@/Components/Welcome/HeroSection';
import BrandsGrid from '@/Components/Welcome/BrandsGrid';
import CategoriesGrid from '@/Components/Welcome/CategoriesGrid';
import ValueProposition from '@/Components/Welcome/ValueProposition';
import CallToAction from '@/Components/Welcome/CallToAction';
import AboutSection from '@/Components/Welcome/AboutSection';

export default function Welcome({ auth, brands, categories }) {
    return (
        <CustomerLayout auth={auth}>
            <Head title="Zam Zam Import export Inc - Landing Page" />
            <HeroSection />
            <BrandsGrid brands={brands} />
            <CategoriesGrid categories={categories} />
            <ValueProposition />
            <CallToAction />
            <AboutSection />
        </CustomerLayout>
    );
}
