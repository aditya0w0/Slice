<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','variant_slug','capacity','months','quantity','price_monthly','total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
