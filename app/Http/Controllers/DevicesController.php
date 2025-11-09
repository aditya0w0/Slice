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
        // Previous UI: list individual device models (separate model years) rather than grouping by family.
        // Support optional ?family=slug to filter the list to a specific family (so /devices?family=ipad
        // or /devices/ipad will show the same per-model listing for that family).
        $query = Device::query();
        $familyFilter = $request->query('family');
        if ($familyFilter) {
            // convert slug-like family to a readable name (e.g. 'ipad' -> 'ipad', 'iphone-11' -> 'iphone 11')
            $familyName = trim(str_replace('-', ' ', $familyFilter));
            // Filter by normalized family column (case-insensitive) OR name starting with the family
            $query->where(function ($q) use ($familyName) {
                $q->whereRaw('LOWER(COALESCE(family, "")) = ?', [mb_strtolower($familyName)])
                  ->orWhere('name', 'like', $familyName . '%');
            });
        }

        $devices = $query->orderBy('name')->get();

        // Build per-device cards but include the family slug so the 'View models' button
        // navigates to the family page (previous UI expectation).
        $baseModels = $devices->map(function ($device) {
            $slug = $device->slug ?: Str::slug($device->name);
            $familyName = $device->base_name ?? $device->name;
            $familySlug = Str::slug($familyName);
            return [
                'slug' => $slug,
                'name' => $device->name,
                'family_name' => $familyName,
                'family_slug' => $familySlug,
            ];
        })->values();

        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = CartItem::where('user_id', Auth::id())->count();
        } else {
            // count session cart items for guests
            $session = session()->get('cart.items', []);
            $cartCount = is_array($session) ? count($session) : 0;
        }

        return view('devices.index', ['baseModels' => $baseModels, 'cartCount' => $cartCount]);
    }

    public function show($slug)
    {
        $device = Device::where('slug', $slug)->firstOrFail();

        return view('devices.show', compact('device'));
    }

    public function family($family)
    {
        // Render a family-level page. Accepts a slug like 'ipad' or 'iphone-11'.
        // Reuse the same resolution logic as the model() action but render the
        // `devices.family` view so callers that expect a family page get the
        // family-specific layout.
        $slug = Str::slug($family);

        // Resolve base name from available distinct families (cheap) and then query only matching devices.
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
                        abort(404);
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
                'variant' => $variantLabel,
                'variant_type' => $vtype,
                'generation' => (int) ($gen ?? 0),
                'price_monthly' => (int) ($device->price_monthly ?? 0),
                'price_formatted' => $device->price_formatted,
                'image' => $device->image,
                'description' => $device->description,
            ]);
        }

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

        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = CartItem::where('user_id', Auth::id())->count();
        } else {
            $session = session()->get('cart.items', []);
            $cartCount = is_array($session) ? count($session) : 0;
        }

        return view('devices.family', [
            'family' => $baseName,
            'variants' => $variants,
            'cartCount' => $cartCount,
        ]);
    }

    public function model($slug)
    {
    // Resolve base name from available distinct families (cheap) and then query only matching devices.
        $families = Device::selectRaw("COALESCE(family, name) as family")->distinct()->pluck('family')->filter()->values();
        $slugToFamily = $families->mapWithKeys(function ($name) {
            return [Str::slug($name) => $name];
        });

        // Support generation-qualified slugs like "ipad-8" or "ipad-2020".
        $wantedGeneration = null;
        $originalSlug = $slug;
    if (preg_match('/^(.*)-(\d{1,4})$/', $slug, $m)) {
            $maybeFamily = $m[1];
            $maybeGen = (int) $m[2];
            // set the slug to the family portion so resolution logic can try to match it
            $slug = $maybeFamily;
            $wantedGeneration = $maybeGen;
        }

    // debug logging removed

        // Try to resolve the incoming slug to a base family name.
    if (!isset($slugToFamily[$slug])) {
            // First, try a direct family match in DB (case-insensitive)
            $familyCandidate = str_replace('-', ' ', $slug);
            $deviceWithFamily = Device::whereRaw('LOWER(family) = ?', [mb_strtolower($familyCandidate)])->first();
            if ($deviceWithFamily && $deviceWithFamily->family) {
                $baseName = $deviceWithFamily->family;
            } else {
                // Next, try a name prefix match (e.g. "ipad air" -> names starting with that)
                $deviceByName = Device::whereRaw('LOWER(name) LIKE ?', [mb_strtolower($familyCandidate) . '%'])->first();
                if ($deviceByName) {
                    // If the row has an explicit `family` column, prefer it — but avoid
                    // using overly-specific family values like "iPhone XR" when the
                    // incoming slug is the generic family ('iphone'). In that case normalize
                    // to the generic family name so the page aggregates all iPhone variants.
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
                    // fallback: try slugifying known families
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

    // debug logging removed

        // Query only devices that match this family (or start with family name if family column is null for some rows)
        $devices = Device::where(function ($q) use ($baseName) {
            $q->where('family', $baseName)
              ->orWhere('name', 'like', $baseName . '%');
        })->orderBy('name')->get();

        $variants = collect();

        foreach ($devices as $device) {
            // Use Device model accessors for consistent parsing
            $vtype = $device->variant_type ?? 'base';
            $gen = $device->generation ?? 0;

            // Variant display label: prefer accessor, fall back to trimming family from name
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

            // ensure slug exists
            $slugVal = $device->slug ?: Str::slug($device->name);

            $variants->push((object) [
                'id' => $device->id,
                'name' => $device->name,
                'slug' => $slugVal,
                'variant' => $variantLabel,
                'variant_type' => $vtype,
                'generation' => (int) ($gen ?? 0),
                'price_monthly' => (int) ($device->price_monthly ?? 0),
                'price_formatted' => $device->price_formatted,
                'image' => $device->image,
                'description' => $device->description,
            ]);
        }

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
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = CartItem::where('user_id', Auth::id())->count();
        } else {
            $session = session()->get('cart.items', []);
            $cartCount = is_array($session) ? count($session) : 0;
        }

        return view('devices.model', [
            'base' => $baseName,
            'variants' => $variants,
            'cartCount' => $cartCount,
        ]);
    }
}
