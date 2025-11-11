<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function track($orderId)
    {
        $order = Order::with('user')->findOrFail($orderId);

        // Authorization check
        if (Auth::id() !== $order->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        // Calculate delivery progress based on order status
        $deliveryStages = $this->getDeliveryStages($order);

        // Generate dummy coordinates for animated map
        $routePoints = $this->generateRoutePoints($order);

        return view('delivery-tracking', [
            'order' => $order,
            'deliveryStages' => $deliveryStages,
            'routePoints' => $routePoints,
            'currentStage' => $this->getCurrentStageIndex($order),
        ]);
    }

    private function getDeliveryStages($order)
    {
        $stages = [
            [
                'name' => 'Order Confirmed',
                'icon' => 'check-circle',
                'completed' => true,
                'timestamp' => $order->created_at->format('M d, H:i'),
            ],
            [
                'name' => 'Processing',
                'icon' => 'cog',
                'completed' => in_array($order->status, ['processing', 'picked_up', 'shipped', 'delivered']),
                'timestamp' => $order->processed_at ? $order->processed_at->format('M d, H:i') : null,
            ],
            [
                'name' => 'Picked Up',
                'icon' => 'truck',
                'completed' => in_array($order->status, ['picked_up', 'shipped', 'delivered']),
                'timestamp' => $order->picked_up_at ? $order->picked_up_at->format('M d, H:i') : null,
            ],
            [
                'name' => 'Out for Delivery',
                'icon' => 'delivery',
                'completed' => in_array($order->status, ['shipped', 'delivered']),
                'timestamp' => $order->shipped_at ? $order->shipped_at->format('M d, H:i') : null,
            ],
            [
                'name' => 'Delivered',
                'icon' => 'home',
                'completed' => $order->status === 'delivered',
                'timestamp' => $order->delivered_at ? $order->delivered_at->format('M d, H:i') : null,
            ],
        ];

        return $stages;
    }

    private function getCurrentStageIndex($order)
    {
        switch ($order->status) {
            case 'paid':
            case 'created':
                return 0;
            case 'processing':
                return 1;
            case 'picked_up':
                return 2;
            case 'shipped':
                return 3;
            case 'delivered':
                return 4;
            default:
                return 0;
        }
    }

    private function generateRoutePoints($order)
    {
        // Generate dummy coordinates for visual route
        // In production, these would come from a real delivery API

        $totalStages = 5;
        $currentStage = $this->getCurrentStageIndex($order);

        return [
            'origin' => [
                'lat' => 40.7589, // Dummy warehouse location (NYC)
                'lng' => -73.9851,
                'label' => 'Slice Warehouse',
            ],
            'destination' => [
                'lat' => 40.7128, // Dummy customer location
                'lng' => -74.0060,
                'label' => $order->user->address ?? 'Your Location',
            ],
            'current' => [
                'lat' => 40.7589 - (($currentStage / $totalStages) * 0.0461),
                'lng' => -73.9851 - (($currentStage / $totalStages) * 0.0209),
                'progress' => ($currentStage / $totalStages) * 100,
            ],
            'waypoints' => [
                ['lat' => 40.7489, 'lng' => -73.9680, 'label' => 'Distribution Hub'],
                ['lat' => 40.7389, 'lng' => -73.9900, 'label' => 'Transit Point'],
                ['lat' => 40.7289, 'lng' => -73.9950, 'label' => 'Local Hub'],
            ],
        ];
    }
}
