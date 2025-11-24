<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    Log::info('Channel authorization check for chat.' . $userId . ' by user ' . $user->id . ' (admin: ' . ($user->is_admin ? 'yes' : 'no') . ')');
    $allowed = (int) $user->id === (int) $userId || $user->is_admin;
    Log::info('Channel authorization result: ' . ($allowed ? 'ALLOWED' : 'DENIED'));
    return $allowed;
});
