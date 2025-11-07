<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function start(Request $request)
    {
        $data = $request->validate([
            'variant_slug' => 'required|string',
            'months' => 'required|integer|min:1',
            'capacity' => 'nullable|string',
        ]);

        $device = Device::where('slug', $data['variant_slug'])->first();
        if (!$device) {
            return back()->withErrors(['variant_slug' => 'Selected device variant not found']);
        }

        $priceMonthly = (int) ($device->price_monthly ?? 0);
        $months = (int) $data['months'];
        $total = $priceMonthly * $months;

        $order = Order::create([
            'user_id' => Auth::id(),
            'variant_slug' => $data['variant_slug'],
            'capacity' => $data['capacity'] ?? null,
            'months' => $months,
            'price_monthly' => $priceMonthly,
            'total_price' => $total,
            'status' => 'created',
        ]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Order created');
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        // basic authorization: only owner can view
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }
}
