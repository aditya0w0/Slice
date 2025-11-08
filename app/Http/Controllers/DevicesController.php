<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    ->orWhere('name', 'like', $familyName.'%');
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
        // legacy: redirect to model route by slugifying family
        return redirect()->route('devices.model', ['family' => Str::slug($family)]);
    }

    public function model($slug)
    {
        // Resolve base name from available distinct families (cheap) and then query only matching devices.
        $families = Device::selectRaw('COALESCE(family, name) as family')->distinct()->pluck('family')->filter()->values();
        $slugToFamily = $families->mapWithKeys(function ($name) {
            return [Str::slug($name) => $name];
        });

        if (! isset($slugToFamily[$slug])) {
            abort(404);
        }

        $baseName = $slugToFamily[$slug];

        // Query only devices that match this family (or start with family name if family column is null for some rows)
        $devices = Device::where(function ($q) use ($baseName) {
            $q->where('family', $baseName)
                ->orWhere('name', 'like', $baseName.'%');
        })->orderBy('name')->get();

        $variants = collect();

        foreach ($devices as $device) {
            // Use Device model accessors for consistent parsing
            $vtype = $device->variant_type ?? 'base';
            $gen = $device->generation ?? 0;

            // Variant display label: compute by trimming family from name
            // This works for both iPhone variants (e.g., "iPhone 13 Pro" -> "Pro")
            // and iPad generations (e.g., "iPad Air (5th generation) 2022" -> "(5th generation) 2022")
            $variantLabel = 'Base';
            if (! empty($device->family)) {
                $variantLabel = trim(str_ireplace($device->family, '', $device->name));
                $variantLabel = preg_replace('/^[\s\-]+/', '', trim($variantLabel));
                if ($variantLabel === '') {
                    $variantLabel = 'Base';
                }
            } else {
                $parts = explode(' ', $device->name);
                $last = end($parts);
                $lastLower = mb_strtolower($last);
                if (in_array(ucfirst($lastLower), array_map('ucfirst', ['pro', 'max', 'mini']))) {
                    $variantLabel = $last;
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
