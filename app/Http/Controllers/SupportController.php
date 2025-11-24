<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Get support messages for a conversation
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'notification_id' => 'nullable|exists:notifications,id',
        ]);

        $messages = SupportMessage::conversation(
            Auth::id(),
            $request->notification_id
        )->get();

        return response()->json([
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at,
                    'formatted_time' => $message->created_at->format('g:i A'),
                ];
            }),
        ]);
    }

    /**
     * Send a support message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'notification_id' => 'nullable|exists:notifications,id',
        ]);

        // Verify the notification belongs to the user if provided
        if ($request->notification_id) {
            $notification = Notification::where('id', $request->notification_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }
        }

        $message = SupportMessage::create([
            'user_id' => Auth::id(),
            'notification_id' => $request->notification_id,
            'sender_type' => 'user',
            'message' => $request->message,
            'is_read' => false,
        ]);

        // In a real application, you would trigger notifications to admin/support staff here
        // For now, we'll simulate an auto-response

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'nullable|exists:notifications,id',
        ]);

        SupportMessage::conversation(Auth::id(), $request->notification_id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read.',
        ]);
    }

    /**
     * Get unread message count
     */
    public function unreadCount(Request $request)
    {
        $request->validate([
            'notification_id' => 'nullable|exists:notifications,id',
        ]);

        $count = SupportMessage::conversation(Auth::id(), $request->notification_id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'count' => $count,
        ]);
    }
}
