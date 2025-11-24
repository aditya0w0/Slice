<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'variant_slug' => 'iphone-15-pro',
            'capacity' => '128GB',
            'months' => 12,
            'price_monthly' => 100,
            'total_price' => 1200,
            'status' => 'paid',
            'invoice_number' => Order::generateInvoiceNumber(),
            'delivery_status' => 'pending',
        ];
    }
}
