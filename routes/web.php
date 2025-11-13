<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DeviceManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\KycManagementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\User\KycController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Welcome route
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Home page
Route::get('/', function () {
    return view('Home');
})->name('home');

// Support page
Route::get('/support', function () {
    return view('support');
})->name('support');

// Devices routes
Route::get('/devices', [DevicesController::class, 'index'])->name('devices');
Route::get('/devices/family/{family}', [DevicesController::class, 'family'])->name('devices.model');
Route::get('/devices/{slug}', [DevicesController::class, 'show'])->name('devices.show');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Checkout and payment process
    Route::get('/checkout', [RentalController::class, 'recipe'])->name('checkout');
    Route::post('/checkout/confirm', [RentalController::class, 'confirm'])->name('checkout.confirm');

    // Payment status pages
    Route::get('/payment/success/{order}', [RentalController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/pending/{order}', [RentalController::class, 'paymentPending'])->name('payment.pending');
    Route::get('/payment/failed/{order}', [RentalController::class, 'paymentFailed'])->name('payment.failed');

    // Delivery tracking
    Route::get('/delivery/track/{order}', [DeliveryController::class, 'track'])->name('delivery.track');

    // Rental and order management
    Route::post('/rent', [RentalController::class, 'start'])->name('rent.start');
    Route::get('/orders/{order}', [RentalController::class, 'show'])->name('orders.show');

    // User dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // KYC routes
    Route::get('/kyc/submit', [KycController::class, 'create'])->name('kyc.create');
    Route::post('/kyc/submit', [KycController::class, 'store'])->name('kyc.store');
    Route::get('/kyc/status', [KycController::class, 'status'])->name('kyc.status');

    // Session validation API endpoint
    Route::get('/api/session/validate', function () {
        if (Auth::check()) {
            return response()->json(['valid' => true], 200);
        }
        return response()->json(['valid' => false], 401);
    });
});

// Admin routes
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

    // Credit Score and KYC Management
    Route::get('/credit-scores', [AdminController::class, 'users'])->name('credit-scores');
    Route::post('/users/{user}/blacklist', [AdminController::class, 'blacklistUser'])->name('users.blacklist');
    Route::post('/users/{user}/unblacklist', [AdminController::class, 'unblacklistUser'])->name('users.unblacklist');
    Route::post('/users/{user}/kyc/approve', [AdminController::class, 'approveKyc'])->name('kyc.approve-user');
    Route::post('/users/{user}/kyc/reject', [AdminController::class, 'rejectKyc'])->name('kyc.reject-user');

    // KYC Management
    Route::get('/kyc', [KycManagementController::class, 'index'])->name('kyc.index');
    Route::get('/kyc/{kyc}', [KycManagementController::class, 'show'])->name('kyc.show');
    Route::patch('/kyc/{kyc}/approve', [KycManagementController::class, 'approve'])->name('kyc.approve');
    Route::patch('/kyc/{kyc}/reject', [KycManagementController::class, 'reject'])->name('kyc.reject');
});

// Legacy cart routes (kept for reference)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
