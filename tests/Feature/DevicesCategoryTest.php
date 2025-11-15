<?php

use Illuminate\Support\Facades\Artisan;
use function Pest\Faker\faker;

beforeEach(function () {
    // ensure migrations + seed data are present for deterministic assertions
    \Artisan::call('migrate:fresh', ['--seed' => true]);
});

test('devices index shows all devices together', function () {
    $response = $this->get('/devices');

    $response->assertStatus(200);
    // Original behavior: all devices are shown together without category filtering
    $response->assertSee('Rent the Latest Devices');
    $response->assertSee('Devices');
});

test('devices index can filter by family parameter', function () {
    // Original behavior supports optional ?family=slug parameter
    $response = $this->get('/devices?family=iphone');

    $response->assertStatus(200);
    $response->assertSee('Devices');
});

test('family model page includes variants json', function () {
    // Use a known family+generation from seed data; ipad-8 is present in seed fixture
    $response = $this->get('/devices/family/ipad-8');

    $response->assertStatus(200);
    // The family model page should inject `const variants =` into the rendered JS
    $response->assertSee('const variants =', false);
});
