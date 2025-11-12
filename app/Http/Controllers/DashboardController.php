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

        return view('dashboard-react', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}
