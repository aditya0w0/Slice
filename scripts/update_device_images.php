<?php

/**
 * Script to update device images in the database
 * Maps generated product photos to appropriate device categories
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Device;
use Illuminate\Support\Facades\DB;

echo "Updating device images...\n";
echo str_repeat("=", 60) . "\n\n";

// Define image mapping - first by category, then refine by family for iPads
$categoryMap = [
    'iPhone' => '/images/devices/iphone.png',
    'Mac' => '/images/devices/macbook.png', // Default for Mac category
    'Apple Watch' => '/images/product-watch.svg',
    'Apple TV' => '/images/product-appletv.svg',
];

$familyMap = [
    // iPad-specific mappings based on family
    'iPad Mini' => '/images/devices/ipad-mini.png',
    'iPad Air' => '/images/devices/ipad-air.png',
    'iPad Pro' => '/images/devices/ipad-pro.png',
    'iPad' => '/images/devices/ipad.png', // Standard iPad
    // Mac-specific mappings
    'iMac' => '/images/devices/imac.png',
    'MacBook Air' => '/images/devices/macbook.png',
];

$updated = 0;
$skipped = 0;

// Step 1: Update all devices by category first (iPhones, Macs, Watches, TVs)
foreach ($categoryMap as $category => $imagePath) {
    $count = Device::where('category', $category)
        ->update(['image' => $imagePath]);
    
    if ($count > 0) {
        echo "✓ Updated {$count} {$category} device(s) by category → {$imagePath}\n";
        $updated += $count;
    }
}

// Step 2: Override with family-specific images for iPads and specific Macs
foreach ($familyMap as $family => $imagePath) {
    $count = Device::where('family', $family)
        ->update(['image' => $imagePath]);
    
    if ($count > 0) {
        echo "✓ Updated {$count} {$family} device(s) by family → {$imagePath}\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Summary:\n";
echo "  • Total devices updated: {$updated}\n";
echo "  • Devices skipped: {$skipped}\n";

// Show current image distribution
echo "\n" . str_repeat("=", 60) . "\n";
echo "Current image distribution:\n";
$distribution = Device::select('image', DB::raw('count(*) as count'))
    ->groupBy('image')
    ->get();

foreach ($distribution as $item) {
    echo "  • {$item->image}: {$item->count} devices\n";
}

echo "\n✅ Image update complete!\n";
