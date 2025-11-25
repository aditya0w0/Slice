<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Starting image deployment...\n\n";

// Define image mappings: source => destination
$images = [
    'C:/Users/Xiao Fan/.gemini/antigravity/brain/d945feb3-0235-446c-8191-f817fadf7fc4/apple_tv_4k_1764057557112.png' => 'public/images/devices/apple-tv-4k.png',
    'C:/Users/Xiao Fan/.gemini/antigravity/brain/d945feb3-0235-446c-8191-f817fadf7fc4/apple_homepod_1764057574753.png' => 'public/images/devices/homepod.png',
    'C:/Users/Xiao Fan/.gemini/antigravity/brain/d945feb3-0235-446c-8191-f817fadf7fc4/magic_keyboard_1764057590334.png' => 'public/images/devices/magic-keyboard.png',
    'C:/Users/Xiao Fan/.gemini/antigravity/brain/d945feb3-0235-446c-8191-f817fadf7fc4/magic_mouse_1764057607631.png' => 'public/images/devices/magic-mouse.png',
    'C:/Users/Xiao Fan/.gemini/antigravity/brain/d945feb3-0235-446c-8191-f817fadf7fc4/vision_pro_1764057638066.png' => 'public/images/devices/vision-pro.png',
    'C:/Users/Xiao Fan/.gemini/antigravity/brain/d945feb3-0235-446c-8191-f817fadf7fc4/airpods_pro_1764057654542.png' => 'public/images/devices/airpods-pro.png',
];

// Copy images
echo "Copying images to public/images/devices...\n";
foreach ($images as $source => $destination) {
    if (file_exists($source)) {
        $destPath = __DIR__ . '/' . $destination;
        $destDir = dirname($destPath);
        
        // Create directory if it doesn't exist
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        if (copy($source, $destPath)) {
            echo "✓ Copied: " . basename($destination) . "\n";
        } else {
            echo "✗ Failed to copy: " . basename($destination) . "\n";
        }
    } else {
        echo "✗ Source not found: " . basename($source) . "\n";
    }
}

echo "\nUpdating device records in database...\n\n";

// Update existing devices
\App\Models\Device::where('name', 'LIKE', '%iPhone 15%')->update(['image' => '/images/devices/iphone-15-pro-max.png']);
\App\Models\Device::where('name', 'LIKE', '%iPhone 14%')->update(['image' => '/images/devices/iphone-14-pro.png']);
\App\Models\Device::where('name', 'LIKE', '%iPhone 13%')->update(['image' => '/images/devices/iphone-13.png']);
\App\Models\Device::where('name', 'LIKE', '%iPhone 12%')->update(['image' => '/images/devices/iphone-13.png']);
\App\Models\Device::where('name', 'LIKE', '%iPhone 11%')->update(['image' => '/images/devices/iphone-13.png']);
\App\Models\Device::where('name', 'LIKE', '%iPhone X%')->update(['image' => '/images/devices/iphone-13.png']);

// Update iPads
\App\Models\Device::where('name', 'LIKE', '%iPad%')->update(['image' => '/images/devices/ipad-pro-12.png']);

// Update Macs
\App\Models\Device::where('name', 'LIKE', '%MacBook%')->update(['image' => '/images/devices/macbook-pro-16.png']);
\App\Models\Device::where('name', 'LIKE', '%iMac%')->update(['image' => '/images/devices/macbook-pro-16.png']);
\App\Models\Device::where('name', 'LIKE', '%Mac%')->update(['image' => '/images/devices/macbook-pro-16.png']);

// Update new devices with generated images
$updated = \App\Models\Device::where('name', 'LIKE', '%Apple TV%')->update(['image' => '/images/devices/apple-tv-4k.png']);
echo "✓ Updated $updated Apple TV devices\n";

$updated = \App\Models\Device::where('name', 'LIKE', '%HomePod%')->update(['image' => '/images/devices/homepod.png']);
echo "✓ Updated $updated HomePod devices\n";

$updated = \App\Models\Device::where('name', 'LIKE', '%Magic Keyboard%')->update(['image' => '/images/devices/magic-keyboard.png']);
echo "✓ Updated $updated Magic Keyboard devices\n";

$updated = \App\Models\Device::where('name', 'LIKE', '%Magic Mouse%')->update(['image' => '/images/devices/magic-mouse.png']);
echo "✓ Updated $updated Magic Mouse devices\n";

$updated = \App\Models\Device::where('name', 'LIKE', '%Vision Pro%')->update(['image' => '/images/devices/vision-pro.png']);
echo "✓ Updated $updated Vision Pro devices\n";

$updated = \App\Models\Device::where('name', 'LIKE', '%AirPods%')->update(['image' => '/images/devices/airpods-pro.png']);
echo "✓ Updated $updated AirPods devices\n";

echo "\n✅ All devices updated with new images!\n";
echo "\nNote: Apple Pencil image was not generated due to quota limits.\n";
echo "You can generate it later and add manually.\n";
