<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class MergeSessionCart
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (!$user) return;

        $sessionItems = session()->get('cart.items', []);
        if (empty($sessionItems) || !is_array($sessionItems)) {
            return;
        }

        // normalize session cart: support numeric array or keyed map
        // if numeric array (sequential), convert to keyed map by composite key
        $normalized = [];
        $isSequential = array_values($sessionItems) === $sessionItems;
        if ($isSequential) {
            foreach ($sessionItems as $it) {
                $k = sprintf('%s|%s|%s', $it['variant_slug'] ?? '', $it['capacity'] ?? '', $it['months'] ?? 1);
                $normalized[$k] = $it;
            }
        } else {
            $normalized = $sessionItems;
        }

        // batch existing items to avoid N+1 queries
        $userId = $user->getAuthIdentifier();
        $existingItems = CartItem::where('user_id', $userId)->get()
            ->keyBy(function ($it) {
                return sprintf('%s|%s|%s', $it->variant_slug, $it->capacity, $it->months);
            });

        $toInsert = [];
        $toUpdate = [];

        foreach ($normalized as $s) {
            $variant = $s['variant_slug'] ?? null;
            $capacity = $s['capacity'] ?? null;
            $months = $s['months'] ?? 1;
            $qty = $s['quantity'] ?? 1;
            $priceMonthly = $s['price_monthly'] ?? 0;
            $total = $s['total_price'] ?? ($priceMonthly * $months * $qty);

            if (!$variant) continue;

            $key = sprintf('%s|%s|%s', $variant, $capacity, $months);

            if (isset($existingItems[$key])) {
                $existing = $existingItems[$key];
                $existing->quantity = ($existing->quantity ?? 0) + $qty;
                $existing->total_price = ($existing->price_monthly ?? 0) * $existing->months * $existing->quantity;
                $toUpdate[$existing->id] = $existing;
            } else {
                $toInsert[] = [
                    'user_id' => $userId,
                    'variant_slug' => $variant,
                    'capacity' => $capacity,
                    'months' => $months,
                    'quantity' => $qty,
                    'price_monthly' => $priceMonthly,
                    'total_price' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // perform DB writes: bulk insert new rows and save updated models
        if (!empty($toInsert)) {
            DB::table('cart_items')->insert($toInsert);
        }

        foreach ($toUpdate as $u) {
            $u->save();
        }

        // clear session cart
        session()->forget('cart.items');
    }
}
