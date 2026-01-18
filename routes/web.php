<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoice-pdf', [App\Http\Controllers\InvoiceController::class, 'generatePdf']);
