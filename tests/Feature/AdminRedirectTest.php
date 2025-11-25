<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('admin user login redirects to admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->from('/login')->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
});

test('regular user login redirects to user dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));
});
