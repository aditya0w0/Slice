<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield("title", "Admin Dashboard") — Slice</title>
        @vite("resources/css/app.css")
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
        ></script>
        @stack("styles")
    </head>
    <body class="bg-[#f5f5f7] antialiased">
        <!-- Admin Navbar - Non-Sticky -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <a
                        href="{{ route("admin.dashboard") }}"
                        class="flex items-center gap-3 group"
                    >
                        <div
                            class="w-8 h-8 rounded-lg bg-linear-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg group-hover:shadow-blue-500/30 transition-all duration-300"
                        >
                            <svg
                                class="w-5 h-5 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        <span
                            class="text-xl font-bold text-gray-900 tracking-tight"
                        >
                            Slice
                            <span class="text-blue-600">.</span>
                        </span>
                    </a>

                    <!-- Notifications -->
                    <div
                        class="flex items-center gap-4"
                        x-data="{ open: false }"
                    >
                        <button
                            @click="open = !open"
                            @click.away="open = false"
                            class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                />
                            </svg>
                            @if (isset($unreadNotifications) && $unreadNotifications > 0)
                                <span
                                    class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"
                                ></span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-6 top-16 mt-2 w-80 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            style="display: none"
                        >
                            <div class="p-3">
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
                                    <h3
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        Notifications
                                    </h3>
                                    @if (isset($unreadNotifications) && $unreadNotifications > 0)
                                        <span
                                            class="text-xs text-blue-600 font-medium"
                                        >
                                            {{ $unreadNotifications }} new
                                        </span>
                                    @endif
                                </div>
                                @if (isset($latestNotifications) && $latestNotifications->count() > 0)
                                    <div
                                        class="space-y-2 max-h-96 overflow-y-auto"
                                    >
                                        @foreach ($latestNotifications as $notif)
                                            <div
                                                class="p-3 rounded-lg {{ $notif->read_at ? "bg-gray-50" : "bg-blue-50" }} hover:bg-gray-100 transition-colors cursor-pointer"
                                            >
                                                <p
                                                    class="text-sm text-gray-900 font-medium"
                                                >
                                                    {{ $notif->data["title"] ?? "Notification" }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-600 mt-1"
                                                >
                                                    {{ $notif->data["message"] ?? "" }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 mt-1"
                                                >
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <a
                                        href="{{ route("admin.notifications.index") }}"
                                        class="block mt-3 text-center text-sm text-blue-600 hover:text-blue-700 font-medium"
                                    >
                                        View All
                                    </a>
                                @else
                                    <p
                                        class="text-sm text-gray-500 text-center py-6"
                                    >
                                        No notifications
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        @yield("content")

        @stack("scripts")
    </body>
</html>
