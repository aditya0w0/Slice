@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                    <p class="text-sm text-gray-600 mt-1">Stay updated with your account activity and important messages</p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    @switch($notification->type)
                                        @case('info')
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-info text-blue-600"></i>
                                            </div>
                                            @break
                                        @case('warning')
                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                            </div>
                                            @break
                                        @case('success')
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check-circle text-green-600"></i>
                                            </div>
                                            @break
                                        @case('error')
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-times-circle text-red-600"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-bell text-gray-600"></i>
                                            </div>
                                    @endswitch
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                            @if(!$notification->is_read)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($notification->message, 150) }}</p>
                                        <p class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 mt-4">
                                    <a href="{{ route('notifications.show', $notification->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors">
                                        <i class="fas fa-eye mr-1.5"></i>View Full
                                    </a>

                                    @if(!$notification->is_read)
                                        <button onclick="markAsRead({{ $notification->id }})" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                            <i class="fas fa-check mr-1.5"></i>Mark Read
                                        </button>
                                    @endif

                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                            <i class="fas fa-external-link-alt mr-1.5"></i>Take Action
                                        </a>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this notification? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                            <i class="fas fa-trash mr-1.5"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No notifications yet</h3>
                <p class="text-gray-600 mb-6">We'll notify you when there are important updates about your account and orders.</p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-home mr-2"></i>Go to Dashboard
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to update the UI
            location.reload();
        } else {
            alert('Failed to mark notification as read.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark notification as read.');
    });
}
</script>
@endsection