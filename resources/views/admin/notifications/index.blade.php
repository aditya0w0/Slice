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
                    href="{{ route("admin.dashboard") }}"
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
                        <button
                            onclick="sendTestNotification()"
                            class="flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                        >
                            <i class="fas fa-flask mr-2 text-gray-500"></i>
                            Test Channel
                        </button>
                        <a
                            href="{{ route("admin.notifications.create") }}"
                            class="flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            Broadcast New
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <div class="mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Total Sent</div>
                    <div class="text-3xl font-bold text-gray-900">{{ count($recentNotifications) }}</div>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <div class="mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Delivery Rate</div>
                    <div class="text-3xl font-bold text-gray-900">99.8%</div>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                    <div class="mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Active Channels</div>
                    <div class="text-3xl font-bold text-gray-900">3</div>
                </div>
            </div>

            <!-- Notifications Table -->
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Transmission History</h3>
                            <p class="mt-1 text-xs text-gray-500">Recent alerts dispatched to the fleet</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($recentNotifications as $notification)
                        <div class="group px-6 py-5 transition-colors hover:bg-gray-50">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 shrink-0">
                                            @switch($notification->type)
                                                @case("info")
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-200 bg-blue-50"
                                                    >
                                                        <i class="fas fa-info text-blue-600"></i>
                                                    </div>

                                                    @break
                                                @case("warning")
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-yellow-200 bg-yellow-50"
                                                    >
                                                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                                    </div>

                                                    @break
                                                @case("success")
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-green-200 bg-green-50"
                                                    >
                                                        <i class="fas fa-check-circle text-green-600"></i>
                                                    </div>

                                                    @break
                                                @case("error")
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-200 bg-red-50"
                                                    >
                                                        <i class="fas fa-times-circle text-red-600"></i>
                                                    </div>

                                                    @break
                                                @default
                                                    <div
                                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-gray-50"
                                                    >
                                                        <i class="fas fa-bell text-gray-600"></i>
                                                    </div>
                                            @endswitch
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="truncate pr-4 text-base font-semibold text-gray-900">
                                                    {{ $notification->title }}
                                                </h4>
                                                <span class="shrink-0 font-mono text-xs text-gray-500">
                                                    {{ $notification->created_at->format("M d, H:i") }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-sm leading-relaxed text-gray-600">
                                                {{ Str::limit($notification->message, 120) }}
                                            </p>

                                            <div class="mt-3 flex items-center gap-4">
                                                <div
                                                    class="flex items-center gap-2 rounded-md bg-gray-100 px-2 py-1 text-xs text-gray-500"
                                                >
                                                    <i class="fas fa-user opacity-50"></i>
                                                    {{ $notification->user->name ?? "System Wide" }}
                                                </div>

                                                @if ($notification->is_read)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600"
                                                    >
                                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                        Read
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-yellow-600"
                                                    >
                                                        <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
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
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100"
                            >
                                <i class="fas fa-wind text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="mb-2 text-lg font-medium text-gray-900">No signals detected</h3>
                            <p class="mx-auto max-w-sm text-gray-500">
                                The communication logs are currently empty. Start by broadcasting your first alert.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if (method_exists($recentNotifications, "links"))
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Transmission failed.');
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Transmission error.');
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                });
        }
    }
</script>
