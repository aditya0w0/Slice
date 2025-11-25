<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Redirect admins to admin dashboard
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Fetch recent orders for the current user (most recent first)
        $orders = Order::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->limit(12)
                       ->get();

        // Get the most recent active rental order to determine delivery status
        $activeOrder = Order::where('user_id', $user->id)
                            ->whereIn('status', ['paid', 'processing', 'picked_up', 'shipped', 'delivered', 'active'])
                            ->orderBy('created_at', 'desc')
                            ->first();

        // Determine if device is currently being delivered (NOT yet received)
        // Show delivery tracking ONLY for in-transit orders
        $isDelivering = $activeOrder && in_array($activeOrder->status, ['paid', 'processing', 'picked_up', 'shipped']);
        
        // Device is delivered/active = show as Active rental
        $isDelivered = $activeOrder && in_array($activeOrder->status, ['delivered', 'active']);

        // Get user location for map display
        $locationData = $this->getUserLocation($request);

        return view('dashboard.react', [
            'user' => $user,
            'orders' => $orders,
            'activeOrder' => $activeOrder,
            'isDelivering' => $isDelivering,  // This is what the React component expects!
            'isDelivered' => $isDelivered,
            'hasOrders' => $orders->count() > 0,
            'isTrusted' => $user->kyc_verified ?? false,
            'locationData' => $locationData,
        ]);
    }

    private function getUserLocation(Request $request)
    {
        try {
            // Try to get location from IP
            $ip = $request->ip();

            // Skip for local development
            if ($ip === '127.0.0.1' || $ip === '::1') {
                return [
                    'city' => 'Local Development',
                    'country' => 'Development',
                    'latitude' => null,
                    'longitude' => null,
                ];
            }

            // Use a more reliable geolocation service
            $response = \Illuminate\Support\Facades\Http::timeout(5)
                ->get("http://ip-api.com/json/{$ip}");

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === 'success') {
                    return [
                        'city' => $data['city'] ?? 'Unknown',
                        'country' => $data['country'] ?? 'Unknown',
                        'latitude' => $data['lat'] ?? null,
                        'longitude' => $data['lon'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::info('Location detection failed: ' . $e->getMessage());
        }

        // Fallback
        return [
            'city' => 'Location unavailable',
            'country' => '',
            'latitude' => null,
            'longitude' => null,
        ];
    }
}
