<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'device'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'device']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:created,active,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Update delivery status with tracking info
     */
    public function updateDeliveryStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'delivery_status' => 'required|in:pending,processing,packed,shipped,out_for_delivery,delivered',
            'estimated_delivery_date' => 'nullable|date',
            'tracking_number' => 'nullable|string|max:255',
            'courier_name' => 'nullable|string|max:255',
            'courier_phone' => 'nullable|string|max:20',
            'delivery_notes' => 'nullable|string|max:1000',
        ]);

        // Update delivery status with automatic timestamp
        $order->updateDeliveryStatus($validated['delivery_status'], $validated['delivery_notes'] ?? null);

        // Update other delivery fields
        $order->update([
            'estimated_delivery_date' => $validated['estimated_delivery_date'] ?? null,
            'tracking_number' => $validated['tracking_number'] ?? null,
            'courier_name' => $validated['courier_name'] ?? null,
            'courier_phone' => $validated['courier_phone'] ?? null,
        ]);

        return back()->with('success', 'Delivery status updated successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    }
}
