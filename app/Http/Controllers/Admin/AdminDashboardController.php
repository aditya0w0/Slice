<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! ($user->is_admin ?? false)) {
            abort(403);
        }

        // Get statistics
        $stats = [
            'total_orders' => Order::count(),
            'active_orders' => Order::where('status', 'active')->count(),
            'total_revenue' => Order::sum('total_price'),
            'total_users' => User::count(),
            'total_devices' => Device::count(),
            'pending_orders' => Order::where('status', 'created')->count(),
        ];

        // Recent orders (last 10)
        $recentOrders = Order::with(['user', 'device'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Revenue by month (last 6 months)
        $revenueByMonth = Order::select(
            DB::raw(DB::connection()->getDriverName() === 'sqlite' ? "strftime('%Y-%m', created_at) as month" : "DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('SUM(total_price) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();

        // Top devices by orders
        $topDevices = Order::select('variant_slug', DB::raw('COUNT(*) as order_count'))
            ->groupBy('variant_slug')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'revenueByMonth' => $revenueByMonth,
            'topDevices' => $topDevices,
        ]);
    }
}
