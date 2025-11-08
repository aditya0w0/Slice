<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Device;
use Illuminate\Support\Str;

$slug = 'ipad-air';
echo "=== Testing /devices/family/ipad-air ===\n\n";

$families = Device::selectRaw("COALESCE(family, name) as family")->distinct()->pluck('family')->filter()->values();
$slugToFamily = $families->mapWithKeys(fn($name) => [Str::slug($name) => $name]);

if (!isset($slugToFamily[$slug])) {
    echo "ERROR: Family '$slug' not found\n";
    exit(1);
}

$baseName = $slugToFamily[$slug];
echo "Family: $baseName\n\n";

$devices = Device::where(function ($q) use ($baseName) {
    $q->where('family', $baseName)->orWhere('name', 'like', $baseName . '%');
})->orderBy('name')->get();

echo "Devices found: " . $devices->count() . "\n\n";

foreach ($devices as $device) {
    $variantLabel = 'Base';
    if (! empty($device->family)) {
        $variantLabel = trim(str_ireplace($device->family, '', $device->name));
        $variantLabel = preg_replace('/^[\s\-]+/', '', trim($variantLabel));
        if ($variantLabel === '') $variantLabel = 'Base';
    }
    echo "{$device->name}\n  → '$variantLabel'\n\n";
}
