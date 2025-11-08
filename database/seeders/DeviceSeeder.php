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
                'variant' => '',
                'price_monthly' => 29 + ($i % 6) * 10, // sample price spread
                'image' => '/images/product-iphone.svg',
                'description' => 'Flexible rental plan for ' . $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // iPad family models (2020+)
        // Each product line (iPad, iPad Mini, iPad Air, iPad Pro) is treated as a separate family
        // to match the iPhone structure where each generation is a family
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
                'family' => 'iPad Mini',
                'category' => 'iPad',
                'image' => '/images/product-ipad-mini.svg',
                'price' => 28,
            ],
            [
                'name' => 'iPad Air (4th generation) 2020',
                'family' => 'iPad Air',
                'category' => 'iPad',
                'image' => '/images/product-ipad-air.svg',
                'price' => 35,
            ],
            [
                'name' => 'iPad Air (5th generation) 2022',
                'family' => 'iPad Air',
                'category' => 'iPad',
                'image' => '/images/product-ipad-air.svg',
                'price' => 38,
            ],
            [
                'name' => 'iPad Pro 11-inch (2nd generation) 2020',
                'family' => 'iPad Pro',
                'category' => 'iPad',
                'image' => '/images/product-ipad-pro.svg',
                'price' => 50,
            ],
            [
                'name' => 'iPad Pro 12.9-inch (4th generation) 2020',
                'family' => 'iPad Pro',
                'category' => 'iPad',
                'image' => '/images/product-ipad-pro.svg',
                'price' => 60,
            ],
            [
                'name' => 'iPad Pro 11-inch (3rd generation) 2021 (M1)',
                'family' => 'iPad Pro',
                'category' => 'iPad',
                'image' => '/images/product-ipad-pro.svg',
                'price' => 55,
            ],
            [
                'name' => 'iPad Pro 12.9-inch (5th generation) 2021 (M1)',
                'family' => 'iPad Pro',
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
                'variant' => '',
                'price_monthly' => $im['price'],
                'image' => $im['image'],
                'description' => 'Flexible rental plan for ' . $im['name'],
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
}
