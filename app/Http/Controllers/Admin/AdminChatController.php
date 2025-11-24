<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        // Get user_id from query parameter if switching conversations
        $requestedUserId = $request->query('user_id');

        // Get all users who have sent support messages
        $contacts = SupportMessage::select('user_id', DB::raw('MAX(created_at) as last_message_at'))
            ->where('sender_type', 'user')
            ->with(['user'])
            ->groupBy('user_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                $lastMessage = SupportMessage::where('user_id', $conversation->user_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $unreadCount = SupportMessage::where('user_id', $conversation->user_id)
                    ->where('sender_type', 'user')
                    ->where('is_read', false)
                    ->count();

                // Convert last_message_at to Carbon instance
                $lastMessageAt = \Carbon\Carbon::parse($conversation->last_message_at);

                return [
                    'id' => $conversation->user_id,
                    'name' => $conversation->user->name ?? 'Unknown User',
                    'avatar' => $conversation->user->profile_photo
                        ? asset('storage/' . $conversation->user->profile_photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($conversation->user->name ?? 'User'),
                    'last_message' => $lastMessage->message ?? '',
                    'time' => $lastMessageAt->diffForHumans(),
                    'unread' => $unreadCount,
                    'status' => 'online',
                    'is_typing' => false,
                ];
            });

        // Determine which user's chat to display
        $activeUserId = $requestedUserId ?? ($contacts->first()['id'] ?? null);

        if ($activeUserId) {
            // Get messages for the active conversation
            $messages = SupportMessage::where('user_id', $activeUserId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                        'type' => 'text',
                        'content' => $message->message,
                        'time' => $message->created_at->format('g:i A'),
                        'is_read' => $message->is_read,
                    ];
                });

            $activeUser = User::find($activeUserId);
            $activeChat = [
                'user' => [
                    'id' => $activeUser->id,
                    'name' => $activeUser->name,
                    'avatar' => $activeUser->profile_photo
                        ? asset('storage/' . $activeUser->profile_photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($activeUser->name),
                    'status' => 'Online',
                ],
                'messages' => $messages,
            ];

            // Mark user messages as read
            SupportMessage::where('user_id', $activeUserId)
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            // No conversations yet
            $activeChat = [
                'user' => [
                    'id' => null,
                    'name' => 'No conversations',
                    'avatar' => 'https://ui-avatars.com/api/?name=No+Conversations',
                    'status' => 'No active chats',
                ],
                'messages' => [],
            ];
        }

        // Mark the active contact
        $contacts = $contacts->map(function ($contact) use ($activeUserId) {
            if ($contact['id'] == $activeUserId) {
                $contact['active'] = true;
            }
            return $contact;
        });

        return view('admin.chat.index', [
            'user' => $user,
            'contacts' => $contacts,
            'activeChat' => $activeChat,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = SupportMessage::create([
            'user_id' => $request->user_id,
            'sender_type' => 'admin',
            'message' => $request->message,
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
                'type' => 'text',
                'content' => $message->message,
                'time' => $message->created_at->format('g:i A'),
            ],
        ]);
    }

    public function getMessages(Request $request, $userId)
    {
        $user = Auth::user();

        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        $afterId = $request->query('after');

        if ($afterId) {
            // Only return new messages after the specified ID
            $newMessages = SupportMessage::where('user_id', $userId)
                ->where('id', '>', $afterId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                        'type' => 'text',
                        'content' => $message->message,
                        'time' => $message->created_at->format('g:i A'),
                        'is_read' => $message->is_read,
                    ];
                });

            $hasNewMessages = $newMessages->isNotEmpty();

            // Mark user messages as read (only new ones)
            if ($hasNewMessages) {
                SupportMessage::where('user_id', $userId)
                    ->where('sender_type', 'user')
                    ->where('id', '>', $afterId)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            return response()->json([
                'success' => true,
                'messages' => $newMessages,
                'hasNewMessages' => $hasNewMessages
            ]);
        }

        // Initial load - return all messages
        $allMessages = SupportMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                    'type' => 'text',
                    'content' => $message->message,
                    'time' => $message->created_at->format('g:i A'),
                    'is_read' => $message->is_read,
                ];
            });

        // Mark user messages as read
        SupportMessage::where('user_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $allMessages,
            'hasNewMessages' => false
        ]);
    }

    public function getChatData(Request $request)
    {
        $user = Auth::user();
        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        $requestedUserId = $request->query('user_id');

        $contacts = SupportMessage::select('user_id', DB::raw('MAX(created_at) as last_message_at'))
            ->where('sender_type', 'user')
            ->with(['user'])
            ->groupBy('user_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                $lastMessage = SupportMessage::where('user_id', $conversation->user_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $unreadCount = SupportMessage::where('user_id', $conversation->user_id)
                    ->where('sender_type', 'user')
                    ->where('is_read', false)
                    ->count();

                $lastMessageAt = \Carbon\Carbon::parse($conversation->last_message_at);

                return [
                    'id' => $conversation->user_id,
                    'name' => $conversation->user->name ?? 'Unknown User',
                    'avatar' => $conversation->user->profile_photo
                        ? asset('storage/' . $conversation->user->profile_photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($conversation->user->name ?? 'User'),
                    'last_message' => $lastMessage->message ?? '',
                    'time' => $lastMessageAt->diffForHumans(),
                    'unread' => $unreadCount,
                    'status' => 'online',
                ];
            });

        $activeUserId = $requestedUserId ?? ($contacts->first()['id'] ?? null);

        if ($activeUserId) {
            $messages = SupportMessage::where('user_id', $activeUserId)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                        'type' => 'text',
                        'content' => $message->message,
                        'time' => $message->created_at->format('g:i A'),
                    ];
                });

            $activeUser = User::find($activeUserId);
            $activeChat = [
                'user' => [
                    'id' => $activeUser->id,
                    'name' => $activeUser->name,
                    'avatar' => $activeUser->profile_photo
                        ? asset('storage/' . $activeUser->profile_photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($activeUser->name),
                    'status' => 'Online',
                ],
                'messages' => $messages,
            ];

            SupportMessage::where('user_id', $activeUserId)
                ->where('sender_type', 'user')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            $activeChat = [
                'user' => [
                    'id' => null,
                    'name' => 'No conversations',
                    'avatar' => 'https://ui-avatars.com/api/?name=No+Conversations',
                    'status' => 'No active chats',
                ],
                'messages' => [],
            ];
        }

        $contacts = $contacts->map(function ($contact) use ($activeUserId) {
            $contact['active'] = $contact['id'] == $activeUserId;
            return $contact;
        });

        return response()->json([
            'contacts' => $contacts,
            'activeChat' => $activeChat,
        ]);
    }

    public function getConversation($userId)
    {
        $user = Auth::user();
        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        $messages = SupportMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                    'type' => 'text',
                    'content' => $message->message,
                    'time' => $message->created_at->format('g:i A'),
                ];
            });

        $activeUser = User::find($userId);
        $activeChat = [
            'user' => [
                'id' => $activeUser->id,
                'name' => $activeUser->name,
                'avatar' => $activeUser->profile_photo
                    ? asset('storage/' . $activeUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($activeUser->name),
                'status' => 'Online',
            ],
            'messages' => $messages,
        ];

        SupportMessage::where('user_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['activeChat' => $activeChat]);
    }
}
