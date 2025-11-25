<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Device;
use Illuminate\Auth\Events\Login;

uses(RefreshDatabase::class);

test('guest can add to cart and session stores keyed map', function () {
    // ensure no previous session
    $this->withSession([]);

    // create a variant in the DB so the controller can resolve price & slug
    $device = Device::create([
        'name' => 'iPhone 17 Pro',
        'slug' => 'iphone-17-pro-test-' . uniqid(),
        'family' => 'iPhone 17',
        'price_monthly' => 50,
        'sku' => 'TEST-SKU-' . uniqid(),
    ]);
    $payload = [
        'variant_slug' => $device->slug,
        'months' => 12,
        'capacity' => '256 GB',
        'quantity' => 1,
    ];

    $res = $this->postJson(route('cart.add'), $payload);
    $res->assertStatus(200)->assertJson(['success' => true]);

    // session should now contain a keyed map
    $sessionCart = session('cart.items');
    $this->assertIsArray($sessionCart);
    $this->assertCount(1, $sessionCart);

    $keys = array_keys($sessionCart);
    $this->assertStringContainsString('iphone-17-pro', $keys[0]);
});

test('merge session cart on login merges into database', function () {
    $user = User::factory()->create();

    // create a guest session cart (numeric array)
    $guestCart = [
        [
            'variant_slug' => 'iphone-17-pro',
            'capacity' => '256 GB',
            'months' => 12,
            'quantity' => 1,
            'price_monthly' => 50,
            'total_price' => 600,
        ],
    ];

    $this->withSession(['cart.items' => $guestCart]);

    // fire login event - MergeSessionCart listens to Login
    // Login event expects (guard, user, remember)
    event(new Login('web', $user, false));

    // assert cart item created in DB for the user
    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'variant_slug' => 'iphone-17-pro',
        'capacity' => '256 GB',
    ]);

    // session cart should be cleared
    $this->assertNull(session('cart.items'));
});
