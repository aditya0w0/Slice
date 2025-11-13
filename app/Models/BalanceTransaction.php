<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'payment_method',
        'bank',
        'virtual_account',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate dynamic Virtual Account number based on bank
     */
    public static function generateVirtualAccount($bank, $userId)
    {
        $prefix = match($bank) {
            'bca' => '7088',
            'mandiri' => '8808',
            'bni' => '8810',
            'bri' => '26215',
            default => '0000',
        };

        // Add user ID padded to 10 digits
        $userPart = str_pad($userId, 10, '0', STR_PAD_LEFT);

        return $prefix . $userPart;
    }
}
