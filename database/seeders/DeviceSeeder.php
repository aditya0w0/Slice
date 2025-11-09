<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
{
    public function run()
    {
        $models = [
            'iPhone XR','iPhone XR Max','iPhone XS','iPhone XS Max','iPhone 11','iPhone 11 Pro','iPhone 11 Pro Max','iPhone 12 mini','iPhone 12','iPhone 12 Pro','iPhone 12 Pro Max',
            'iPhone 13 mini','iPhone 13','iPhone 13 Pro','iPhone 13 Pro Max','iPhone 14','iPhone 14 Pro','iPhone 14 Pro Max',
            'iPhone 15','iPhone 15 Pro','iPhone 15 Pro Max','iPhone 16 Pro','iPhone 16 Pro Max','iPhone 17','iPhone 17 Pro','iPhone 17 Pro Max'
        ];

        $rows = [];
        foreach ($models as $i => $name) {
            $slug = Str::slug($name);

            // infer family by stripping known suffixes like 'Pro Max', 'Pro', 'Max', 'mini'
            $family = $name;
            $lname = strtolower($name);
            if (str_ends_with($lname, ' pro max')) {
                $family = trim(substr($name, 0, -strlen(' Pro Max')));
            } else {
                $parts = explode(' ', $name);
                $last = end($parts);
                $lastLower = strtolower($last);
                if (in_array(ucfirst($lastLower), array_map('ucfirst', ['pro','max','mini']))) {
                    array_pop($parts);
                    $family = implode(' ', $parts);
                }
            }

            $rows[] = [
                'name' => $name,
                'slug' => $slug,
                'family' => $family,
                'category' => 'iPhone',
                'generation' => $this->parseGenerationFromName($name),
                'variant' => '',
                'price_monthly' => 29 + ($i % 6) * 10, // sample price spread
                'image' => '/images/product-iphone.svg',
                'description' => 'Flexible rental plan for ' . $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // iPad family models (2020+)
        $ipadModels = [
            [
                'name' => 'iPad (8th generation) 2020',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad.svg',
                'price' => 20,
            ],
            [
                'name' => 'iPad (9th generation) 2021',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad.svg',
                'price' => 22,
            ],
            [
                'name' => 'iPad Mini (6th generation) 2021',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad-mini.svg',
                'price' => 28,
            ],
            [
                'name' => 'iPad Air (4th generation) 2020',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad-air.svg',
                'price' => 35,
            ],
            [
                'name' => 'iPad Air (5th generation) 2022',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad-air.svg',
                'price' => 38,
            ],
            [
                'name' => 'iPad Pro 11-inch (2nd generation) 2020',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad-pro.svg',
                'price' => 50,
            ],
            [
                'name' => 'iPad Pro 12.9-inch (4th generation) 2020',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad-pro.svg',
                'price' => 60,
            ],
            [
                'name' => 'iPad Pro 11-inch (3rd generation) 2021 (M1)',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad-pro.svg',
                'price' => 55,
            ],
            [
                'name' => 'iPad Pro 12.9-inch (5th generation) 2021 (M1)',
                'family' => 'iPad',
                'category' => 'iPad',
                'image' => '/images/product-ipad-pro.svg',
                'price' => 70,
            ],
        ];

        foreach ($ipadModels as $im) {
            $slug = Str::slug($im['name']);
            $rows[] = [
                'name' => $im['name'],
                'slug' => $slug,
                'family' => $im['family'],
                'category' => $im['category'],
                'generation' => $this->parseGenerationFromName($im['name']),
                'variant' => '',
                'price_monthly' => $im['price'],
                'image' => $im['image'],
                'description' => 'Flexible rental plan for ' . $im['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Mac family models
        $macModels = [
            [
                'name' => 'iMac 24-inch (M1) 2021',
                'family' => 'iMac',
                'category' => 'Mac',
                'image' => '/images/product-mac.svg',
                'price' => 120,
            ],
            [
                'name' => 'MacBook Air (M1) 2020',
                'family' => 'MacBook Air',
                'category' => 'Mac',
                'image' => '/images/product-mac.svg',
                'price' => 100,
            ],
        ];

        foreach ($macModels as $m) {
            $slug = Str::slug($m['name']);
            $rows[] = [
                'name' => $m['name'],
                'slug' => $slug,
                'family' => $m['family'],
                'category' => $m['category'],
                'generation' => $this->parseGenerationFromName($m['name']),
                'variant' => '',
                'price_monthly' => $m['price'],
                'image' => $m['image'],
                'description' => 'Flexible rental plan for ' . $m['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Apple Watch / wearables (treated as Accessories category)
        $watchModels = [
            [
                'name' => 'Apple Watch Series 6',
                'family' => 'Apple Watch',
                'category' => 'Accessories',
                'image' => '/images/product-watch.svg',
                'price' => 25,
            ],
            [
                'name' => 'Apple Watch SE',
                'family' => 'Apple Watch',
                'category' => 'Accessories',
                'image' => '/images/product-watch.svg',
                'price' => 18,
            ],
        ];

        foreach ($watchModels as $w) {
            $slug = Str::slug($w['name']);
            $rows[] = [
                'name' => $w['name'],
                'slug' => $slug,
                'family' => $w['family'],
                'category' => $w['category'],
                'generation' => $this->parseGenerationFromName($w['name']),
                'variant' => '',
                'price_monthly' => $w['price'],
                'image' => $w['image'],
                'description' => 'Flexible rental plan for ' . $w['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // use upsert so seeding is idempotent (insert new or update existing by slug)
        DB::table('devices')->upsert(
            $rows,
            ['slug'],
            ['name', 'family', 'category', 'variant', 'price_monthly', 'image', 'description', 'updated_at']
        );
    }

    /**
     * Extract generation from a device name using the same heuristics as
     * App\Models\Device::getGenerationAttribute. Kept here so seeding can
     * populate the DB column deterministically during migrate --seed.
     */
    protected function parseGenerationFromName(string $name): int
    {
        // 1) Prefer explicit "Nth generation" patterns: e.g. "(4th generation)"
        if (preg_match('/(\d{1,2})(?:st|nd|rd|th)? generation/i', $name, $m)) {
            return (int)$m[1];
        }

        // 2) 4-digit year
        if (preg_match('/(19|20)\d{2}/', $name, $y)) {
            return (int)$y[0];
        }

        // 3) standalone 1-2 digit number not followed by a dot
        if (preg_match('/\b(\d{1,2})\b(?!\.)/', $name, $m2)) {
            return (int)$m2[1];
        }

        // 4) letter based special cases
        if (stripos($name, 'xs') !== false || stripos($name, 'xr') !== false || stripos($name, ' x ') !== false) return 10;
        if (stripos($name, 'se') !== false) return 9;

        return 0;
    }
}
