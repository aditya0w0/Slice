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
        'family',
        'category',
        'variant',
        'price_monthly',
        'image',
        'description',
    ];

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
        $name = $this->name ?? '';
        preg_match('/(\d{1,2})/', $name, $m);
        if (!empty($m)) return (int)$m[1];
        if (stripos($name, 'xs') !== false || stripos($name, 'xr') !== false || stripos($name, ' x ') !== false) return 10;
        if (stripos($name, 'se') !== false) return 9;
        return 0;
    }
}
