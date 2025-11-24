<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('user can register and is redirected to user dashboard', function () {
    $email = 'newuser@example.com';

    $response = $this->from('/register')->post('/register', [
        'name' => 'New User',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', ['email' => $email]);
});
