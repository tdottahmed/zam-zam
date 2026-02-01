<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('taxes', \App\Http\Controllers\Admin\TaxController::class);
    Route::resource('profit-margins', \App\Http\Controllers\Admin\ProfitMarginController::class); // Cleanup old if exists
    Route::get('profit-margin', [\App\Http\Controllers\Admin\ProfitMarginController::class, 'index'])->name('profit-margin.index');
    Route::put('profit-margin', [\App\Http\Controllers\Admin\ProfitMarginController::class, 'update'])->name('profit-margin.update');
    Route::resource('units', \App\Http\Controllers\Admin\UnitController::class);
});

require __DIR__.'/auth.php';
