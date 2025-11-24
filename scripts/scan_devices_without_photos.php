<?php

/**
 * Script to scan all devices and identify which ones need real product photos
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Device;

echo "Scanning devices for photo status...\n";
echo str_repeat("=", 60) . "\n\n";

// Get all devices
$devices = Device::all();

// Group by image type
$needsPhotos = [];
$hasPhotos = [];

foreach ($devices as $device) {
    $image = $device->image ?? '';
    
    // Check if it's a placeholder SVG or needs a real photo
    if (empty($image) || str_ends_with($image, '.svg')) {
        $needsPhotos[] = $device;
    } else {
        $hasPhotos[] = $device;
    }
}

echo "Total Devices: " . $devices->count() . "\n";
echo "Devices with real photos: " . count($hasPhotos) . "\n";
echo "Devices needing photos: " . count($needsPhotos) . "\n\n";

if (!empty($needsPhotos)) {
    echo str_repeat("=", 60) . "\n";
    echo "DEVICES NEEDING PHOTOS:\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Group by category for easier image generation
    $byCategory = [];
    foreach ($needsPhotos as $device) {
        $category = $device->category ?? 'Unknown';
        if (!isset($byCategory[$category])) {
            $byCategory[$category] = [];
        }
        $byCategory[$category][] = $device;
    }
    
    foreach ($byCategory as $category => $deviceList) {
        echo "\n📱 {$category} ({count($deviceList)} devices):\n";
        echo str_repeat("-", 60) . "\n";
        
        foreach ($deviceList as $device) {
            echo sprintf(
                "  • %-50s [Current: %s]\n",
                $device->name,
                $device->image ?? 'null'
            );
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "\nSummary by Category:\n";
    foreach ($byCategory as $category => $deviceList) {
        echo "  • {$category}: " . count($deviceList) . " devices\n";
    }
}

echo "\n✅ Scan complete!\n";
