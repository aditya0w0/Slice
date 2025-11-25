<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\SupportMessage;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(SupportMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.' . $this->message->user_id),
        ];

        // If this is a user-to-user message (receiver_id is set), also broadcast to receiver
        if ($this->message->receiver_id) {
            $channels[] = new PrivateChannel('chat.' . $this->message->receiver_id);
            Log::info('MessageSent broadcasting to both channels: chat.' . $this->message->user_id . ' and chat.' . $this->message->receiver_id);
        } else {
            Log::info('MessageSent broadcasting to user channel: chat.' . $this->message->user_id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        Log::info('MessageSent broadcastWith called, sending data for message ID: ' . $this->message->id);

        // For user-to-user messages, sender is always user_id
        // For admin messages, sender_type is 'admin' and we need admin's ID
        $senderId = $this->message->sender_type === 'user'
            ? $this->message->user_id
            : null; // For admin messages, we don't track admin ID in user_id field

        $data = [
            'id' => $this->message->id,
            'sender_id' => $senderId,
            'receiver_id' => $this->message->receiver_id,
            'sender_type' => $this->message->sender_type, // Send actual type, let frontend decide me/them
            'content' => $this->message->message,
            'time' => $this->message->created_at->format('g:i A'),
            'type' => 'text',
        ];

        // Add attachment data if present
        if ($this->message->attachment_url) {
            $data['type'] = $this->message->attachment_type ?? 'file';
            $data['attachment'] = [
                'url' => asset('storage/' . $this->message->attachment_url),
                'type' => $this->message->attachment_type,
                'name' => $this->message->attachment_name,
                'size' => $this->message->attachment_size,
            ];
        }

        return $data;
    }
}
