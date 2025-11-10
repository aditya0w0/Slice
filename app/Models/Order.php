<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Device;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'variant_slug', 'capacity', 'months', 'price_monthly', 'total_price', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'variant_slug', 'slug');
    }

    // Display-friendly device name (e.g., "iPhone 11 Pro" instead of "iphone-11-pro")
    public function getDeviceNameAttribute()
    {
        if ($this->device) {
            return $this->device->name;
        }
        
        // Fallback: format the slug nicely
        return ucwords(str_replace('-', ' ', $this->variant_slug));
    }

    // Formatted price
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price_monthly, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total_price, 2);
    }
}
