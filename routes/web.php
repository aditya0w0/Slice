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

// Admin routes - protected by admin middleware
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DeviceManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\KycManagementController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Device Management
    Route::resource('devices', DeviceManagementController::class);
    
    // Order Management
    Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}', [OrderManagementController::class, 'destroy'])->name('orders.destroy');
    
    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/toggle-admin', [UserManagementController::class, 'toggleAdmin'])->name('users.toggleAdmin');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // KYC Management
    Route::get('/kyc', [KycManagementController::class, 'index'])->name('kyc.index');
    Route::get('/kyc/{kyc}', [KycManagementController::class, 'show'])->name('kyc.show');
    Route::patch('/kyc/{kyc}/approve', [KycManagementController::class, 'approve'])->name('kyc.approve');
    Route::patch('/kyc/{kyc}/reject', [KycManagementController::class, 'reject'])->name('kyc.reject');
});

// User KYC routes
use App\Http\Controllers\User\KycController;
Route::middleware('auth')->group(function () {
    Route::get('/kyc/submit', [KycController::class, 'create'])->name('kyc.create');
    Route::post('/kyc/submit', [KycController::class, 'store'])->name('kyc.store');
    Route::get('/kyc/status', [KycController::class, 'status'])->name('kyc.status');
});

// Debug endpoints removed — temporary inspection routes have been cleaned up.
