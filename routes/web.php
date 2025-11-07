<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\navBar;

Route::get('/', [navBar::class, 'getNavbar']);

// Devices routes (dynamic)
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CartController;
Route::post('/rent', [RentalController::class,'start'])->middleware('auth')->name('rent.start');
Route::get('/orders/{order}', [RentalController::class,'show'])->middleware('auth')->name('orders.show');

// Cart and checkout
// Cart pages and add-to-cart are available to guests (stored in session). Checkout completion still requires authentication.
Route::get('/cart', [CartController::class,'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class,'add'])->name('cart.add');
Route::get('/checkout', [CartController::class,'checkout'])->name('checkout.index');
Route::post('/checkout/complete', [CartController::class,'complete'])->middleware('auth')->name('checkout.complete');


Route::get('/devices', [DevicesController::class, 'index'])->name('devices');
// Family page (kept for compatibility) — e.g. /devices/family/ipad shows variants for the family
Route::get('/devices/family/{family}', [DevicesController::class, 'model'])->name('devices.model');

// Device detail by slug (e.g. /devices/ipad-9th-generation-2021) — single-device page
Route::get('/devices/{slug}', [DevicesController::class, 'show'])->name('devices.show');
