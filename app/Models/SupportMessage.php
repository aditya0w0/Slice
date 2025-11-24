<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'notification_id',
        'sender_type',
        'message',
        'is_read',
        'read_at',
        'attachment_url',
        'attachment_type',
        'attachment_name',
        'attachment_size',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    // Scope for getting conversation between user and admin
    public function scopeConversation($query, $userId, $notificationId = null)
    {
        return $query->where('user_id', $userId)
            ->when($notificationId, function ($q) use ($notificationId) {
                return $q->where('notification_id', $notificationId);
            })
            ->orderBy('created_at', 'asc');
    }
}
