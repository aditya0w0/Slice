<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DevicesController extends Controller
{
    public function index(Request $request)
    {
        // Show only unique device families (iPhone, iPad, Mac, Apple Watch)
        // Users click a family card to see variants (Base/Mini/Pro/Pro Max)
        $families = Device::selectRaw("COALESCE(family, name) as family")
            ->distinct()
            ->pluck('family')
            ->filter()
            ->values();

        // Build family cards for the index page
        $baseModels = $families->map(function ($familyName) {
            $familySlug = Str::slug($familyName);

            // Get one device from this family for display info
            $sampleDevice = Device::where('family', $familyName)
                ->orWhere('name', 'like', $familyName . '%')
                ->first();

            return [
                'slug' => $familySlug,
                'name' => $familyName,
                'family_name' => $familyName,
                'family_slug' => $familySlug,
                'image' => $sampleDevice->image ?? null,
            ];
        })->values();

        return view('devices.index', ['baseModels' => $baseModels, 'cartCount' => $this->getCartCount()]);
    }

    public function show($slug)
    {
        $device = Device::where('slug', $slug)->firstOrFail();

        return view('devices.show', compact('device'));
    }

    public function family($family)
    {
        // Render a family-level page. Accepts a slug like 'ipad' or 'iphone-11'.
        $slug = Str::slug($family);

        // Resolve slug to base family name and optional generation
        [$baseName, $wantedGeneration] = $this->resolveFamily($slug);

        $devices = Device::where(function ($q) use ($baseName) {
            $q->where('family', $baseName)
              ->orWhere('name', 'like', $baseName . '%');
        })->orderBy('name')->get();

        // Build variant objects from devices
        $variants = $this->buildVariantsFromDevices($devices);

        if (!is_null($wantedGeneration)) {
            // 1) Prefer generation matches where the device name begins with the
            //    base family name (e.g. "iPad (4th generation)"), which avoids
            //    mixing unrelated product lines (Air/Pro) that happen to share
            //    the same numeric generation.
            $basePrefixFiltered = $variants->filter(function ($v) use ($wantedGeneration, $baseName) {
                if ($v->generation !== $wantedGeneration) return false;
                // case-insensitive check that name starts with baseName (word boundary)
                return (bool) preg_match('/^' . preg_quote($baseName, '/') . '\b/i', $v->name);
            })->values();

            if (!$basePrefixFiltered->isEmpty()) {
                $variants = $basePrefixFiltered;
            } else {
                // 2) Next, try a broader generation-only filter (may include Air/Pro)
                $genOnly = $variants->filter(function ($v) use ($wantedGeneration) {
                    return $v->generation === $wantedGeneration;
                })->values();

                if (!$genOnly->isEmpty()) {
                    $variants = $genOnly;
                } else {
                    // 3) finally fall back to showing all family variants (safer than empty page)
                    // keep $variants as originally populated
                }
            }
        }

        $order = ['base' => 0, 'mini' => 1, 'pro' => 2, 'max' => 3, 'pro max' => 4];
        $variants = $variants->sort(function ($a, $b) use ($order) {
            if ($a->generation === $b->generation) {
                $oa = $order[$a->variant_type] ?? 0;
                $ob = $order[$b->variant_type] ?? 0;
                return $oa <=> $ob;
            }
            return $b->generation <=> $a->generation;
        })->values();

        return view('devices.family', [
            'family' => $baseName,
            'variants' => $variants,
            'cartCount' => $this->getCartCount(),
        ]);
    }

    public function model($slug)
    {
        // Resolve slug to base family name and optional generation
        [$baseName, $wantedGeneration] = $this->resolveFamily($slug);

        // Query only devices that match this family
        $devices = Device::where(function ($q) use ($baseName) {
            $q->where('family', $baseName)
              ->orWhere('name', 'like', $baseName . '%');
        })->orderBy('name')->get();

        // Build variant objects from devices
        $variants = $this->buildVariantsFromDevices($devices);

        // If the incoming slug included a generation qualifier, filter variants to that generation.
        if (!is_null($wantedGeneration)) {
            $variants = $variants->filter(function ($v) use ($wantedGeneration) {
                // if generation is year-like (>= 1900) match year; otherwise match numeric generation
                if ($wantedGeneration >= 1900) {
                    return $v->generation === $wantedGeneration;
                }
                return $v->generation === $wantedGeneration;
            })->values();
        }

        // sort variants: generation desc then variant order
        $order = ['base' => 0, 'mini' => 1, 'pro' => 2, 'max' => 3, 'pro max' => 4];
        $variants = $variants->sort(function ($a, $b) use ($order) {
            if ($a->generation === $b->generation) {
                $oa = $order[$a->variant_type] ?? 0;
                $ob = $order[$b->variant_type] ?? 0;
                return $oa <=> $ob;
            }
            return $b->generation <=> $a->generation;
        })->values();

        return view('devices.model', [
            'base' => $baseName,
            'variants' => $variants,
            'cartCount' => $this->getCartCount(),
        ]);
    }

    /**
     * Resolve a family slug to its base name and optional generation.
     *
     * @param string $slug The incoming slug (e.g. 'ipad', 'iphone-11')
     * @return array [$baseName, $wantedGeneration] where $wantedGeneration is int|null
     */
    private function resolveFamily(string $slug): array
    {
        // Get all distinct families from DB
        $families = Device::selectRaw("COALESCE(family, name) as family")
            ->distinct()
            ->pluck('family')
            ->filter()
            ->values();

        $slugToFamily = $families->mapWithKeys(function ($name) {
            return [Str::slug($name) => $name];
        });

        // Check if slug contains generation suffix (e.g. 'ipad-8' or 'iphone-11')
        $wantedGeneration = null;
        if (preg_match('/^(.*)-(\d{1,4})$/', $slug, $m)) {
            $maybeFamily = $m[1];
            $maybeGen = (int) $m[2];
            $slug = $maybeFamily;
            $wantedGeneration = $maybeGen;
        }

        // Try to resolve slug to base family name
        if (!isset($slugToFamily[$slug])) {
            $familyCandidate = str_replace('-', ' ', $slug);

            // First try: direct family column match (case-insensitive)
            $deviceWithFamily = Device::whereRaw('LOWER(family) = ?', [mb_strtolower($familyCandidate)])->first();
            if ($deviceWithFamily && $deviceWithFamily->family) {
                $baseName = $deviceWithFamily->family;
            } else {
                // Second try: name prefix match
                $deviceByName = Device::whereRaw('LOWER(name) LIKE ?', [mb_strtolower($familyCandidate) . '%'])->first();
                if ($deviceByName) {
                    if (!empty($deviceByName->family)) {
                        $fam = $deviceByName->family;
                        // Special case: normalize 'iphone' slug to 'iPhone' family
                        if (mb_strtolower($slug) === 'iphone' && mb_stripos($fam, 'iphone') === 0) {
                            $baseName = 'iPhone';
                        } else {
                            $baseName = $fam;
                        }
                    } else {
                        $baseName = ucwords(str_replace('-', ' ', $slug));
                    }
                } else {
                    // Third try: slugify known families
                    $matched = $families->first(function ($f) use ($slug) {
                        return Str::slug($f) === $slug;
                    });
                    if ($matched) {
                        $baseName = $matched;
                    } else {
                        abort(404);
                    }
                }
            }
        } else {
            $baseName = $slugToFamily[$slug];
        }

        return [$baseName, $wantedGeneration];
    }

    /**
     * Build variant objects from device collection.
     *
     * @param \Illuminate\Database\Eloquent\Collection $devices
     * @return \Illuminate\Support\Collection
     */
    private function buildVariantsFromDevices($devices)
    {
        $variants = collect();

        foreach ($devices as $device) {
            $vtype = $device->variant_type ?? 'base';
            $gen = $device->generation ?? 0;

            $variantLabel = $device->variant_label ?? null;
            if (empty($variantLabel)) {
                if (!empty($device->family)) {
                    $variantLabel = trim(str_ireplace($device->family, '', $device->name));
                    $variantLabel = preg_replace('/^[\s\-]+/','', trim($variantLabel));
                    if ($variantLabel === '') $variantLabel = 'Base';
                } else {
                    $parts = explode(' ', $device->name);
                    $last = end($parts);
                    $lastLower = mb_strtolower($last);
                    if (in_array(ucfirst($lastLower), array_map('ucfirst', ['pro','max','mini']))) {
                        $variantLabel = $last;
                    } else {
                        $variantLabel = 'Base';
                    }
                }
            }

            $slugVal = $device->slug ?: Str::slug($device->name);
            $variants->push((object) [
                'id' => $device->id,
                'name' => $device->name,
                'slug' => $slugVal,
                'sku' => $device->sku,
                'variant' => $variantLabel,
                'variant_type' => $vtype,
                'generation' => (int) ($gen ?? 0),
                'price_monthly' => (int) ($device->price_monthly ?? 0),
                'price_formatted' => $device->price_formatted,
                'image' => $device->image,
                'description' => $device->description,
            ]);
        }

        return $variants;
    }
}
