<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function track(Order $order)
    {
        // Authorization check
        if (Auth::id() !== $order->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        return view('delivery-tracking', [
            'order' => $order,
            'timeline' => $order->delivery_timeline,
            'currentStageIndex' => $this->getCurrentStageIndex($order),
        ]);
    }

    private function getCurrentStageIndex($order)
    {
        $statuses = ['pending', 'processing', 'packed', 'shipped', 'out_for_delivery', 'delivered'];
        $currentIndex = array_search($order->delivery_status, $statuses);
        return $currentIndex !== false ? $currentIndex : 0;
    }

    public function findDevice(Order $order)
    {
        // Authorization check
        if (Auth::id() !== $order->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        // Get device from order
        $device = $order->device;

        if (!$device) {
            abort(404, 'Device not found');
        }

        // Set default coordinates if not available
        if (!$order->delivery_latitude || !$order->delivery_longitude) {
            // Default to Jakarta, Indonesia
            $order->delivery_latitude = -6.2088;
            $order->delivery_longitude = 106.8456;
        }

        return view('find-device', [
            'order' => $order,
            'device' => $device,
        ]);
    }
}
