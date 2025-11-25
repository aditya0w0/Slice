<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     */
    public function index(Request $request)
    {
        // Debug logging
        Log::info('Notification index called', [
            'wantsJson' => $request->wantsJson(),
            'expectsJson' => $request->expectsJson(),
            'ajax' => $request->ajax(),
            'x-requested-with' => $request->header('X-Requested-With'),
            'accept' => $request->header('Accept'),
            'referer' => $request->header('Referer'),
            'user_agent' => $request->header('User-Agent'),
            'query_string' => $request->getQueryString(),
            'user_id' => Auth::id()
        ]);
        // Check if user is authenticated
        if (!Auth::check()) {
            // Check for our custom AJAX headers
            $isAjaxRequest = $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                            str_contains($request->header('Accept', ''), 'application/json');

            if ($request->wantsJson() || $request->expectsJson() || $request->ajax() || $isAjaxRequest) {
                Log::info('User not authenticated, returning empty JSON');
                return response()->json([
                    'notifications' => ['data' => []],
                    'unread_count' => 0,
                ]);
            }
            return redirect()->route('login');
        }

        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        // Check for our custom AJAX headers
        $isAjaxRequest = $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                        str_contains($request->header('Accept', ''), 'application/json');

        // If it's an AJAX request, return JSON
        if ($request->wantsJson() || $request->expectsJson() || $request->ajax() || $isAjaxRequest) {
            Log::info('Returning JSON response', [
                'notifications_count' => $notifications->count(),
                'first_notification' => optional($notifications->first())->toArray(),
            ]);
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);
        }

        Log::info('Returning view response');
        // Otherwise, return view
        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Show a specific notification
     */
    public function show($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Mark as read if not already read
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('notifications.show', [
            'notification' => $notification,
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        Log::info('Unread count called', [
            'user_id' => Auth::id(),
            'is_authenticated' => Auth::check()
        ]);

        if (!Auth::check()) {
            Log::info('User not authenticated for unread count');
            return response()->json([
                'count' => 0,
            ]);
        }

        $countQuery = Notification::where('user_id', Auth::id())
            ->where('is_read', false);
        $count = $countQuery->count();
        $latest = $countQuery->latest()->first();

        Log::info('Unread count result', ['count' => $count, 'latest' => $latest?->toArray()]);

        return response()->json([
            'count' => $count,
            'latest' => $latest ? [
                'id' => $latest->id,
                'title' => $latest->title,
                'message' => $latest->message,
                'created_at' => $latest->created_at,
            ] : null,
        ]);
    }

    /**
     * API index: Always return JSON for notifications listing
     */
    public function apiIndex(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'notifications' => ['data' => []],
                'unread_count' => 0,
            ]);
        }

        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        Log::info('API Notifications index called', [
            'user_id' => Auth::id(),
            'notifications_count' => $notifications->count(),
        ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
        ]);
    }
}
