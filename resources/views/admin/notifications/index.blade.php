<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Notification Management — Admin — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50">
        <main class="mx-auto max-w-7xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="mb-2 inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900"
                >
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Admin Dashboard
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-semibold tracking-tight text-gray-900">Notification Center</h1>
                        <p class="mt-2 text-gray-500">System broadcasting and user alerts</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="sendTestNotification()" class="flex items-center px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-flask mr-2 text-gray-500"></i>
                            Test Channel
                        </button>
                        <a href="{{ route('admin.notifications.create') }}" class="flex items-center px-4 py-2.5 rounded-lg bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Broadcast New
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Total Sent</div>
                    <div class="text-3xl font-bold text-gray-900">{{ count($recentNotifications) }}</div>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Delivery Rate</div>
                    <div class="text-3xl font-bold text-gray-900">99.8%</div>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <div class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Active Channels</div>
                    <div class="text-3xl font-bold text-gray-900">3</div>
                </div>
            </div>

            <!-- Notifications Table -->
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Transmission History</h3>
                            <p class="text-xs text-gray-500 mt-1">Recent alerts dispatched to the fleet</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($recentNotifications as $notification)
                    <div class="group px-6 py-5 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <div class="shrink-0 mt-1">
                                        @switch($notification->type)
                                            @case('info')
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                                                    <i class="fas fa-info text-blue-600"></i>
                                                </div>
                                                @break
                                            @case('warning')
                                                <div class="w-10 h-10 rounded-xl bg-yellow-50 border border-yellow-200 flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                                </div>
                                                @break
                                            @case('success')
                                                <div class="w-10 h-10 rounded-xl bg-green-50 border border-green-200 flex items-center justify-center">
                                                    <i class="fas fa-check-circle text-green-600"></i>
                                                </div>
                                                @break
                                            @case('error')
                                                <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center">
                                                    <i class="fas fa-times-circle text-red-600"></i>
                                                </div>
                                                @break
                                            @default
                                                <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-bell text-gray-600"></i>
                                                </div>
                                        @endswitch
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-base font-semibold text-gray-900 truncate pr-4">{{ $notification->title }}</h4>
                                            <span class="shrink-0 text-xs font-mono text-gray-500">{{ $notification->created_at->format('M d, H:i') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ Str::limit($notification->message, 120) }}</p>

                                        <div class="flex items-center gap-4 mt-3">
                                            <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
                                                <i class="fas fa-user opacity-50"></i>
                                                {{ $notification->user->name ?? 'System Wide' }}
                                            </div>

                                            @if($notification->is_read)
                                                <span class="inline-flex items-center gap-1.5 text-xs text-green-600 font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                    Read
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-xs text-yellow-600 font-medium">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                                    Unread
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-24 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-wind text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No signals detected</h3>
                        <p class="text-gray-500 max-w-sm mx-auto">The communication logs are currently empty. Start by broadcasting your first alert.</p>
                    </div>
                    @endforelse
                </div>

                @if(method_exists($recentNotifications, 'links'))
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $recentNotifications->links() }}
                </div>
                @endif
            </div>
        </main>
    </body>
</html>

<script>
function sendTestNotification() {
    if (confirm('Initiate test transmission to your account?')) {
        const btn = event.currentTarget;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
        btn.disabled = true;

        fetch('{{ route("admin.notifications.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Transmission failed.');
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Transmission error.');
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }
}
</script>
