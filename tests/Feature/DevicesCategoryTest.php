<?php

use Illuminate\Support\Facades\Artisan;
use function Pest\Faker\faker;

beforeEach(function () {
    // ensure migrations + seed data are present for deterministic assertions
    \Artisan::call('migrate:fresh', ['--seed' => true]);
});

test('devices index is category exclusive for iphone', function () {
    $response = $this->get('/devices?category=iphone');

    $response->assertStatus(200);
    // When viewing the iPhone category, there should be no links to iPad family pages
    $response->assertDontSee('/devices/family/ipad-');
    $response->assertSee('/devices/family/iphone-');
});

test('devices index is category exclusive for ipad', function () {
    $response = $this->get('/devices?category=ipad');

    $response->assertStatus(200);
    // When viewing the iPad category, there should be no links to iPhone family pages
    $response->assertDontSee('/devices/family/iphone-');
    $response->assertSee('/devices/family/ipad-');
});

test('family model page includes variants json', function () {
    // Use a known family+generation from seed data; ipad-8 is present in seed fixture
    $response = $this->get('/devices/family/ipad-8');

    $response->assertStatus(200);
    // The family model page should inject `const variants =` into the rendered JS
    $response->assertSee('const variants =', false);
});
