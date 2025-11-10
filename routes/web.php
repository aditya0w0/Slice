<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
});

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
// Use the dedicated `family` action so it can render a family-level view (devices.family)
Route::get('/devices/family/{family}', [DevicesController::class, 'family'])->name('devices.model');

// Device detail by slug (e.g. /devices/ipad-9th-generation-2021) — single-device page
Route::get('/devices/{slug}', [DevicesController::class, 'show'])->name('devices.show');

// Authentication (simple custom pages)
use App\Http\Controllers\AuthController;
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User dashboard (end-user, not admin)
use App\Http\Controllers\DashboardController;
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Admin dashboard
use App\Http\Controllers\Admin\AdminDashboardController;
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('auth')->name('admin.dashboard');

// Debug endpoints removed — temporary inspection routes have been cleaned up.
