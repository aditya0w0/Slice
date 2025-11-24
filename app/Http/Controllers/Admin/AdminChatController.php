<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
                    $data = [
                        'id' => $message->id,
                        'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                        'type' => 'text',
                        'content' => $message->message,
                        'time' => $message->created_at->format('g:i A'),
                        'is_read' => $message->is_read,
                    ];

                    // Add attachment data if present
                    if ($message->attachment_url) {
                        $data['attachment'] = [
                            'url' => $message->attachment_url,
                            'type' => $message->attachment_type,
                            'name' => $message->attachment_name,
                            'size' => $message->attachment_size,
                        ];
                    }

                    return $data;
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
                    $data = [
                        'id' => $message->id,
                        'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                        'type' => 'text',
                        'content' => $message->message,
                        'time' => $message->created_at->format('g:i A'),
                        'is_read' => $message->is_read,
                    ];

                    // Add attachment data if present
                    if ($message->attachment_url) {
                        $data['attachment'] = [
                            'url' => $message->attachment_url,
                            'type' => $message->attachment_type,
                            'name' => $message->attachment_name,
                            'size' => $message->attachment_size,
                        ];
                    }

                    return $data;
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
                $data = [
                    'id' => $message->id,
                    'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                    'type' => 'text',
                    'content' => $message->message,
                    'time' => $message->created_at->format('g:i A'),
                    'is_read' => $message->is_read,
                ];

                // Add attachment data if present
                if ($message->attachment_url) {
                    $data['attachment'] = [
                        'url' => $message->attachment_url,
                        'type' => $message->attachment_type,
                        'name' => $message->attachment_name,
                        'size' => $message->attachment_size,
                    ];
                }

                return $data;
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
                    $data = [
                        'id' => $message->id,
                        'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                        'type' => 'text',
                        'content' => $message->message,
                        'time' => $message->created_at->format('g:i A'),
                    ];

                    // Add attachment data if present
                    if ($message->attachment_url) {
                        $data['attachment'] = [
                            'url' => $message->attachment_url,
                            'type' => $message->attachment_type,
                            'name' => $message->attachment_name,
                            'size' => $message->attachment_size,
                        ];
                    }

                    return $data;
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
                $data = [
                    'id' => $message->id,
                    'sender' => $message->sender_type === 'admin' ? 'me' : 'them',
                    'type' => 'text',
                    'content' => $message->message,
                    'time' => $message->created_at->format('g:i A'),
                ];

                // Add attachment data if present
                if ($message->attachment_url) {
                    $data['attachment'] = [
                        'url' => $message->attachment_url,
                        'type' => $message->attachment_type,
                        'name' => $message->attachment_name,
                        'size' => $message->attachment_size,
                    ];
                }

                return $data;
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

    public function uploadFile(Request $request)
    {
        try {
            // Check authentication
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            // Manual validation to ensure JSON responses
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'file' => 'required|file|max:51200|mimes:jpg,jpeg,png,gif,webp,bmp,tiff,svg,pdf,doc,docx,txt',
                'user_id' => 'required|exists:users,id',
                'message' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $validator->errors()->all()),
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            if (!$file || !$file->isValid()) {
                return response()->json(['success' => false, 'message' => 'Invalid file uploaded'], 400);
            }

            $userId = $request->user_id;
            $message = $request->message ?? '';

            // Create directory if it doesn't exist
            $directory = "chat-attachments/{$userId}";
            $fullDirectory = storage_path("app/public/{$directory}");
            if (!file_exists($fullDirectory)) {
                if (!mkdir($fullDirectory, 0777, true)) {
                    return response()->json(['success' => false, 'message' => 'Failed to create upload directory'], 500);
                }
            }

            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $path = "{$directory}/{$filename}";

            // Store file using Storage facade with public disk
            $fileContent = $file->get();
            if ($fileContent === null || $fileContent === false) {
                Log::error('Failed to get file content for: ' . $file->getClientOriginalName());
                return response()->json(['success' => false, 'message' => 'Failed to read uploaded file'], 500);
            }

            Log::info('Storing file to path: ' . $path . ', size: ' . strlen($fileContent));
            $stored = Storage::disk('public')->put($path, $fileContent);
            
            if (!$stored) {
                Log::error('Storage::put failed for path: ' . $path);
                return response()->json(['success' => false, 'message' => 'Failed to store file'], 500);
            }

            Log::info('File stored successfully at: ' . $path);

            // Determine file type
            $mimeType = $file->getMimeType();
            $fileExtension = strtolower($extension);
            
            if (str_contains($mimeType, 'image/') || in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff', 'svg'])) {
                $fileType = 'image';
            } elseif (str_contains($mimeType, 'video/') || in_array($fileExtension, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'])) {
                $fileType = 'video';
            } elseif (str_contains($mimeType, 'pdf') || str_contains($mimeType, 'document') || in_array($fileExtension, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'])) {
                $fileType = 'document';
            } else {
                $fileType = 'file';
            }

            // Create message
            $messageRecord = SupportMessage::create([
                'user_id' => $userId,
                'sender_type' => 'admin',
                'message' => $message,
                'attachment_url' => asset('storage/' . $path),
                'attachment_type' => $fileType,
                'attachment_name' => $originalName,
                'attachment_size' => $file->getSize(),
                'is_read' => false,
            ]);

            if (!$messageRecord) {
                // Clean up the stored file if message creation failed
                Storage::disk('public')->delete($path);
                return response()->json(['success' => false, 'message' => 'Failed to create message record'], 500);
            }

            // Broadcast the message
            Log::info('About to broadcast file message to user: ' . $messageRecord->user_id);
            broadcast(new \App\Events\MessageSent($messageRecord));
            Log::info('Broadcast call completed for file message: ' . $messageRecord->id);

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $messageRecord->id,
                    'sender' => 'me',
                    'type' => 'text',
                    'content' => $message,
                    'attachment' => [
                        'url' => asset('storage/' . $path),
                        'type' => $fileType,
                        'name' => $originalName,
                        'size' => $file->getSize(),
                    ],
                    'time' => $messageRecord->created_at->format('g:i A'),
                ],
            ]);
            
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage(), [
                'file' => $request->file('file')?->getClientOriginalName(),
                'user_id' => $request->user_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMessages(Request $request)
    {
        $user = Auth::user();
        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:support_messages,id'
        ]);

        try {


            // Broadcast event
            // We need the user_id to broadcast to the correct channel. 
            // Assuming all messages belong to the same user (which they should in a chat context),
            // or we can broadcast to the currently active chat user if we know it.
            // However, the request doesn't explicitly send user_id.
            // But since this is AdminChat, we are likely viewing a specific user's chat.
            // Ideally, we should pass user_id in the request or fetch it from one of the messages.
            
            // Let's fetch the user_id from the first message before deleting, or pass it from frontend.
            // For now, let's try to find the user_id from the messages being deleted.
            // Since we already deleted them, we should have fetched it first.
            // Let's adjust the logic to fetch first.
            
            // Re-fetching logic implemented below in a better way:
            $messages = SupportMessage::whereIn('id', $request->message_ids)->get();
            $userId = $messages->first()->user_id ?? null;

            SupportMessage::whereIn('id', $request->message_ids)->delete();

            if ($userId) {
                broadcast(new \App\Events\MessageDeleted($userId, $request->message_ids));
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to delete messages: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete messages'], 500);
        }
    }

    public function deleteConversation($userId)
    {
        $user = Auth::user();
        if (!$user || !($user->is_admin ?? false)) {
            abort(403);
        }

        try {
            // Get all message IDs to broadcast deletion
            $messageIds = SupportMessage::where('user_id', $userId)->pluck('id')->toArray();

            // Delete all messages for this user
            SupportMessage::where('user_id', $userId)->delete();

            if (!empty($messageIds)) {
                broadcast(new \App\Events\MessageDeleted($userId, $messageIds));
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to clear conversation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to clear conversation'], 500);
        }
    }
}
