<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'family',
        'category',
        'variant',
        'price_monthly',
        'image',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate SKU when creating a new device
        static::creating(function ($device) {
            if (empty($device->sku)) {
                $device->sku = static::generateSku();
            }
        });
    }

    /**
     * Generate a unique SKU for the device
     */
    public static function generateSku()
    {
        do {
            // Format: SLC-XXXX-YYYY (SLC = Slice, XXXX = random letters, YYYY = random numbers)
            $sku = 'SLC-' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4)) . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('sku', $sku)->exists());

        return $sku;
    }

    // convenience accessor for formatted price
    public function getPriceFormattedAttribute()
    {
        // assuming price_monthly stored as whole dollars
        return '$' . number_format($this->price_monthly, 0) . '/mo';
    }

    // Derived base name (prefer explicit `family` column, otherwise compute)
    public function getBaseNameAttribute()
    {
        if (!empty($this->family)) return $this->family;
        $name = $this->name ?? '';
        $lname = mb_strtolower($name);
        if (str_ends_with($lname, ' pro max')) {
            return trim(substr($name, 0, -strlen(' Pro Max')));
        }
        $parts = explode(' ', $name);
        $last = end($parts);
        $lastLower = mb_strtolower($last);
        if (in_array(ucfirst($lastLower), array_map('ucfirst', ['pro','max','mini']))) {
            array_pop($parts);
            return implode(' ', $parts);
        }
        return $name;
    }

    public function getVariantTypeAttribute()
    {
        $lname = mb_strtolower($this->name ?? '');
        if (str_contains($lname, 'pro max')) return 'pro max';
        if (str_contains($lname, ' max') || str_ends_with($lname, ' max')) return 'max';
        if (str_contains($lname, 'pro')) return 'pro';
        if (str_contains($lname, 'mini')) return 'mini';
        return 'base';
    }

    /**
     * Human-friendly variant label (e.g. 'Pro Max', 'Max', 'Pro', 'Mini', or 'Base').
     */
    public function getVariantLabelAttribute()
    {
        $type = $this->variant_type ?? 'base';
        if ($type === 'pro max') return 'Pro Max';
        if ($type === 'max') return 'Max';
        if ($type === 'pro') return 'Pro';
        if ($type === 'mini') return 'Mini';
        return 'Base';
    }

    public function getGenerationAttribute()
    {
        // If an explicit database column is present and set, prefer that — it
        // allows DB-level grouping and faster queries on the index page.
        if (array_key_exists('generation', $this->attributes) && !is_null($this->attributes['generation'])) {
            return (int) $this->attributes['generation'];
        }

        $name = $this->name ?? '';

        // 1) Prefer explicit "Nth generation" patterns: e.g. "(4th generation)"
        if (preg_match('/(\d{1,2})(?:st|nd|rd|th)? generation/i', $name, $m)) {
            return (int)$m[1];
        }

        // 2) If a 4-digit year exists (common on iPad rows), use the year. This gives
        //    a monotonic value that sorts newer models first within the family.
        if (preg_match('/(19|20)\d{2}/', $name, $y)) {
            return (int)$y[0];
        }

        // 3) Fall back to a standalone 1-2 digit number that is NOT immediately
        //    followed by a dot (this avoids matching sizes like "12.9-inch").
        if (preg_match('/\b(\d{1,2})\b(?!\.)/', $name, $m2)) {
            return (int)$m2[1];
        }

        // 4) Common letter-based generations / special cases
        if (stripos($name, 'xs') !== false || stripos($name, 'xr') !== false || stripos($name, ' x ') !== false) return 10;
        if (stripos($name, 'se') !== false) return 9;

        return 0;
    }

    /**
     * Human-friendly display title used in the index (presentation logic
     * extracted from Blade). Example: "iPad (8th generation)" or
     * "iPhone (2021)" when a year is present.
     */
    public function getDisplayTitleAttribute()
    {
        $displayTitle = $this->family_name ?? $this->base_name ?? $this->name;
        $gen = $this->generation ?? 0;

        if (!empty($gen) && $gen > 0) {
            // If the family name already contains the generation number, avoid
            // duplicating it.
            if (!preg_match('/\b' . preg_quote((string)$gen, '/') . '\b/', $displayTitle)) {
                if ($gen >= 1900) {
                    $displayTitle .= ' (' . $gen . ')';
                } else {
                    $n = (int)$gen;
                    $s = 'th';
                    if ((($n % 100) < 11) || (($n % 100) > 13)) {
                        if ($n % 10 == 1) $s = 'st';
                        elseif ($n % 10 == 2) $s = 'nd';
                        elseif ($n % 10 == 3) $s = 'rd';
                    }
                    $displayTitle .= ' (' . $n . $s . ' generation)';
                }
            }
        }

        return $displayTitle;
    }

    /**
     * Produces a family param for route generation that prefers including
     * generation when available (e.g. 'ipad-8' or 'iphone-12'). Mirrors the
     * logic previously embedded in the Blade template.
     */
    public function getFamilyParamAttribute()
    {
        $familyParam = $this->family_slug ?? null;
        if (empty($familyParam)) {
            // fallback to slug field or a sanitized base name
            if (!empty($this->slug)) return $this->slug;
            $familyParam = strtolower(preg_replace('/[^a-z0-9\-]+/i', '-', trim($this->base_name ?? $this->name ?? '')));
        }

        if (!empty($this->generation) && $this->generation > 0) {
            if (!preg_match('/-' . preg_quote((string)$this->generation, '/') . '$/', $familyParam)) {
                $familyParam = $familyParam . '-' . $this->generation;
            }
        }

        return $familyParam;
    }
}
