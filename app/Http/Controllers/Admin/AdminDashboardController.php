<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Device;
use App\Models\User;
use App\Models\Notification;
use App\Models\SupportMessage;
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
            'total_notifications' => Notification::count(),
            'unread_notifications' => Notification::where('is_read', false)->count(),
            'total_support_messages' => SupportMessage::count(),
            'unread_support_messages' => SupportMessage::where('sender_type', 'user')->where('is_read', false)->count(),
        ];

        // Recent orders (last 10)
        $recentOrders = Order::with(['user', 'device'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Revenue by month (last 6 months)
        $revenueByMonth = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
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

        // Get latest notifications for dropdown
        $latestNotifications = $user->notifications()->limit(5)->get();
        $unreadNotifications = $user->unreadNotifications()->count();

        return view('admin.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'revenueByMonth' => $revenueByMonth,
            'topDevices' => $topDevices,
            'latestNotifications' => $latestNotifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }
}
