<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\User;

beforeEach(function () {
    Artisan::call('migrate:fresh', ['--seed' => true]);
});

test('user can register and is redirected to user dashboard', function () {
    $email = 'newuser@example.com';

    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', ['email' => $email]);
});
