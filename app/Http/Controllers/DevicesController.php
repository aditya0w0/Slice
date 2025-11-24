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
        $category = $request->query('category');
        // Show only unique device families (iPhone, iPad, Mac, Apple Watch)
        // Users click a family card to see variants (Base/Mini/Pro/Pro Max)
        $baseQuery = Device::query();
        if (!empty($category)) {
            $baseQuery->where('category', $category);
        }

        $families = $baseQuery->selectRaw("COALESCE(family, name) as family")
            ->distinct()
            ->pluck('family')
            ->filter()
            ->values();

        // FIX: Split "iPad" family into individual models (e.g. iPad 8, iPad Air 4)
        // because the user wants to see them listed separately like iPhones.
        if ($families->contains('iPad')) {
            $families = $families->reject(fn($f) => $f === 'iPad');

            // Fetch all iPad names to treat them as distinct families
            $ipadNames = Device::where('category', 'iPad')
                ->pluck('name')
                ->unique()
                ->values();

            $families = $families->merge($ipadNames)->sort()->values();
        }

        // Build family cards for the index page
        // If the user is filtering by iPad category, list all models individually so
        // the index page shows each iPad model rather than a single "iPad" family card.
        if (!empty($category) && mb_strtolower($category) === 'ipad') {
            $devices = Device::where('category', 'iPad')
                ->orderBy('generation', 'desc')
                ->orderBy('name')
                ->get();
            $baseModels = $devices->map(function ($device) {
                return [
                    'slug' => $device->slug,
                    'name' => $device->name,
                    'family_name' => $device->family ?? $device->name,
                    'family_slug' => $device->slug,
                    'image' => $device->image ?? null,
                    'category' => $device->category,
                    'generation' => $device->generation,
                    'is_device' => false,
                ];
            })->values();
        } else {
            // Sort families by category priority and generation
            $categoryPriority = [
                'iPhone' => 1,
                'iPad' => 2,
                'Mac' => 3,
                'Apple Watch' => 4,
                'Apple TV' => 5,
                'Accessories' => 6,
            ];

            $sortedFamilies = $families->map(function ($familyName) use ($category, $categoryPriority) {
                // Get sample device for sorting info
                $sampleDevice = Device::where(function ($q) use ($familyName, $category) {
                    $q->where('family', $familyName)
                      ->orWhere('name', 'like', $familyName . '%');
                    if (!empty($category)) {
                        $q->where('category', $category);
                    }
                })->first();

                $categoryOrder = $categoryPriority[$sampleDevice->category ?? 'Accessories'] ?? 99;
                $generation = $sampleDevice->generation ?? 0;

                return [
                    'name' => $familyName,
                    'category' => $sampleDevice->category ?? null,
                    'generation' => $generation,
                    'category_order' => $categoryOrder,
                    'sample_device' => $sampleDevice,
                ];
            })->sort(function ($a, $b) {
                // Sort by category priority first
                if ($a['category_order'] !== $b['category_order']) {
                    return $a['category_order'] <=> $b['category_order'];
                }
                // Then by generation (newest first)
                if ($a['generation'] !== $b['generation']) {
                    return $b['generation'] <=> $a['generation'];
                }
                // Finally alphabetically
                return strcmp($a['name'], $b['name']);
            })->pluck('name')->values();

            $baseModels = $sortedFamilies->map(function ($familyName) use ($category) {
                $familySlug = Str::slug($familyName);

                // Get one device from this family for display info
                $sampleDevice = Device::where(function ($q) use ($familyName, $category) {
                    $q->where('family', $familyName)
                      ->orWhere('name', 'like', $familyName . '%');
                    if (!empty($category)) {
                        $q->where('category', $category);
                    }
                })->orderBy('generation', 'desc')->first();

                return [
                    'slug' => $familySlug,
                    'name' => $familyName,
                    'family_name' => $familyName,
                    'family_slug' => $familySlug,
                    'image' => $sampleDevice->image ?? null,
                    'category' => $sampleDevice->category ?? null,
                    'price_monthly' => $sampleDevice->price_monthly ?? null,
                ];
            })->values();
        }

        // pass available categories for UI dropdown
        $categories = Device::select('category')->distinct()->pluck('category')->filter()->values();

            return view('devices.index', [
                'baseModels' => $baseModels,
                'cartCount' => $this->getCartCount(),
                'categories' => $categories,
                'activeCategory' => $category,
                ]);

            }
    public function show($slug)
    {
        $device = Device::where('slug', $slug)->firstOrFail();

        return view('devices.show', compact('device'));
    }

public function family($family)
    {
        $slug = Str::slug($family);

        // Resolve slug. Sekarang return 3 data: BaseName, Generation, dan ExactDeviceName
        [$baseName, $wantedGeneration, $exactDeviceName] = $this->resolveFamily($slug);

        // Query dasar: Cari family yang cocok
        $query = Device::where(function ($q) use ($baseName) {
            $q->where('family', $baseName)
              ->orWhere('name', 'like', $baseName . '%');
        });

        // FIX BRUTAL LIST:
        // Kalo kita dapet match device spesifik (misal iPad 9th Gen),
        // kita persempit query biar cuma ambil varian dia doang (Storage/Color),
        // jangan ambil sodara-sodaranya (iPad 8, Air, dll).
        if ($exactDeviceName) {
            // Ambil nama depan sebelum varian storage (misal "iPad (9th generation) 2021")
            // Asumsi: Nama device di DB konsisten formatnya
            $query->where('name', 'like', $exactDeviceName . '%');
        }

        $devices = $query->orderBy('name')->get();

        // Build variant objects
        $variants = $this->buildVariantsFromDevices($devices);

        // Filtering tambahan kalo cuma dapet Generation number (bukan exact name)
        if (!is_null($wantedGeneration) && !$exactDeviceName) {
            // Filter by generation
             $genFiltered = $variants->filter(function ($v) use ($wantedGeneration) {
                return $v->generation === $wantedGeneration;
            })->values();

             if ($genFiltered->isNotEmpty()) {
                 $variants = $genFiltered;
             }
        }

        // Sorting logic (tetep sama kayak punya lu)
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

    /**
     * Resolve a family slug to its base name and optional generation.
     */
    private function resolveFamily(string $slug): array
    {
        // 1. PRIORITAS UTAMA: Cek apakah slug ini adalah DEVICE SPESIFIK?
        // Ini fix buat kasus "ipad-9th-generation-2021" yang tadi 404.
        // Kita langsung ambil data valid dari DB, gak usah regex-regex aneh.
        $device = Device::where('slug', $slug)->first();

        if ($device) {
            // Ketemu! Balikin Family asli, Generasi asli (9, bukan 2021), dan Nama aslinya
            // Kita perlu hapus embel-embel storage (misal " 64GB") dari nama buat filtering
            $cleanName = preg_replace('/\s\d+(GB|TB)$/i', '', $device->name);
            return [$device->family, $device->generation, $cleanName];
        }

        // 2. Kalo gak ketemu device exact, baru pake logic tebak-tebakan (Regex)
        // Buat handle URL generic kayak "/family/iphone-13" (kalo itu bukan slug device)

        $families = Device::selectRaw("COALESCE(family, name) as family")
            ->distinct()
            ->pluck('family')
            ->filter()
            ->values();

        $slugToFamily = $families->mapWithKeys(function ($name) {
            return [Str::slug($name) => $name];
        });

        $wantedGeneration = null;

        // Logic Regex lama lu (buat fallback)
        if (preg_match('/^(.*)-(\d{1,4})$/', $slug, $m)) {
            $maybeFamily = $m[1];
            $maybeGen = (int) $m[2];
            // Validasi: Anggap gen valid cuma kalo < 100 (biar 2021 gak dianggap gen)
            // Atau cek keberadaan family
            if (isset($slugToFamily[$maybeFamily]) || Device::where('family', 'like', $maybeFamily)->exists()) {
                 $slug = $maybeFamily;
                 $wantedGeneration = $maybeGen;
            }
        }

        if (isset($slugToFamily[$slug])) {
            $baseName = $slugToFamily[$slug];
        } else {
            // Fallback pencarian nama family manual
            $familyCandidate = str_replace('-', ' ', $slug);
            $deviceWithFamily = Device::whereRaw('LOWER(family) = ?', [mb_strtolower($familyCandidate)])->first();

            if ($deviceWithFamily) {
                $baseName = $deviceWithFamily->family;
            } else {
                 // Last resort search
                 $baseName = ucwords($familyCandidate);
            }
        }

        return [$baseName, $wantedGeneration, null]; // Null karena bukan exact device match
    }

    /**
     * Resolve a family slug to its base name and optional generation.
     *
     * @param string $slug The incoming slug (e.g. 'ipad', 'iphone-11')
     * @return array [$baseName, $wantedGeneration] where $wantedGeneration is int|null
     */

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
