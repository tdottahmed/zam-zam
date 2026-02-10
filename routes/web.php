<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/{item}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::get('products/next-sku', [\App\Http\Controllers\Admin\ProductController::class, 'nextSku'])->name('products.next-sku');
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

    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);

    // Invoices Resource
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
    
    // Invoice specific routes (Legacy/Specific actions)
    Route::get('orders/{order}/invoice/create', [\App\Http\Controllers\Admin\OrderInvoiceController::class, 'create'])->name('orders.invoice.create');
    Route::post('orders/{order}/invoice/store', [\App\Http\Controllers\Admin\OrderInvoiceController::class, 'store'])->name('orders.invoice.store');
    Route::get('invoices/{invoice}/print', [\App\Http\Controllers\Admin\OrderInvoiceController::class, 'print'])->name('invoices.print');
});

Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::get('/brands', [\App\Http\Controllers\ShopController::class, 'brands'])->name('shop.brands');

require __DIR__.'/auth.php';
