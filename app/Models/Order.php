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
        'user_id', 'variant_slug', 'capacity', 'months', 'price_monthly', 'total_price', 'status', 'invoice_number'
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate unique invoice number on creation
        static::creating(function ($order) {
            if (!$order->invoice_number) {
                $order->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    /**
     * Generate unique invoice number in format: INV-YYYYMMDD-XXXXX
     * Example: INV-20251111-00001
     */
    public static function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $prefix = "INV-{$date}-";

        // Get the last order created today
        $lastOrder = self::whereDate('created_at', today())
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder && $lastOrder->invoice_number) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastOrder->invoice_number, -5);
            $newSequence = $lastSequence + 1;
        } else {
            // First order of the day
            $newSequence = 1;
        }

        return $prefix . str_pad($newSequence, 5, '0', STR_PAD_LEFT);
    }

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
