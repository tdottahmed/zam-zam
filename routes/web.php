<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\WelcomeController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\WelcomeController::class, 'contact'])->name('contact');

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'sitemap'])->name('sitemap');
if (Route::hasMacro('feeds')) {
    Route::feeds(); // Spatie Feed Routes
}

Route::get('/dashboard', [\App\Http\Controllers\WelcomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/{item}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
    
    // Credit Note Routes
    Route::get('/orders/{order}/credit-note/create', [\App\Http\Controllers\CreditNoteController::class, 'create'])->name('credit-notes.create');
    Route::post('/orders/{order}/credit-note', [\App\Http\Controllers\CreditNoteController::class, 'store'])->name('credit-notes.store');
    Route::get('/credit-notes/{creditNote}', [\App\Http\Controllers\CreditNoteController::class, 'show'])->name('credit-notes.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Kept existing route
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Address Routes
    Route::resource('addresses', \App\Http\Controllers\UserAddressController::class);
    Route::patch('/addresses/{address}/default', [\App\Http\Controllers\UserAddressController::class, 'setDefault'])->name('addresses.set-default');

    // Wishlist Routes
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('products/next-sku', [\App\Http\Controllers\Admin\ProductController::class, 'nextSku'])->name('products.next-sku');
    Route::post('products/import', [\App\Http\Controllers\Admin\ProductController::class, 'import'])->name('products.import');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('taxes', \App\Http\Controllers\Admin\TaxController::class);
    Route::resource('profit-margins', \App\Http\Controllers\Admin\ProfitMarginController::class); // Cleanup old if exists
    Route::get('profit-margin', [\App\Http\Controllers\Admin\ProfitMarginController::class, 'index'])->name('profit-margin.index');
    Route::put('profit-margin', [\App\Http\Controllers\Admin\ProfitMarginController::class, 'update'])->name('profit-margin.update');
    Route::resource('units', \App\Http\Controllers\Admin\UnitController::class);
    // Search APIs
    Route::get('api/search/users', [\App\Http\Controllers\Admin\OrderController::class, 'searchUsers'])->name('api.search.users');
    Route::get('api/search/products', [\App\Http\Controllers\Admin\OrderController::class, 'searchProducts'])->name('api.search.products');

    // Notifications
    Route::get('/notifications/poll', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.poll');
    Route::post('/notifications/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');

    // Orders
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);

    // Users
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Invoices Resource
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
    
    // Invoice specific routes (Legacy/Specific actions)
    Route::get('orders/{order}/invoice/create', [\App\Http\Controllers\Admin\OrderInvoiceController::class, 'create'])->name('orders.invoice.create');
    Route::post('orders/{order}/invoice/store', [\App\Http\Controllers\Admin\OrderInvoiceController::class, 'store'])->name('orders.invoice.store');
    Route::get('invoices/{invoice}/print', [\App\Http\Controllers\Admin\OrderInvoiceController::class, 'print'])->name('invoices.print');

    // Credit Notes
    Route::resource('credit-notes', \App\Http\Controllers\Admin\CreditNoteController::class);

    // Settings
    Route::controller(\App\Http\Controllers\Admin\SettingsController::class)->prefix('settings')->name('settings.')->group(function () {
        Route::get('general', 'general')->name('general');
        Route::put('general', 'updateGeneral')->name('general.update');
        
        Route::get('smtp', 'smtp')->name('smtp');
        Route::put('smtp', 'updateSmtp')->name('smtp.update');
        Route::post('smtp/test', 'testSmtpConnection')->name('smtp.test');
        
        Route::get('seo', 'seo')->name('seo');
        Route::put('seo', 'updateSeo')->name('seo.update');
        
        Route::get('third-party', 'thirdParty')->name('third-party');
        Route::put('third-party', 'updateThirdParty')->name('third-party.update');

        Route::post('sitemap/generate', [\App\Http\Controllers\SitemapController::class, 'generate'])->name('sitemap.generate');
    });

    Route::resource('shipping-methods', \App\Http\Controllers\Admin\ShippingMethodController::class);
});

Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::get('/brands', [\App\Http\Controllers\ShopController::class, 'brands'])->name('shop.brands');

require __DIR__.'/auth.php';
