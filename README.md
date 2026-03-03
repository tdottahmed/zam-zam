<h1 align="center">Zam Zam Import Export Management System</h1>

<p align="center">
  A comprehensive, modern Laravel-based application for managing e-commerce, import/export operations, orders, and invoicing.
</p>

## 🚀 Overview

**Zam Zam Import Export** is a robust and scalable web application tailored for managing business operations. Built on a modern tech stack featuring **Laravel 12**, **Inertia.js**, and **React**, it provides a seamless single-page application (SPA) experience for both end-users and administrators.

## ✨ Features

- **🛒 E-Commerce Functionality**: Robust shopping cart, wishlist, and seamless checkout experience.
- **📦 Product Management**: Comprehensive inventory control with categories, brands, units, and bulk Excel import capabilities.
- **🧾 Order & Invoice Management**: Complete lifecycle tracking for orders, dynamically generated PDF invoices, and credit notes.
- **👥 User & Role Management**: Detailed user profiles, multiple shipping/billing addresses, and secure authentication.
- **⚙️ Dynamic System Settings**: Easily configurable global settings, taxes, shipping methods, and offline payment methods.
- **📊 Analytics Dashboard**: Real-time sales metrics, transaction tracking (income/expense), and dynamic data visualization.

## 🛠️ Tech Stack

- **Backend**: [Laravel 12.x](https://laravel.com/) (PHP 8.2+)
- **Frontend**: [React 18](https://reactjs.org/) & [Inertia.js](https://inertiajs.com/)
- **Styling**: [Tailwind CSS 3.x](https://tailwindcss.com/)
- **Database**: MySQL / SQLite
- **PDF Generation**: [Laravel mPDF](https://github.com/mccarlosen/laravel-mpdf)
- **Excel Handling**: [Laravel Excel](https://github.com/SpartnerNL/Laravel-Excel)
- **Build Tool**: [Vite](https://vitejs.dev/)

## 💻 Getting Started

### Prerequisites

Ensure you have the following installed on your local machine:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL or your preferred database

### Installation Step-by-Step

**1. Clone the repository**
```bash
git clone https://github.com/tdottahmed/zam-zam.git
cd zam-zam
```

**2. Install PHP dependencies**
```bash
composer install
```

**3. Install NPM dependencies**
```bash
npm install
```

**4. Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```
*Note: Update your `.env` file with your specific database credentials.*

**5. Run Database Migrations & Seeders**
```bash
php artisan migrate --seed
```

**6. Compile Frontend Assets**
```bash
npm run build
# Or for development: npm run dev
```

**7. Serve the Application**
```bash
php artisan serve
```
*Visit http://localhost:8000 in your browser.*

## 📂 Key Architecture Highlights

- **Models**: Clean and expressive Eloquent models representing business logic (`Product`, `Order`, `Invoice`, `CreditNote`, etc.).
- **Controllers**: Structured API and web controllers separating administrative management from frontend customer views.
- **Services**: Dedicated service classes (e.g., `InvoicePdfService`) handling complex operations to keep controllers clean.

## 🤝 Contributing

We welcome contributions to improve the Zam Zam Import Export platform!
1. Fork the project.
2. Create your feature branch (`git checkout -b feature/YourFeature`).
3. Commit your changes (`git commit -m 'Add YourFeature'`).
4. Push to the branch (`git push origin feature/YourFeature`).
5. Open a Pull Request.

## 📄 License

This project is licensed under the MIT License.
