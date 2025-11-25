<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\Api\DeviceApiController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DeviceManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\KycManagementController;
use App\Http\Controllers\Admin\NotificationManagementController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\SupportController;

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

Broadcast::routes(['middleware' => ['web', 'auth']]);

// Welcome route
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Home page
Route::get('/', function () {
    $currencyService = app(\App\Services\CurrencyService::class);

    // Pricing in USD
    $pricing = [
        'starter' => 49,
        'growth' => 129,
        'devices' => [
            'macbook_pro' => 89,
            'iphone_15_pro' => 45,
            'ipad_pro' => 65,
            'studio_display' => 35
        ],
        'stats' => [
            'revenue' => 50000000, // $50M
        ]
    ];

    // Convert to IDR
    $pricingIdr = [
        'starter' => $currencyService->convertUsdToIdr($pricing['starter']),
        'growth' => $currencyService->convertUsdToIdr($pricing['growth']),
        'devices' => [
            'macbook_pro' => $currencyService->convertUsdToIdr($pricing['devices']['macbook_pro']),
            'iphone_15_pro' => $currencyService->convertUsdToIdr($pricing['devices']['iphone_15_pro']),
            'ipad_pro' => $currencyService->convertUsdToIdr($pricing['devices']['ipad_pro']),
            'studio_display' => $currencyService->convertUsdToIdr($pricing['devices']['studio_display'])
        ],
        'stats' => [
            'revenue' => $currencyService->convertUsdToIdr($pricing['stats']['revenue']),
        ]
    ];

    return view('Home', compact('pricingIdr', 'currencyService'));
})->name('home');

// Support page
Route::get('/support', function () {
    return view('support');
})->name('support');

// Devices routes
Route::get('/devices', [DevicesController::class, 'index'])->name('devices');
Route::get('/api/devices', [DeviceApiController::class, 'search'])->name('api.devices.index');
Route::get('/devices/family/{family}', [DevicesController::class, 'family'])->name('devices.model');
Route::get('/devices/{slug}', [DevicesController::class, 'show'])->name('devices.show');

// Cart (legacy)
Route::get('/cart', [CartController::class,'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class,'add'])->name('cart.add');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Checkout and payment process
    Route::get('/checkout', [RentalController::class, 'recipe'])->name('checkout');
    Route::post('/checkout/confirm', [RentalController::class, 'confirm'])->name('checkout.confirm');
    Route::get('/orders/{order}/receipt', [RentalController::class, 'receipt'])->name('orders.receipt');

    // Payment status pages
    Route::get('/payment/success/{order}', [RentalController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/pending/{order}', [RentalController::class, 'paymentPending'])->name('payment.pending');
    Route::get('/payment/failed/{order}', [RentalController::class, 'paymentFailed'])->name('payment.failed');

    // Delivery tracking
    Route::get('/delivery/track/{order}', [DeliveryController::class, 'track'])->name('delivery.track');
    Route::get('/find-device/{order}', [DeliveryController::class, 'findDevice'])->name('find.device');

    // Rental and order management
    Route::post('/rent', [RentalController::class, 'start'])->name('rent.start');
    Route::get('/orders/{order}', [RentalController::class, 'show'])->name('orders.show');

    // Chat & Pricing
    Route::get('/chat', function () {
        return view('chat-react');
    })->name('chat.index');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])
        ->middleware('throttle:30,1')
        ->name('chat.send');
    Route::get('/api/chat/data', [\App\Http\Controllers\ChatController::class, 'getChatData'])->name('chat.data');
    Route::get('/api/chat/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');

    Route::get('/pricing', function () {
        return view('pricing');
    })->name('pricing');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Support Chat
    Route::get('/support/messages', [SupportController::class, 'getMessages'])->name('support.messages');
    Route::post('/support/messages', [SupportController::class, 'sendMessage'])->name('support.send');
    Route::post('/support/mark-read', [SupportController::class, 'markAsRead'])->name('support.mark-read');
    Route::get('/support/unread-count', [SupportController::class, 'unreadCount'])->name('support.unread-count');

    // Balance
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance');
    Route::get('/balance/transactions', [BalanceController::class, 'transactionHistory'])->name('balance.transactions');
    Route::post('/balance/topup', [BalanceController::class, 'topup'])->name('balance.topup');
    Route::get('/balance/payment-processing', [BalanceController::class, 'paymentProcessing'])->name('balance.payment.processing');
    Route::post('/balance/process-payment/{transaction}', [BalanceController::class, 'processPayment'])->name('balance.process.payment');
    Route::get('/balance/payment-instruction/{transaction}', [BalanceController::class, 'paymentInstruction'])->name('balance.payment-instruction');
    Route::get('/balance/confirm-payment/{transaction}', [BalanceController::class, 'confirmPayment'])->name('balance.confirm-payment');
    Route::get('/balance/payment-success/{transaction}', [BalanceController::class, 'paymentSuccess'])->name('balance.payment.success');
    Route::get('/balance/referral-earnings', [BalanceController::class, 'referralEarnings'])->name('balance.referral.earnings');

    // Settings
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    Route::get('/settings/profile', function () {
        return view('settings.profile');
    })->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');

    Route::get('/settings/security', function () {
        return view('settings.security');
    })->name('settings.security');
    Route::put('/settings/security/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/settings/security/2fa', [SettingsController::class, 'toggle2FA'])->name('settings.2fa.toggle');
    Route::post('/settings/security/logout-device/{deviceId}', [SettingsController::class, 'logoutDevice'])->name('settings.logout.device');
    Route::post('/settings/security/logout-all', [SettingsController::class, 'logoutAllDevices'])->name('settings.logout.all');

    Route::get('/settings/notifications', function () {
        return view('settings.notifications');
    })->name('settings.notifications');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');

    Route::get('/settings/privacy', function () {
        return view('settings.privacy');
    })->name('settings.privacy');
    Route::put('/settings/privacy', [SettingsController::class, 'updatePrivacy'])->name('settings.privacy.update');
    Route::get('/settings/privacy/download-data', [SettingsController::class, 'downloadData'])->name('settings.data.download');
    Route::delete('/settings/delete-account', [SettingsController::class, 'deleteAccount'])->name('settings.account.delete');

    Route::get('/settings/payment', function () {
        return view('settings.payment');
    })->name('settings.payment');
    Route::post('/settings/payment/add', [SettingsController::class, 'addPaymentMethod'])->name('settings.payment.add');
    Route::get('/settings/payment/history', [SettingsController::class, 'paymentHistory'])->name('settings.payment.history');

    Route::get('/settings/subscription', function () {
        return view('settings.subscription');
    })->name('settings.subscription');
    Route::put('/settings/subscription', [SettingsController::class, 'updateSubscription'])->name('settings.subscription.update');
    Route::post('/settings/subscription/cancel', [SettingsController::class, 'cancelSubscription'])->name('settings.subscription.cancel');

    Route::get('/settings/activity-log', [SettingsController::class, 'activityLog'])->name('settings.activity.log');

    // KYC (User)
    Route::get('/kyc/submit', [KycController::class, 'create'])->name('kyc.create');
    Route::post('/kyc/submit', [KycController::class, 'store'])
        ->middleware('throttle:3,60')
        ->name('kyc.store');
    Route::get('/kyc/status', [KycController::class, 'status'])->name('kyc.status');

    // Session validation
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
    Route::patch('/orders/{order}/delivery-status', [OrderManagementController::class, 'updateDeliveryStatus'])->name('orders.updateDeliveryStatus');
    Route::delete('/orders/{order}', [OrderManagementController::class, 'destroy'])->name('orders.destroy');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/orders', [UserManagementController::class, 'getOrders'])->name('users.orders');
    Route::get('/users/{user}/logins', [UserManagementController::class, 'getLogins'])->name('users.logins');
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

    // Notification Management
    Route::get('/notifications', [NotificationManagementController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [NotificationManagementController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationManagementController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/test', [NotificationManagementController::class, 'sendTestNotification'])->name('notifications.test');

    Route::get('/chat', function () {
        return view('admin.chat-react');
    })->name('chat.index');
    Route::post('/chat/send', [AdminChatController::class, 'sendMessage'])
        ->middleware('throttle:30,1')
        ->name('chat.send');
    Route::get('/chat/messages/{userId}', [AdminChatController::class, 'getMessages'])->name('chat.messages');
});

// Admin Chat API Routes (moved to api.php for proper JSON responses)
Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/api/admin/chat/data', [AdminChatController::class, 'getChatData'])->name('admin.api.chat.data');
    Route::get('/api/admin/chat/messages/{userId}', [AdminChatController::class, 'getMessages'])->name('admin.api.chat.messages');
    Route::get('/api/admin/chat/conversation/{userId}', [AdminChatController::class, 'getConversation'])->name('admin.api.chat.conversation');
    Route::post('/api/admin/chat/upload', [AdminChatController::class, 'uploadFile'])
        ->middleware('throttle:10,1')
        ->name('admin.api.chat.upload');
    Route::post('/api/admin/chat/messages/delete', [AdminChatController::class, 'deleteMessages'])->name('admin.api.chat.delete');
    Route::delete('/api/admin/chat/conversation/{userId}', [AdminChatController::class, 'deleteConversation'])->name('admin.api.chat.clear');
    
    // Get all users for new chat modal
    Route::get('/api/admin/users', function () {
        return response()->json(
            \App\Models\User::where('is_admin', false)
                ->select('id', 'name', 'email', 'profile_photo')
                ->orderBy('name')
                ->get()
        );
    })->name('admin.api.users');
});
