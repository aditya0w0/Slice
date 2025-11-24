<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $device = \App\Models\Device::where('name', 'like', '%xc%')->first();
    if ($device) {
        echo "Found: {$device->name} (ID: {$device->id})\n";
        $device->delete();
        echo "Successfully deleted!\n";
    } else {
        echo "Device not found\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
