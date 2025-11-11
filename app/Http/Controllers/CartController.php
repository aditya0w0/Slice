<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $items = collect();
        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->get();
        } else {
            // support both numeric-array session carts and keyed-map carts
            $session = session()->get('cart.items', []);
            // if session is a numeric array of items, convert to values; if it's a keyed map, collect its values
            if (is_array($session)) {
                $values = array_values($session);
            } else {
                $values = $session;
            }
            $items = collect($values)->map(function($it){
                return (object) [
                    'variant_slug' => $it['variant_slug'] ?? null,
                    'capacity' => $it['capacity'] ?? null,
                    'months' => $it['months'] ?? 1,
                    'quantity' => $it['quantity'] ?? 1,
                    'price_monthly' => $it['price_monthly'] ?? 0,
                    'total_price' => $it['total_price'] ?? 0,
                ];
            });
        }

        return view('cart.index', compact('items'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_slug' => 'required|string',
            'months' => 'required|integer|min:1',
            'capacity' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1'
        ]);
        $device = Device::where('slug', $data['variant_slug'])->first();
        if (!$device) {
            return response()->json(['error' => 'Variant not found'], 404);
        }

        $qty = $data['quantity'] ?? 1;
        $months = $data['months'];
        $priceMonthly = (int) ($device->price_monthly ?? 0);
        $total = $priceMonthly * $months * $qty;

        $userId = Auth::id();
        if ($userId) {
            // Try to merge with an existing identical cart item (same variant/capacity/months)
            $existing = CartItem::where('user_id', $userId)
                ->where('variant_slug', $data['variant_slug'])
                ->where('capacity', $data['capacity'] ?? null)
                ->where('months', $months)
                ->first();

            if ($existing) {
                $existing->quantity = ($existing->quantity ?? 0) + $qty;
                $existing->total_price = ($existing->price_monthly ?? $priceMonthly) * $existing->months * $existing->quantity;
                $existing->save();
                $item = $existing;
            } else {
                $item = CartItem::create([
                    'user_id' => $userId,
                    'variant_slug' => $data['variant_slug'],
                    'capacity' => $data['capacity'] ?? null,
                    'months' => $months,
                    'quantity' => $qty,
                    'price_monthly' => $priceMonthly,
                    'total_price' => $total,
                ]);
            }

            // return new cart count
            $count = CartItem::where('user_id', $userId)->count();

            return response()->json(['success' => true, 'count' => $count, 'item' => $item]);
        }
        // Guest: use a keyed session cart map for O(1) merges. Key format: variant|capacity|months
        $sessionCart = session()->get('cart.items', []);

        // normalize numeric-array to map if needed
        $cartMap = [];
        if (is_array($sessionCart) && array_values($sessionCart) === $sessionCart) {
            // numeric array
            foreach ($sessionCart as $it) {
                $key = sprintf('%s|%s|%s', $it['variant_slug'] ?? '', $it['capacity'] ?? '', $it['months'] ?? 1);
                $cartMap[$key] = $it;
            }
        } elseif (is_array($sessionCart)) {
            // already a map
            $cartMap = $sessionCart;
        }

        $key = sprintf('%s|%s|%s', $data['variant_slug'], $data['capacity'] ?? '', $months);
        if (isset($cartMap[$key])) {
            $existing = $cartMap[$key];
            $existing['quantity'] = ($existing['quantity'] ?? 1) + $qty;
            $existing['total_price'] = ($existing['price_monthly'] ?? $priceMonthly) * $existing['months'] * $existing['quantity'];
            $cartMap[$key] = $existing;
            $item = $existing;
        } else {
            $entry = [
                'variant_slug' => $data['variant_slug'],
                'capacity' => $data['capacity'] ?? null,
                'months' => $months,
                'quantity' => $qty,
                'price_monthly' => $priceMonthly,
                'total_price' => $total,
                'added_at' => now()->toDateTimeString(),
            ];
            $cartMap[$key] = $entry;
            $item = $entry;
        }

        session()->put('cart.items', $cartMap);

        $count = count($cartMap);

        return response()->json(['success' => true, 'count' => $count, 'item' => $item]);
    }

    public function checkout()
    {
        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->get();
        } else {
            $session = session()->get('cart.items', []);
            $items = collect($session)->map(function($it){
                return (object) [
                    'variant_slug' => $it['variant_slug'] ?? null,
                    'capacity' => $it['capacity'] ?? null,
                    'months' => $it['months'] ?? 1,
                    'quantity' => $it['quantity'] ?? 1,
                    'price_monthly' => $it['price_monthly'] ?? 0,
                    'total_price' => $it['total_price'] ?? 0,
                ];
            });
        }

        // compute totals
        $subtotal = $items->sum('total_price');
        return view('checkout.index', compact('items','subtotal'));
    }

    public function complete(Request $request)
    {
        // For now, create orders for each cart item and clear cart
        $userId = Auth::id();
        $items = CartItem::where('user_id', $userId)->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error','Cart is empty');
        }

        $createdOrderId = null;
        DB::transaction(function () use ($items, $userId, &$createdOrderId) {
            $firstOrderId = null;
            foreach ($items as $ci) {
                $order = \App\Models\Order::create([
                    'user_id' => $userId,
                    'variant_slug' => $ci->variant_slug,
                    'capacity' => $ci->capacity,
                    'months' => $ci->months,
                    'price_monthly' => $ci->price_monthly,
                    'total_price' => $ci->total_price,
                    'status' => 'created',
                ]);

                if (! $firstOrderId) $firstOrderId = $order->id;
            }

            // clear cart
            CartItem::where('user_id', $userId)->delete();

            $createdOrderId = $firstOrderId;
        });

        if (! $createdOrderId) {
            return redirect()->route('cart.index')->with('error','Could not create order');
        }

        return redirect()->route('orders.show', [$createdOrderId])->with('success','Order placed');
    }
}
