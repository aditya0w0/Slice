<?php

use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('parses variant types and generation correctly', function () {
    $cases = [
        ['name' => 'iPhone 12', 'expectedType' => 'base', 'expectedGen' => 12, 'expectedLabel' => 'Base'],
        ['name' => 'iPhone 12 Pro', 'expectedType' => 'pro', 'expectedGen' => 12, 'expectedLabel' => 'Pro'],
        ['name' => 'iPhone 12 Pro Max', 'expectedType' => 'pro max', 'expectedGen' => 12, 'expectedLabel' => 'Pro Max'],
        ['name' => 'iPhone XR', 'expectedType' => 'base', 'expectedGen' => 10, 'expectedLabel' => 'Base'],
        ['name' => 'iPad Mini', 'expectedType' => 'mini', 'expectedGen' => 0, 'expectedLabel' => 'Mini'],
    ];

    foreach ($cases as $c) {
        $d = Device::create([
            'name' => $c['name'],
            'slug' => \Illuminate\Support\Str::slug($c['name']),
            'price_monthly' => 10,
        ]);

        expect($d->variant_type)->toBe($c['expectedType']);
        expect($d->generation)->toBe($c['expectedGen']);
        expect($d->variant_label)->toBe($c['expectedLabel']);
    }
});
