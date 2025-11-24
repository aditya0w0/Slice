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
        Log::info('MessageSent broadcastOn called for user: ' . $this->message->user_id . ', channel: chat.' . $this->message->user_id);
        return [
            new PrivateChannel('chat.' . $this->message->user_id),
        ];
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
        return [
            'id' => $this->message->id,
            'sender' => $this->message->sender_type === 'admin' ? 'them' : 'me',
            'content' => $this->message->message,
            'time' => $this->message->created_at->format('g:i A'),
            'type' => 'text',
        ];
    }
}
