<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationManagementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        // Get recent notifications sent by admin
        $recentNotifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.notifications.index', [
            'user' => $user,
            'recentNotifications' => $recentNotifications,
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        // Get user stats for the form
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('kyc_verified', true)->count(),
            'unverified_users' => User::where('kyc_verified', false)->count(),
        ];

        return view('admin.notifications.create', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'action_url' => 'nullable|url',
            'target_users' => 'required|in:all,verified,unverified',
            'notification_type' => 'required|in:info,warning,success,error',
        ]);

        // Determine which users to send to
        $query = User::query();

        switch ($request->target_users) {
            case 'verified':
                $query->where('kyc_verified', true);
                break;
            case 'unverified':
                $query->where('kyc_verified', false);
                break;
            case 'all':
            default:
                // Send to all users
                break;
        }

        $users = $query->get();

        // Create notifications for each user (avoid duplicates)
        $notificationCount = 0;
        foreach ($users as $targetUser) {
            // Check if this exact notification was already sent to this user recently (within last hour)
            $existingNotification = Notification::where('user_id', $targetUser->id)
                ->where('title', $request->title)
                ->where('message', $request->message)
                ->where('created_at', '>=', now()->subHour())
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $targetUser->id,
                    'type' => $request->notification_type,
                    'title' => $request->title,
                    'message' => $request->message,
                    'action_url' => $request->action_url,
                    'icon' => $this->getIconForType($request->notification_type),
                    'is_read' => false,
                ]);
                $notificationCount++;
            }
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', "Notification sent to {$notificationCount} users successfully!");
    }

    public function sendTestNotification(Request $request)
    {
        $user = Auth::user();

        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        // Send a test notification to the admin user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'info',
            'title' => 'Test Notification',
            'message' => 'This is a test notification to verify the notification system is working.',
            'action_url' => null,
            'icon' => 'bell',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test notification sent!',
        ]);
    }

    private function getIconForType($type)
    {
        $icons = [
            'info' => 'info',
            'warning' => 'alert-triangle',
            'success' => 'check-circle',
            'error' => 'x-circle',
        ];

        return $icons[$type] ?? 'bell';
    }
}
