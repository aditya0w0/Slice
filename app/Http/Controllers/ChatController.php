<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = Auth::id();

        // Get admin/support user
        $adminUser = User::where('is_admin', true)->first();

        if (!$adminUser) {
            abort(500, 'No admin user found');
        }

        // Get all messages for this user (both sent and received)
        $messages = SupportMessage::where('user_id', $currentUserId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin messages as read
        SupportMessage::where('user_id', $currentUserId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Format messages
        $formattedMessages = $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'sender' => $msg->sender_type === 'admin' ? 'them' : 'me',
                'content' => $msg->message,
                'time' => $msg->created_at->format('g:i A'),
                'type' => 'text',
            ];
        })->toArray();

        // Build conversations list (just support for now)
        $lastMessage = $messages->last();
        $unreadCount = SupportMessage::where('user_id', $currentUserId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        $conversations = [[
            'user' => [
                'id' => $adminUser->id,
                'name' => 'Slice Support',
                'avatar' => $adminUser->profile_photo
                    ? asset('storage/' . $adminUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=Support&background=3B82F6&color=fff',
                'online' => true,
            ],
            'last_message' => $lastMessage ? $lastMessage->message : 'Start a conversation',
            'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans(null, true) : '',
            'unread_count' => $unreadCount,
            'active' => true,
        ]];

        $activeChat = [
            'user' => [
                'id' => $adminUser->id,
                'name' => 'Slice Support',
                'status' => 'Online',
                'avatar' => $adminUser->profile_photo
                    ? asset('storage/' . $adminUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=Support&background=3B82F6&color=fff',
                'online' => true,
            ],
            'messages' => $formattedMessages
        ];

        return view('chat.index', compact('conversations', 'activeChat'));
    }

    public function getChatData()
    {
        $currentUserId = Auth::id();

        // Get admin/support user
        $adminUser = User::where('is_admin', true)->first();

        if (!$adminUser) {
            return response()->json(['error' => 'No admin user found'], 500);
        }

        // Get all messages for this user
        $messages = SupportMessage::where('user_id', $currentUserId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin messages as read
        SupportMessage::where('user_id', $currentUserId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Format messages
        $formattedMessages = $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'sender' => $msg->sender_type === 'admin' ? 'them' : 'me',
                'content' => $msg->message,
                'time' => $msg->created_at->format('g:i A'),
                'type' => 'text',
            ];
        })->toArray();

        // Build conversations list
        $lastMessage = $messages->last();
        $unreadCount = SupportMessage::where('user_id', $currentUserId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        $conversations = [[
            'user' => [
                'id' => $adminUser->id,
                'name' => 'Slice Support',
                'avatar' => $adminUser->profile_photo
                    ? asset('storage/' . $adminUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=Support&background=3B82F6&color=fff',
                'online' => true,
            ],
            'last_message' => $lastMessage ? $lastMessage->message : 'Start a conversation',
            'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans(null, true) : '',
            'unread_count' => $unreadCount,
            'active' => true,
        ]];

        $activeChat = [
            'user' => [
                'id' => $adminUser->id,
                'name' => 'Slice Support',
                'status' => 'Online',
                'avatar' => $adminUser->profile_photo
                    ? asset('storage/' . $adminUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=Support&background=3B82F6&color=fff',
                'online' => true,
            ],
            'messages' => $formattedMessages
        ];

        return response()->json([
            'conversations' => $conversations,
            'activeChat' => $activeChat
        ]);
    }

    public function getMessages(Request $request)
    {
        $currentUserId = Auth::id();
        $afterId = $request->query('after');

        if ($afterId) {
            // Only return new messages after the specified ID
            $newMessages = SupportMessage::where('user_id', $currentUserId)
                ->where('id', '>', $afterId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($msg) {
                    return [
                        'id' => $msg->id,
                        'sender' => $msg->sender_type === 'admin' ? 'them' : 'me',
                        'content' => $msg->message,
                        'time' => $msg->created_at->format('g:i A'),
                        'type' => 'text',
                    ];
                });

            $hasNewMessages = $newMessages->isNotEmpty();

            // Mark admin messages as read (only new ones)
            if ($hasNewMessages) {
                SupportMessage::where('user_id', $currentUserId)
                    ->where('sender_type', 'admin')
                    ->where('id', '>', $afterId)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            return response()->json([
                'messages' => $newMessages,
                'hasNewMessages' => $hasNewMessages
            ]);
        }

        // Initial load - return all messages
        $allMessages = SupportMessage::where('user_id', $currentUserId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'sender' => $msg->sender_type === 'admin' ? 'them' : 'me',
                    'content' => $msg->message,
                    'time' => $msg->created_at->format('g:i A'),
                    'type' => 'text',
                ];
            });

        // Mark admin messages as read
        SupportMessage::where('user_id', $currentUserId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $allMessages,
            'hasNewMessages' => false
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = SupportMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'sender_type' => 'user',
            'is_read' => false,
        ]);

        // Broadcast the message
        Log::info('About to broadcast message to user: ' . $message->user_id);
        broadcast(new \App\Events\MessageSent($message));
        Log::info('Broadcast call completed for message: ' . $message->id);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender' => 'me',
                'content' => $message->message,
                'time' => $message->created_at->format('g:i A'),
                'type' => 'text',
            ]
        ]);
    }
}

