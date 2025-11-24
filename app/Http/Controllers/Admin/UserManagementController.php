<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withCount(['orders', 'loginLogs'])
            ->with('latestKyc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'orders' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(3);
            },
            'kycs',
            'loginLogs' => function($query) {
                $query->orderBy('logged_in_at', 'desc')->limit(3);
            }
        ]);

        // Get total counts for View All links
        $totalOrders = $user->orders()->count();
        $totalLoginLogs = $user->loginLogs()->count();

        return view('admin.users.show', compact('user', 'totalOrders', 'totalLoginLogs'));
    }

    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();

        return back()->with('success', $user->is_admin ?
            'User promoted to admin!' :
            'User demoted from admin!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    public function getOrders(User $user)
    {
        $orders = $user->orders()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total_price' => number_format($order->total_price, 2),
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('M d, Y g:i A'),
                ];
            });

        return response()->json(['orders' => $orders]);
    }

    public function getLogins(User $user)
    {
        $logins = $user->loginLogs()
            ->orderBy('logged_in_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'ip_address' => $log->ip_address ?? 'Unknown IP',
                    'user_agent' => $log->user_agent ?? 'Unknown Device',
                    'logged_in_at' => $log->logged_in_at->format('M d, Y g:i A'),
                ];
            });

        return response()->json(['logins' => $logins]);
    }
}
