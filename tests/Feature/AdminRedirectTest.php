<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\User;

beforeEach(function () {
    // Fresh database for deterministic behavior
    Artisan::call('migrate:fresh', ['--seed' => true]);
});

test('admin user login redirects to admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
});

test('regular user login redirects to user dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
});
