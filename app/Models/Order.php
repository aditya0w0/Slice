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
        'user_id',
        'variant_slug',
        'capacity',
        'months',
        'price_monthly',
        'total_price',
        'status',
        'invoice_number',
        'delivery_status',
        'processing_at',
        'packed_at',
        'shipped_at',
        'out_for_delivery_at',
        'delivered_at',
        'estimated_delivery_date',
        'delivery_notes',
        'tracking_number',
        'courier_name',
        'courier_phone',
    ];

    protected $casts = [
        'processing_at' => 'datetime',
        'packed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'out_for_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_delivery_date' => 'datetime',
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
     * 
     * Uses database transaction and row locking to prevent race conditions
     */
    public static function generateInvoiceNumber()
    {
        return \DB::transaction(function () {
            $date = now()->format('Ymd');
            $prefix = "INV-{$date}-";

            // Get the last order created today with row-level lock
            $lastOrder = self::whereDate('created_at', today())
                ->where('invoice_number', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
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
        });
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

    /**
     * Get delivery progress percentage (0-100)
     */
    public function getDeliveryProgressAttribute()
    {
        $statuses = ['pending', 'processing', 'packed', 'shipped', 'out_for_delivery', 'delivered'];
        $currentIndex = array_search($this->delivery_status, $statuses);

        if ($currentIndex === false) {
            return 0;
        }

        return ($currentIndex / (count($statuses) - 1)) * 100;
    }

    /**
     * Get human-readable delivery status
     */
    public function getDeliveryStatusLabelAttribute()
    {
        return match($this->delivery_status) {
            'pending' => 'Order Confirmed',
            'processing' => 'Processing Your Order',
            'packed' => 'Order Packed',
            'shipped' => 'Shipped',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            default => 'Unknown Status',
        };
    }

    /**
     * Get delivery timeline steps
     */
    public function getDeliveryTimelineAttribute()
    {
        return [
            [
                'status' => 'pending',
                'label' => 'Order Confirmed',
                'icon' => 'check-circle',
                'completed' => !is_null($this->created_at),
                'timestamp' => $this->created_at,
            ],
            [
                'status' => 'processing',
                'label' => 'Processing',
                'icon' => 'clock',
                'completed' => !is_null($this->processing_at),
                'timestamp' => $this->processing_at,
            ],
            [
                'status' => 'packed',
                'label' => 'Packed',
                'icon' => 'package',
                'completed' => !is_null($this->packed_at),
                'timestamp' => $this->packed_at,
            ],
            [
                'status' => 'shipped',
                'label' => 'Shipped',
                'icon' => 'truck',
                'completed' => !is_null($this->shipped_at),
                'timestamp' => $this->shipped_at,
            ],
            [
                'status' => 'out_for_delivery',
                'label' => 'Out for Delivery',
                'icon' => 'map-pin',
                'completed' => !is_null($this->out_for_delivery_at),
                'timestamp' => $this->out_for_delivery_at,
            ],
            [
                'status' => 'delivered',
                'label' => 'Delivered',
                'icon' => 'check',
                'completed' => !is_null($this->delivered_at),
                'timestamp' => $this->delivered_at,
            ],
        ];
    }

    /**
     * Update delivery status and set timestamp
     */
    public function updateDeliveryStatus($newStatus, $notes = null)
    {
        $this->delivery_status = $newStatus;

        // Set the appropriate timestamp
        $timestampField = $newStatus . '_at';
        if (in_array($timestampField, $this->fillable)) {
            $this->$timestampField = now();
        }

        if ($notes) {
            $this->delivery_notes = $notes;
        }

        $this->save();

        // Create notification for user
        if ($this->user) {
            \App\Models\Notification::create([
                'user_id' => $this->user_id,
                'type' => 'delivery_update',
                'title' => 'Delivery Update',
                'message' => "Your order {$this->invoice_number} is now: {$this->delivery_status_label}",
                'icon' => 'truck',
                'action_url' => route('delivery.track', $this->id),
            ]);
        }

        return $this;
    }
}
