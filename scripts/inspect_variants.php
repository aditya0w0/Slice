<?php
// Temporary debug script to inspect resolved variants for family slugs
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Device;
use Illuminate\Support\Str;

function resolveVariantsForSlug($slugRaw) {
    $slug = Str::slug($slugRaw);

    $families = Device::selectRaw("COALESCE(family, name) as family")->distinct()->pluck('family')->filter()->values();
    $slugToFamily = $families->mapWithKeys(function ($name) {
        return [Str::slug($name) => $name];
    });

    $wantedGeneration = null;
    if (preg_match('/^(.*)-(\d{1,4})$/', $slug, $m)) {
        $maybeFamily = $m[1];
        $maybeGen = (int) $m[2];
        $slug = $maybeFamily;
        $wantedGeneration = $maybeGen;
    }

    if (!isset($slugToFamily[$slug])) {
        $familyCandidate = str_replace('-', ' ', $slug);
        $deviceWithFamily = Device::whereRaw('LOWER(family) = ?', [mb_strtolower($familyCandidate)])->first();
        if ($deviceWithFamily && $deviceWithFamily->family) {
            $baseName = $deviceWithFamily->family;
        } else {
            $deviceByName = Device::whereRaw('LOWER(name) LIKE ?', [mb_strtolower($familyCandidate) . '%'])->first();
            if ($deviceByName) {
                if (!empty($deviceByName->family)) {
                    $fam = $deviceByName->family;
                    if (mb_strtolower($slug) === 'iphone' && mb_stripos($fam, 'iphone') === 0) {
                        $baseName = 'iPhone';
                    } else {
                        $baseName = $fam;
                    }
                } else {
                    $baseName = ucwords(str_replace('-', ' ', $slug));
                }
            } else {
                $matched = $families->first(function ($f) use ($slug) {
                    return Str::slug($f) === $slug;
                });
                if ($matched) {
                    $baseName = $matched;
                } else {
                    echo "slug $slugRaw -> no match (404)\n";
                    return;
                }
            }
        }
    } else {
        $baseName = $slugToFamily[$slug];
    }

    $devices = Device::where(function ($q) use ($baseName) {
        $q->where('family', $baseName)
          ->orWhere('name', 'like', $baseName . '%');
    })->orderBy('name')->get();

    $variants = collect();
    foreach ($devices as $device) {
        $variants->push((object) [
            'id' => $device->id,
            'name' => $device->name,
            'slug' => $device->slug ?: Str::slug($device->name),
            'generation' => $device->generation ?? 0,
            'image' => $device->image,
        ]);
    }

    // apply same generation preference logic as controller
    if (!is_null($wantedGeneration)) {
        $basePrefixFiltered = $variants->filter(function ($v) use ($wantedGeneration, $baseName) {
            if ($v->generation !== $wantedGeneration) return false;
            return (bool) preg_match('/^' . preg_quote($baseName, '/') . '\b/i', $v->name);
        })->values();

        if (!$basePrefixFiltered->isEmpty()) {
            $variants = $basePrefixFiltered;
        } else {
            $genOnly = $variants->filter(function ($v) use ($wantedGeneration) {
                return $v->generation === $wantedGeneration;
            })->values();

            if (!$genOnly->isEmpty()) {
                $variants = $genOnly;
            }
        }
    }

    echo "--- slug: $slugRaw -> baseName: $baseName (wantedGen: " . ($wantedGeneration ?? 'none') . ")\n";
    foreach ($variants as $v) {
        echo "id={$v->id} | name={$v->name} | slug={$v->slug} | gen={$v->generation} | image=" . ($v->image ?? '[NULL]') . "\n";
    }
    echo "\n";
}

$slugs = [
    'ipad-4', 'ipad-8', 'ipad-9',
    'imac-24-inch-m1-2021', 'imac', 'macbook-air',
    'apple-watch-series-6', 'apple-watch', 'apple-watch-se',
    'accessories'
];
foreach ($slugs as $s) resolveVariantsForSlug($s);

