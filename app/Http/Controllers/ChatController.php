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
            'last_message' => $lastMessage ? $lastMessage->message : '',
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

        // Get admin/support user, or create a virtual one if none exists
        $adminUser = User::where('is_admin', true)->first();

        // If no admin exists, create a virtual support user object (not saved to DB)
        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->id = 0;
            $adminUser->name = 'Slice Support';
            $adminUser->email = 'support@slice.com';
            $adminUser->profile_photo = null;
        }

        // Get all messages where current user is sender or receiver
        // For support messages: user_id = currentUserId AND receiver_id IS NULL
        // For user-to-user: (user_id = currentUserId AND receiver_id IS NOT NULL) OR receiver_id = currentUserId
        $messagesQuery = SupportMessage::where(function($query) use ($currentUserId) {
            // Support messages (to/from admin)
            $query->where('user_id', $currentUserId)
                  ->whereNull('receiver_id');
        })->orWhere(function($query) use ($currentUserId) {
            // User-to-user messages where I'm the sender
            $query->where('user_id', $currentUserId)
                  ->whereNotNull('receiver_id');
        })->orWhere('receiver_id', $currentUserId) // User-to-user where I'm the receiver
            ->orderBy('created_at', 'asc');

        $messages = $messagesQuery->get();

        // Mark unread messages as read for support chat
        SupportMessage::where('user_id', $currentUserId)
            ->whereNull('receiver_id')
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Format messages
        $formattedMessages = $messages->map(function($msg) use ($currentUserId) {
            // Determine sender based on message type and current user
            $isMine = false;

            if ($msg->receiver_id === null) {
                // Support message (user <-> admin)
                // If I'm the user_id and sender_type is 'user', it's mine
                $isMine = ($msg->user_id == $currentUserId && $msg->sender_type === 'user');
            } else {
                // User-to-user message
                // It's mine if I'm the sender (user_id = me) OR if I'm the receiver and sender_type is admin
                $isMine = ($msg->user_id == $currentUserId && $msg->sender_type === 'user');
            }

            $data = [
                'id' => $msg->id,
                'sender' => $isMine ? 'me' : 'them',
                'content' => $msg->message,
                'time' => $msg->created_at->format('g:i A'),
                'type' => 'text',
            ];

            // Add attachment data if present
            if ($msg->attachment_url) {
                $data['type'] = $msg->attachment_type ?? 'file';
                $data['attachment'] = [
                    'url' => asset('storage/' . $msg->attachment_url),
                    'type' => $msg->attachment_type,
                    'name' => $msg->attachment_name,
                    'size' => $msg->attachment_size,
                ];
            }

            return $data;
        });

        // Build conversations list including both support and user-to-user
        $conversations = [];

        // Add support conversation
        $supportMessages = $messages->where('receiver_id', null);
        $supportFormattedMessages = $formattedMessages->filter(function($msg, $key) use ($messages) {
            return $messages[$key]->receiver_id === null;
        })->values();
        $lastSupportMessage = $supportMessages->last();
        $unreadCount = SupportMessage::where('user_id', $currentUserId)
            ->whereNull('receiver_id')
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        $conversations[] = [
            'user' => [
                'id' => $adminUser->id,
                'name' => 'Slice Support',
                'email' => 'support@slice.com',
                'avatar' => $adminUser->profile_photo
                    ? asset('storage/' . $adminUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=Support&background=3B82F6&color=fff',
                'online' => true,
                'is_admin' => true,
            ],
            'last_message' => $lastSupportMessage ? $lastSupportMessage->message : '',
            'last_message_time' => $lastSupportMessage ? $lastSupportMessage->created_at->diffForHumans(null, true) : '',
            'unread_count' => $unreadCount,
            'active' => true,
        ];

        // Add user-to-user conversations
        $userToUserMessages = $messages->where('receiver_id', '!=', null);
        $conversationPartners = [];

        foreach ($userToUserMessages as $msg) {
            $partnerId = $msg->user_id == $currentUserId ? $msg->receiver_id : $msg->user_id;
            if (!isset($conversationPartners[$partnerId])) {
                $conversationPartners[$partnerId] = $msg;
            } else {
                // Keep the latest message
                if ($msg->created_at > $conversationPartners[$partnerId]->created_at) {
                    $conversationPartners[$partnerId] = $msg;
                }
            }
        }

        foreach ($conversationPartners as $partnerId => $lastMsg) {
            $partner = User::find($partnerId);
            if ($partner) {
                $conversations[] = [
                    'user' => [
                        'id' => $partner->id,
                        'name' => $partner->name,
                        'email' => $partner->email,
                        'avatar' => $partner->profile_photo
                            ? asset('storage/' . $partner->profile_photo)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($partner->name) . '&background=0D8ABC&color=fff',
                        'online' => false,
                        'is_admin' => false,
                    ],
                    'last_message' => $lastMsg->message,
                    'last_message_time' => $lastMsg->created_at->diffForHumans(null, true),
                    'unread_count' => 0,
                    'active' => false,
                ];
            }
        }

        $activeChat = [
            'user' => [
                'id' => $adminUser->id,
                'name' => 'Slice Support',
                'email' => 'support@slice.com',
                'status' => 'Online',
                'avatar' => $adminUser->profile_photo
                    ? asset('storage/' . $adminUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=Support&background=3B82F6&color=fff',
                'online' => true,
                'is_admin' => true,
            ],
            'messages' => $supportFormattedMessages->toArray()
        ];

        return response()->json([
            'conversations' => $conversations,
            'activeChat' => $activeChat
        ]);
    }

    public function getConversation($userId)
    {
        $currentUserId = Auth::id();
        $targetUserId = (int) $userId;

        // Handle special case: ID 0 means virtual admin/support
        if ($targetUserId === 0) {
            // Find actual admin user
            $targetUser = User::where('is_admin', true)->first();
            if (!$targetUser) {
                // Create virtual admin for response
                $targetUser = new User();
                $targetUser->id = 0;
                $targetUser->name = 'Slice Support';
                $targetUser->email = 'support@slice.com';
                $targetUser->is_admin = true;
            }
            $isAdminChat = true;
        } else {
            // Regular user lookup
            $targetUser = User::find($targetUserId);
            if (!$targetUser) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $isAdminChat = $targetUser->is_admin ?? false;
        }

        // Fetch messages for this specific conversation
        if ($isAdminChat) {
            // Support messages (receiver_id is null)
            $messages = SupportMessage::where('user_id', $currentUserId)
                ->whereNull('receiver_id')
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            // User-to-user messages (where I'm sender or receiver with this specific user)
            $messages = SupportMessage::where(function($query) use ($currentUserId, $targetUserId) {
                // I sent to them
                $query->where('user_id', $currentUserId)
                      ->where('receiver_id', $targetUserId);
            })->orWhere(function($query) use ($currentUserId, $targetUserId) {
                // They sent to me
                $query->where('user_id', $targetUserId)
                      ->where('receiver_id', $currentUserId);
            })->orderBy('created_at', 'asc')
              ->get();
        }

        // Format messages
        $formattedMessages = $messages->map(function($msg) use ($currentUserId) {
            $isMine = false;

            if ($msg->receiver_id === null) {
                // Support message
                $isMine = ($msg->user_id == $currentUserId && $msg->sender_type === 'user');
            } else {
                // User-to-user message
                $isMine = ($msg->user_id == $currentUserId && $msg->sender_type === 'user');
            }

            $data = [
                'id' => $msg->id,
                'sender' => $isMine ? 'me' : 'them',
                'content' => $msg->message,
                'time' => $msg->created_at->format('g:i A'),
                'type' => 'text',
            ];

            if ($msg->attachment_url) {
                $data['type'] = $msg->attachment_type ?? 'file';
                $data['attachment'] = [
                    'url' => asset('storage/' . $msg->attachment_url),
                    'type' => $msg->attachment_type,
                    'name' => $msg->attachment_name,
                    'size' => $msg->attachment_size,
                ];
            }

            return $data;
        })->toArray();

        $activeChat = [
            'user' => [
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'email' => $targetUser->email ?? 'support@slice.com',
                'status' => $targetUser->is_admin ? 'Online' : 'offline',
                'avatar' => $targetUser->profile_photo
                    ? asset('storage/' . $targetUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($targetUser->name ?: 'Support') . '&background=' . ($targetUser->is_admin ? '3B82F6' : '0D8ABC') . '&color=fff',
                'online' => $targetUser->is_admin ?? false,
                'is_admin' => $targetUser->is_admin ?? false,
            ],
            'messages' => $formattedMessages
        ];

        return response()->json([
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
                    $data = [
                        'id' => $msg->id,
                        'sender' => $msg->sender_type === 'admin' ? 'them' : 'me',
                        'content' => $msg->message,
                        'time' => $msg->created_at->format('g:i A'),
                        'type' => 'text',
                    ];

                    // Add attachment data if present
                    if ($msg->attachment_url) {
                        $data['attachment'] = [
                            'url' => asset('storage/' . $msg->attachment_url),
                            'type' => $msg->attachment_type,
                            'name' => $msg->attachment_name,
                            'size' => $msg->attachment_size,
                        ];
                    }

                    return $data;
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
                $data = [
                    'id' => $msg->id,
                    'sender' => $msg->sender_type === 'admin' ? 'them' : 'me',
                    'content' => $msg->message,
                    'time' => $msg->created_at->format('g:i A'),
                    'type' => 'text',
                ];

                // Add attachment data if present
                if ($msg->attachment_url) {
                    $data['attachment'] = [
                        'url' => asset('storage/' . $msg->attachment_url),
                        'type' => $msg->attachment_type,
                        'name' => $msg->attachment_name,
                        'size' => $msg->attachment_size,
                    ];
                }

                return $data;
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
            'user_id' => 'nullable|exists:users,id', // receiver for user-to-user chat
        ]);

        $currentUserId = Auth::id();
        $receiverId = $request->user_id;

        // Sanitize message to prevent XSS attacks
        $sanitizedMessage = htmlspecialchars(strip_tags($request->message), ENT_QUOTES, 'UTF-8');

        $message = SupportMessage::create([
            'user_id' => $currentUserId,
            'message' => $sanitizedMessage,
            'sender_type' => 'user',
            'is_read' => false,
        ]);

        // Broadcast the message to both participants
        Log::info('About to broadcast message from user: ' . $currentUserId . ' to: ' . ($receiverId ?? 'admin'));
        broadcast(new \App\Events\MessageSent($message));

        // If this is user-to-user, also broadcast to the receiver
        if ($receiverId) {
            // The receiver sees this from their channel
            Log::info('Broadcasting to receiver channel: chat.' . $receiverId);
        }

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

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'message' => 'nullable|string|max:1000',
            'user_id' => 'nullable|exists:users,id', // receiver for user-to-user chat
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('chat-uploads', 'public');

            $mimeType = $file->getMimeType();
            $fileExtension = strtolower($file->getClientOriginalExtension());
            
            if (str_starts_with($mimeType, 'image/') || in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff'])) {
                $attachmentType = 'image';
            } elseif (str_starts_with($mimeType, 'video/') || in_array($fileExtension, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'])) {
                $attachmentType = 'video';
            } elseif (str_contains($mimeType, 'pdf') || str_contains($mimeType, 'document') || in_array($fileExtension, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'])) {
                $attachmentType = 'document';
            } else {
                $attachmentType = 'file';
            }

            $message = SupportMessage::create([
                'user_id' => Auth::id(),
                'receiver_id' => $request->user_id,
                'message' => $request->message ?? '',
                'sender_type' => 'user',
                'is_read' => false,
                'attachment_url' => $path,
                'attachment_type' => $attachmentType,
                'attachment_name' => $file->getClientOriginalName(),
                'attachment_size' => $file->getSize(),
            ]);

            // Broadcast the message
            // DISABLED: Causes duplicate messages for sender since they get it via HTTP response
            // broadcast(new \App\Events\MessageSent($message));

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'sender' => 'me',
                    'content' => '', // Empty - caption in attachment now
                    'time' => $message->created_at->format('g:i A'),
                    'type' => 'text', // Always 'text' - attachment determines rendering
                    'attachment' => [
                        'url' => asset('storage/' . $path),
                        'type' => $attachmentType,
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'caption' => $request->message ?? '', // Caption here
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMessages(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:support_messages,id'
        ]);

        try {
            $currentUserId = Auth::id();

            // Fetch messages to verify ownership and get user_id for broadcast
            $messages = SupportMessage::whereIn('id', $request->message_ids)
                ->where('user_id', $currentUserId)
                ->where('sender_type', 'user') // Users can only delete their own sent messages
                ->get();

            if ($messages->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No messages found or you do not have permission to delete these messages'
                ], 403);
            }

            $messageIds = $messages->pluck('id')->toArray();

            // Delete the messages
            SupportMessage::whereIn('id', $messageIds)->delete();

            // Broadcast deletion event
            broadcast(new \App\Events\MessageDeleted($currentUserId, $messageIds));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to delete messages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete messages'
            ], 500);
        }
    }

    public function deleteConversation()
    {
        try {
            $currentUserId = Auth::id();

            // Get all message IDs to broadcast deletion
            $messageIds = SupportMessage::where('user_id', $currentUserId)->pluck('id')->toArray();

            // Delete all messages for this user
            SupportMessage::where('user_id', $currentUserId)->delete();

            if (!empty($messageIds)) {
                broadcast(new \App\Events\MessageDeleted($currentUserId, $messageIds));
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to clear conversation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear conversation'
            ], 500);
        }
    }
}
