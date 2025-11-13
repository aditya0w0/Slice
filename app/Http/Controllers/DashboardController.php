<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Determine if device is delivered (status is 'delivered' or 'active')
        $isDelivered = $activeOrder && in_array($activeOrder->status, ['delivered', 'active']);

        return view('dashboard-react', [
            'user' => $user,
            'orders' => $orders,
            'activeOrder' => $activeOrder,
            'isDelivered' => $isDelivered,
            'isTrusted' => $user->isTrustedUser(),
        ]);
    }
}
