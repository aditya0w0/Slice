<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
{
    // Constants for better maintainability
    private const IPHONE_SUFFIXES = ['pro', 'max', 'mini', 'plus', 'e'];
    private const HOMEPOD_COLORS = ['Midnight', 'White', 'Orange', 'Blue', 'Yellow', 'Space Gray'];
    private const AIRPODS_COLORS = ['Midnight', 'Space Gray', 'Silver', 'Pink', 'Blue', 'Green', 'Purple'];

    public function run()
    {
        // MASTER DATASET (Raw Data dari Apple)
        // Format: [Nama Produk, Kategori, Harga Bulanan]
        // URUTAN: Legacy -> Newest (M1 -> M4)
        $rawProducts = [
            // --- IPHONE (Series Separation) ---
            ['iPhone XS', 'iPhone', 35],
            ['iPhone XS Max', 'iPhone', 45],
            ['iPhone XR', 'iPhone', 35],
            ['iPhone XR Max', 'iPhone', 45],
            ['iPhone 11', 'iPhone', 35],
            ['iPhone 11 Pro', 'iPhone', 45],
            ['iPhone 11 Pro Max', 'iPhone', 50],
            ['iPhone 12', 'iPhone', 35],
            ['iPhone 12 mini', 'iPhone', 30],
            ['iPhone 12 Pro', 'iPhone', 45],
            ['iPhone 12 Pro Max', 'iPhone', 50],
            ['iPhone 13', 'iPhone', 35],
            ['iPhone 13 mini', 'iPhone', 30],
            ['iPhone 13 Pro', 'iPhone', 45],
            ['iPhone 13 Pro Max', 'iPhone', 50],
            ['iPhone 14', 'iPhone', 35],
            ['iPhone 14 mini', 'iPhone', 30],
            ['iPhone 14 Pro', 'iPhone', 45],
            ['iPhone 14 Pro Max', 'iPhone', 50],
            ['iPhone 15', 'iPhone', 55],
            ['iPhone 15 Plus', 'iPhone', 65],
            ['iPhone 15 Pro', 'iPhone', 75],
            ['iPhone 15 Pro Max', 'iPhone', 85],
            // Series 16
            ['iPhone 16', 'iPhone', 70],
            ['iPhone 16 Plus', 'iPhone', 80],
            ['iPhone 16e', 'iPhone', 50], // The "e" model
            ['iPhone 16 Pro', 'iPhone', 90],
            ['iPhone 16 Pro Max', 'iPhone', 100],

            // --- IPAD (Sorted by Chip: M1 -> M2 -> M3 -> M4) ---

            // iPad Pro M1 (Legacy but gold)
            ['iPad Pro 11-inch (M1)', 'iPad', 65],
            ['iPad Pro 12.9-inch (M1)', 'iPad', 80],

            // iPad Pro M2
            ['iPad Pro 11-inch (M2)', 'iPad', 75],
            ['iPad Pro 12.9-inch (M2)', 'iPad', 90],

            // iPad Pro M4 (New Tandem OLED)
            ['iPad Pro 11-inch (M4)', 'iPad', 85],
            ['iPad Pro 13-inch (M4)', 'iPad', 100],

            // iPad Air M1
            ['iPad Air 10.9-inch (M1)', 'iPad', 55],

            // iPad Air M2
            ['iPad Air 11-inch (M2)', 'iPad', 60],
            ['iPad Air 13-inch (M2)', 'iPad', 70],

            // iPad Base & Mini
            ['iPad (9th generation)', 'iPad', 25],
            ['iPad (10th generation)', 'iPad', 30],
            ['iPad (11th generation)', 'iPad', 35],
            ['iPad mini (6th generation)', 'iPad', 40],
            ['iPad mini (7th generation)', 'iPad', 45],

            // --- MACBOOK (Sorted by Chip: M1 -> M2 -> M3 -> M4) ---
            // Enterprise SaaS: RAM variants are critical for business workloads

            // M1 Era
            ['MacBook Air 13-inch (M1) 8GB', 'Mac', 80],
            ['MacBook Air 13-inch (M1) 16GB', 'Mac', 85],
            ['MacBook Pro 13-inch (M1) 8GB', 'Mac', 95],
            ['MacBook Pro 13-inch (M1) 16GB', 'Mac', 100],
            ['MacBook Pro 14-inch (M1 Pro) 16GB', 'Mac', 140],
            ['MacBook Pro 14-inch (M1 Pro) 32GB', 'Mac', 150],
            ['MacBook Pro 16-inch (M1 Max) 32GB', 'Mac', 170],
            ['MacBook Pro 16-inch (M1 Max) 64GB', 'Mac', 185],

            // M2 Era
            ['MacBook Air 13-inch (M2) 8GB', 'Mac', 90],
            ['MacBook Air 13-inch (M2) 16GB', 'Mac', 95],
            ['MacBook Air 13-inch (M2) 24GB', 'Mac', 100],
            ['MacBook Air 15-inch (M2) 8GB', 'Mac', 100],
            ['MacBook Air 15-inch (M2) 16GB', 'Mac', 105],
            ['MacBook Air 15-inch (M2) 24GB', 'Mac', 110],
            ['MacBook Pro 13-inch (M2) 8GB', 'Mac', 105],
            ['MacBook Pro 13-inch (M2) 16GB', 'Mac', 110],
            ['MacBook Pro 13-inch (M2) 24GB', 'Mac', 115],
            ['MacBook Pro 14-inch (M2 Pro) 16GB', 'Mac', 160],
            ['MacBook Pro 14-inch (M2 Pro) 32GB', 'Mac', 170],
            ['MacBook Pro 16-inch (M2 Max) 32GB', 'Mac', 190],
            ['MacBook Pro 16-inch (M2 Max) 64GB', 'Mac', 205],
            ['MacBook Pro 16-inch (M2 Max) 96GB', 'Mac', 220],

            // M3 Era
            ['MacBook Air 13-inch (M3) 8GB', 'Mac', 110],
            ['MacBook Air 13-inch (M3) 16GB', 'Mac', 115],
            ['MacBook Air 13-inch (M3) 24GB', 'Mac', 120],
            ['MacBook Air 15-inch (M3) 8GB', 'Mac', 120],
            ['MacBook Air 15-inch (M3) 16GB', 'Mac', 125],
            ['MacBook Air 15-inch (M3) 24GB', 'Mac', 130],
            ['MacBook Pro 14-inch (M3) 8GB', 'Mac', 180],
            ['MacBook Pro 14-inch (M3) 16GB', 'Mac', 185],
            ['MacBook Pro 14-inch (M3 Pro) 18GB', 'Mac', 200],
            ['MacBook Pro 14-inch (M3 Pro) 36GB', 'Mac', 210],
            ['MacBook Pro 16-inch (M3 Pro) 18GB', 'Mac', 220],
            ['MacBook Pro 16-inch (M3 Pro) 36GB', 'Mac', 230],
            ['MacBook Pro 16-inch (M3 Max) 36GB', 'Mac', 250],
            ['MacBook Pro 16-inch (M3 Max) 48GB', 'Mac', 260],
            ['MacBook Pro 16-inch (M3 Max) 128GB', 'Mac', 280],

            // M4 Era (Latest - Enterprise Premium)
            ['MacBook Pro 14-inch (M4) 16GB', 'Mac', 200],
            ['MacBook Pro 14-inch (M4) 24GB', 'Mac', 210],
            ['MacBook Pro 14-inch (M4 Pro) 24GB', 'Mac', 220],
            ['MacBook Pro 14-inch (M4 Pro) 48GB', 'Mac', 235],
            ['MacBook Pro 14-inch (M4 Max) 36GB', 'Mac', 260],
            ['MacBook Pro 14-inch (M4 Max) 64GB', 'Mac', 275],
            ['MacBook Pro 14-inch (M4 Max) 128GB', 'Mac', 295],
            ['MacBook Pro 16-inch (M4 Pro) 24GB', 'Mac', 240],
            ['MacBook Pro 16-inch (M4 Pro) 48GB', 'Mac', 255],
            ['MacBook Pro 16-inch (M4 Max) 36GB', 'Mac', 280],
            ['MacBook Pro 16-inch (M4 Max) 64GB', 'Mac', 295],
            ['MacBook Pro 16-inch (M4 Max) 128GB', 'Mac', 320],

            // --- MAC DESKTOPS (Enterprise Workstations) ---

            // iMac
            ['iMac 24-inch (M1) 8GB', 'Mac', 110],
            ['iMac 24-inch (M1) 16GB', 'Mac', 115],
            ['iMac 24-inch (M3) 8GB', 'Mac', 130],
            ['iMac 24-inch (M3) 16GB', 'Mac', 135],
            ['iMac 24-inch (M3) 24GB', 'Mac', 140],
            ['iMac 24-inch (M4) 16GB', 'Mac', 145],
            ['iMac 24-inch (M4) 24GB', 'Mac', 150],
            ['iMac 24-inch (M4) 32GB', 'Mac', 155],

            // Mac mini
            ['Mac mini (M2) 8GB', 'Mac', 50],
            ['Mac mini (M2) 16GB', 'Mac', 55],
            ['Mac mini (M2) 24GB', 'Mac', 60],
            ['Mac mini (M2 Pro) 16GB', 'Mac', 75],
            ['Mac mini (M2 Pro) 32GB', 'Mac', 85],
            ['Mac mini (M4) 16GB', 'Mac', 60],
            ['Mac mini (M4) 24GB', 'Mac', 65],
            ['Mac mini (M4) 32GB', 'Mac', 70],
            ['Mac mini (M4 Pro) 24GB', 'Mac', 85],
            ['Mac mini (M4 Pro) 48GB', 'Mac', 95],
            ['Mac mini (M4 Pro) 64GB', 'Mac', 105],

            // Mac Studio - High-Performance Workstations
            ['Mac Studio (M1 Max) 32GB', 'Mac', 180],
            ['Mac Studio (M1 Max) 64GB', 'Mac', 195],
            ['Mac Studio (M1 Ultra) 64GB', 'Mac', 240],
            ['Mac Studio (M1 Ultra) 128GB', 'Mac', 260],
            ['Mac Studio (M2 Max) 32GB', 'Mac', 200],
            ['Mac Studio (M2 Max) 64GB', 'Mac', 215],
            ['Mac Studio (M2 Max) 96GB', 'Mac', 230],
            ['Mac Studio (M2 Ultra) 64GB', 'Mac', 260],
            ['Mac Studio (M2 Ultra) 128GB', 'Mac', 280],
            ['Mac Studio (M2 Ultra) 192GB', 'Mac', 300],

            // Mac Pro - Enterprise Maximum Performance
            ['Mac Pro (M2 Ultra) 64GB', 'Mac', 500],
            ['Mac Pro (M2 Ultra) 128GB', 'Mac', 530],
            ['Mac Pro (M2 Ultra) 192GB', 'Mac', 560],


            // --- APPLE TV (Resolution Separation) ---
            ['Apple TV 4K (3rd generation)', 'Apple TV', 15],
            ['Apple TV 4K (3rd generation) Wi-Fi + Ethernet', 'Apple TV', 18],
            ['Apple TV 4K (2nd generation)', 'Apple TV', 12],
            ['Apple TV HD', 'Apple TV', 8],

            // --- AUDIO (Type Separation) ---
            ['AirPods (3rd generation)', 'Audio', 12],
            ['AirPods (4th generation)', 'Audio', 15],
            ['AirPods Pro (2nd generation)', 'Audio', 25],
            ['AirPods Pro (2nd generation) MagSafe', 'Audio', 28],
            ['AirPods Max', 'Audio', 35],
            ['AirPods Max Midnight', 'Audio', 35],
            ['AirPods Max Space Gray', 'Audio', 35],
            ['AirPods Max Silver', 'Audio', 35],
            ['AirPods Max Pink', 'Audio', 35],
            ['AirPods Max Blue', 'Audio', 35],
            ['AirPods Max Green', 'Audio', 35],
            ['AirPods Max Purple', 'Audio', 35],

            // --- HOMEPOD (Size Separation) ---
            ['HomePod (2nd generation)', 'Home Accessory', 18],
            ['HomePod mini', 'Home Accessory', 9],
            ['HomePod mini Midnight', 'Home Accessory', 9],
            ['HomePod mini White', 'Home Accessory', 9],
            ['HomePod mini Orange', 'Home Accessory', 9],
            ['HomePod mini Blue', 'Home Accessory', 9],
            ['HomePod mini Yellow', 'Home Accessory', 9],

            // --- WATCH (Series Separation) ---
            ['Apple Watch Series 9 41mm', 'Apple Watch', 30],
            ['Apple Watch Series 9 45mm', 'Apple Watch', 32],
            ['Apple Watch Series 10 42mm', 'Apple Watch', 35],
            ['Apple Watch Series 10 46mm', 'Apple Watch', 37],
            ['Apple Watch Ultra 2', 'Apple Watch', 55],
            ['Apple Watch Ultra 3', 'Apple Watch', 60],
            ['Apple Watch SE (2nd Gen) 40mm', 'Apple Watch', 20],
            ['Apple Watch SE (2nd Gen) 44mm', 'Apple Watch', 22],
            ['Apple Watch SE (3rd Gen) 41mm', 'Apple Watch', 25],
            ['Apple Watch SE (3rd Gen) 45mm', 'Apple Watch', 27],

            // --- VISION PRO ---
            ['Apple Vision Pro 256GB', 'Vision Pro', 150],
            ['Apple Vision Pro 512GB', 'Vision Pro', 170],
            ['Apple Vision Pro 1TB', 'Vision Pro', 190],

            // --- ACCESSORIES ---
            ['Apple Pencil (2nd generation)', 'Accessory', 12],
            ['Apple Pencil Pro', 'Accessory', 15],
            ['Magic Keyboard for iPad Pro 11-inch', 'Accessory', 25],
            ['Magic Keyboard for iPad Pro 13-inch', 'Accessory', 30],
            ['Magic Keyboard for iPad Pro 15-inch', 'Accessory', 35],
            ['Smart Keyboard Folio for iPad Air/Pro', 'Accessory', 18],
        ];

        // Validate data before processing
        $this->validateProductData($rawProducts);

        $rows = [];
        foreach ($rawProducts as $item) {
            $name = $item[0];
            $cat  = $item[1];
            $price= $item[2];

            $rows[] = [
                'name'          => $name,
                'slug'          => Str::slug($name),
                'family'        => $this->getAggressiveFamily($name, $cat),
                'category'      => $cat,
                'variant'       => $this->getAggressiveVariant($name, $cat),
                'price_monthly' => $price,
                'image'         => '/images/product-'.Str::slug($cat).'.svg',
                'description'   => "Rental plan for $name",
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        try {
            DB::table('devices')->upsert(
                $rows,
                ['slug'],
                ['name', 'family', 'category', 'variant', 'price_monthly', 'updated_at']
            );
        } catch (\Exception $e) {
            Log::error('Device seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function validateProductData(array $products): void
    {
        $categories = [
            'iPhone', 'iPad', 'Mac', 'Apple Watch', 'Apple TV',
            'Audio', 'Home Accessory', 'Vision Pro', 'Accessory'
        ];

        foreach ($products as $index => $product) {
            if (!is_array($product) || count($product) !== 3) {
                throw new \InvalidArgumentException("Product at index {$index} must be a 3-element array");
            }
            [$name, $category, $price] = $product;
            if (!in_array($category, $categories)) {
                throw new \InvalidArgumentException("Invalid category '{$category}' for product '{$name}'");
            }
        }
    }

    private function getAggressiveFamily($name, $category)
    {
        $clean = $name;

        // GLOBAL CLEANUP: Remove generation info EXCEPT for iPad Base Model
        if ($category === 'iPad' && Str::startsWith($name, 'iPad (')) {
            return preg_replace('/generation\)/', 'Gen)', $name);
        } else {
             $clean = preg_replace('/\s\((?:\d{1,2}(?:st|nd|rd|th) )?(?:Gen|generation|gen)\)/i', '', $clean);
        }

        switch ($category) {
            case 'iPhone':
                $parts = explode(' ', $clean);
                $final = [];
                foreach ($parts as $p) {
                    if (!in_array(strtolower($p), self::IPHONE_SUFFIXES)) {
                        $final[] = $p;
                    }
                }
                $str = implode(' ', $final);
                return preg_replace('/(\d+)[a-zA-Z]+/', '$1', $str);

            case 'iPad':
            case 'Mac':
                $cleaned = trim(preg_replace('/\s\d+(\.\d)?-inch/', '', $clean));
                // Remove RAM suffix for Mac (e.g., "16GB", "32GB")
                if ($category === 'Mac') {
                    $cleaned = trim(preg_replace('/\s\d+GB$/', '', $cleaned));
                }
                return $cleaned;

            case 'Apple Watch':
                // --- FIX URUTAN: Prioritaskan Series & Ultra SEBELUM SE ---

                // 1. ULTRA: Biarin utuh "Apple Watch Ultra 2"
                if (stripos($clean, 'Ultra') !== false) {
                    return $clean;
                }

                // 2. SERIES: Cek eksplisit kata "Series".
                // Kalo ketemu, langsung return Family Series (hapus ukuran mm).
                // Ini ngejamin "Series 9" GAK BAKAL masuk logic SE di bawah.
                if (stripos($clean, 'Series') !== false) {
                    return trim(preg_replace('/\s\d{2}mm/', '', $clean));
                }

                // 3. SE: Fallback terakhir buat SE.
                // Pakai Word Boundary biar makin aman.
                if (preg_match('/\bSE\b/i', $clean)) {
                    return 'Apple Watch SE';
                }

                // 4. Fallback lain (jarang terjadi kalo datanya bener)
                return trim(preg_replace('/\s\d{2}mm/', '', $clean));

            case 'Apple TV':
                if (stripos($clean, '4K') !== false) return 'Apple TV 4K';
                if (stripos($clean, 'HD') !== false) return 'Apple TV HD';
                return 'Apple TV';

            case 'Home Accessory':
                if (stripos($clean, 'mini') !== false) return 'HomePod mini';
                return 'HomePod';

            case 'Audio':
                if (stripos($clean, 'Max') !== false) return 'AirPods Max';
                if (stripos($clean, 'Pro') !== false) return 'AirPods Pro';
                return 'AirPods';

            case 'Vision Pro':
                return 'Apple Vision Pro';

            case 'Accessory':
                if (stripos($clean, 'Keyboard') !== false) {
                     if (stripos($clean, 'Smart') !== false) return 'Smart Keyboard';
                     return 'Magic Keyboard';
                }
                if (stripos($clean, 'Pencil') !== false) return 'Apple Pencil';
                return $clean;

            default:
                return $clean;
        }
    }

    private function getAggressiveVariant($name, $category)
    {
        if ($category === 'iPhone') {
            $fam = $this->getAggressiveFamily($name, $category);
            $var = trim(str_ireplace($fam, '', $name));
            return empty($var) ? 'Base Model' : $var;
        }

        if ($category === 'iPad' || $category === 'Mac') {
            // For Mac: Prioritize RAM extraction first (e.g., "16GB", "32GB")
            if ($category === 'Mac' && preg_match('/\s(\d+GB)\s*$/', $name, $m)) {
                return $m[1]; // Returns RAM like "16GB"
            }

            // Fallback to screen size for iPads or Macs without RAM
            if (preg_match('/(\d+(\.\d)?-inch)/', $name, $m)) {
                return $m[1];
            }
            if (preg_match('/\((.*?)\)/', $name, $m)) {
                 if (stripos($m[1], 'generation') !== false || stripos($m[1], 'Gen') !== false) {
                     return 'Standard';
                 }
            }
        }

        if ($category === 'Apple Watch') {
            // Logic Varian:

            // 1. Cek Gen (Prioritas buat SE)
            if (preg_match('/\((.*?)\)/', $name, $m)) {
                $gen = $this->shortenGen($m[1]);
                // Kalau ada Gen DAN Ukuran (e.g. SE 2nd Gen 44mm)
                if (preg_match('/(\d{2}mm)/', $name, $size)) {
                    return $gen . ' ' . $size[1]; // Output: "Gen 2 44mm"
                }
                return $gen;
            }

            // 2. Standard Series/Ultra (Cuma butuh ukuran)
            if (preg_match('/(\d{2}mm)/', $name, $m)) {
                return $m[1];
            }

            return 'Standard';
        }

        if ($category === 'Apple TV') {
            $var = [];
            if (preg_match('/\((.*?)\)/', $name, $m)) {
                $var[] = $this->shortenGen($m[1]);
            }
            if (stripos($name, 'Ethernet') !== false) $var[] = 'Ethernet';
            return empty($var) ? 'Standard' : implode(' + ', $var);
        }

        if ($category === 'Home Accessory') {
            if (preg_match('/\((.*?)\)/', $name, $m)) {
                return $this->shortenGen($m[1]);
            }
            foreach (self::HOMEPOD_COLORS as $col) {
                if (stripos($name, $col) !== false) return $col;
            }
            return 'Standard';
        }

        if ($category === 'Audio') {
            foreach (self::AIRPODS_COLORS as $color) {
                if (stripos($name, $color) !== false) return $color;
            }
            if (preg_match('/\((.*?)\)/', $name, $m)) {
                return $this->shortenGen($m[1]);
            }
            if (stripos($name, 'MagSafe') !== false) return 'MagSafe';
            return 'Standard';
        }

        if ($category === 'Vision Pro') {
            if (preg_match('/(\d+GB|\d+TB)/', $name, $m)) {
                return $m[1];
            }
        }

        if ($category === 'Accessory') {
            if (stripos($name, 'Pro') !== false) return 'Pro';
            if (preg_match('/\((.*?)\)/', $name, $m)) return $this->shortenGen($m[1]);
            if (stripos($name, 'for') !== false) {
                 return trim(substr($name, strpos($name, 'for') + 3));
            }
        }

        return 'Standard';
    }

    private function shortenGen($text) {
        if (preg_match('/(\d{1,2})(?:st|nd|rd|th)?/i', $text, $num)) {
            return 'Gen ' . $num[1];
        }
        return str_replace(['generation', 'Gen'], '', $text);
    }
}
