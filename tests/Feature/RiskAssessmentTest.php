<?php

use App\Models\Device;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a device to rent
    $this->device = Device::create([
        'name' => 'iPhone 15 Pro',
        'slug' => 'iphone-15-pro',
        'price_monthly' => 100,
        'sku' => 'TEST-SKU-001',
    ]);
});

test('poor credit user is rejected immediately', function () {
    // Create user with poor credit conditions
    // Fresh account (< 7 days) = -30
    // KYC verified = +100
    // Base 500
    // Total 570 (Poor < 580)
    $user = User::factory()->create([
        'kyc_status' => 'verified',
        'created_at' => now(),
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)
        ->post('/confirm', [
            'device_slug' => $this->device->slug,
            'months' => 12,
            'quantity' => 1,
        ]);

    // Should redirect to payment failed
    $response->assertRedirect();
    $response->assertSessionHas('credit_failure');
    
    // Verify order status is rejected
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'status' => 'rejected',
    ]);
});

test('excellent credit user (850) bypasses checks and succeeds', function () {
    // 850 score users skip the updateCreditScore check and have high trust
    $user = User::factory()->create([
        'credit_score' => 850,
        'credit_tier' => 'excellent',
        'kyc_status' => 'verified',
        'created_at' => now()->subYear(), // Veteran
    ]);

    $response = $this->actingAs($user)
        ->post('/confirm', [
            'device_slug' => $this->device->slug,
            'months' => 12,
            'quantity' => 1,
        ]);

    // Should redirect to payment success
    $response->assertRedirect();
    $response->assertSessionDoesntHaveErrors();
    
    // Check for success redirect pattern (payment.success route)
    $order = Order::where('user_id', $user->id)->first();
    $this->assertNotNull($order);
    $response->assertRedirect(route('payment.success', $order->id));
    
    expect($order->status)->toBe('paid');
});

test('fair credit user passes initial check but faces risk assessment', function () {
    // Construct a Fair credit user (580-669)
    // Base 500
    // KYC verified +100
    // 1 month old +20
    // Total 620 (Fair)
    $user = User::factory()->create([
        'kyc_status' => 'verified',
        'created_at' => now()->subDays(35),
        'email_verified_at' => null,
    ]);

    // Normal order should pass for Fair user if no other risks
    $response = $this->actingAs($user)
        ->post('/confirm', [
            'device_slug' => $this->device->slug,
            'months' => 12,
            'quantity' => 1,
        ]);

    $order = Order::where('user_id', $user->id)->first();
    $response->assertRedirect(route('payment.success', $order->id));
});

test('rapid fire orders trigger risk flag', function () {
    // User with Good credit (e.g. 700)
    // Base 500 + 100 (KYC) + 80 (1 year) + 30 (Email) = 710 (Good)
    $user = User::factory()->create([
        'kyc_status' => 'verified',
        'created_at' => now()->subYear(),
        'email_verified_at' => now(),
    ]);

    // Create 3 recent orders to trigger "Rapid Fire" (> 3 in 1 hour)
    Order::factory()->count(3)->create([
        'user_id' => $user->id,
        'created_at' => now()->subMinutes(10),
        'status' => 'paid', // Successful past orders
    ]);

    $response = $this->actingAs($user)
        ->post('/confirm', [
            'device_slug' => $this->device->slug,
            'months' => 12,
            'quantity' => 1,
        ]);

    // 3 orders in 1 hour = +50 risk
    // Good credit bonus = -20
    // Net +30 risk -> Moderate Risk -> Pending Review
    
    $order = Order::where('user_id', $user->id)->latest()->first();
    $response->assertRedirect(route('payment.pending', $order->id));
    expect($order->status)->toBe('pending_review');
});

test('high value order triggers manual review for non-excellent users', function () {
    // User with Good credit
    $user = User::factory()->create([
        'kyc_status' => 'verified',
        'created_at' => now()->subYear(),
        'email_verified_at' => now(),
    ]);

    // High value order: 100 * 12 * 50 = 60,000 (Very High)
    // > 10M is +35 risk
    // Good credit -20
    // Net +15 -> Low Risk (Tier 4)
    // But Tier 4 says: "First-time high-value orders need review"
    // This user has 0 successful orders in this test setup
    
    $response = $this->actingAs($user)
        ->post('/confirm', [
            'device_slug' => $this->device->slug,
            'months' => 12,
            'quantity' => 50, // High quantity to boost price
        ]);

    $order = Order::where('user_id', $user->id)->first();
    
    // Should be pending because it's a first-time high-value order
    $response->assertRedirect(route('payment.pending', $order->id));
});
