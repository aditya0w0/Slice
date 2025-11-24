<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Admin Dashboard — Slice</title>
        @vite("resources/css/app.css")
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
        ></script>
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
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg group-hover:shadow-blue-500/30 transition-all duration-300"
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
                        class="relative flex items-center gap-4"
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
                            @if ($unreadNotifications > 0)
                                <span
                                    class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border border-white"
                                ></span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div
                            x-show="open"
                            x-transition:enter="transition duration-100 ease-out"
                            x-transition:enter-start="scale-95 transform opacity-0"
                            x-transition:enter-end="scale-100 transform opacity-100"
                            x-transition:leave="transition duration-75 ease-in"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 top-full mt-2 w-80 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-gray-200 focus:outline-none z-50"
                            style="display: none"
                        >
                            <div class="p-4">
                                <div
                                    class="flex items-center justify-between mb-4"
                                >
                                    <h3
                                        class="text-lg font-semibold text-gray-900"
                                    >
                                        Notifications
                                    </h3>
                                    @if ($unreadNotifications > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            {{ $unreadNotifications }} new
                                        </span>
                                    @endif
                                </div>
                                @if ($latestNotifications->count() > 0)
                                    <div
                                        class="space-y-3 max-h-80 overflow-y-auto"
                                    >
                                        @foreach ($latestNotifications as $notif)
                                            <div
                                                class="p-3 rounded-lg {{ $notif->read_at ? "border border-gray-100 bg-gray-50" : "border border-blue-200 bg-blue-50" }} hover:bg-gray-100 transition-colors cursor-pointer"
                                            >
                                                <div
                                                    class="flex items-start justify-between"
                                                >
                                                    <div class="flex-1 min-w-0">
                                                        <p
                                                            class="text-sm font-medium text-gray-900 truncate"
                                                        >
                                                            {{ $notif->data["title"] ?? "Notification" }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-600 mt-1 line-clamp-2"
                                                        >
                                                            {{ $notif->data["message"] ?? "" }}
                                                        </p>
                                                        <p
                                                            class="text-xs text-gray-500 mt-2"
                                                        >
                                                            {{ $notif->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    @if (! $notif->read_at)
                                                        <div
                                                            class="ml-2 flex-shrink-0"
                                                        >
                                                            <div
                                                                class="w-2 h-2 bg-blue-500 rounded-full"
                                                            ></div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div
                                        class="mt-4 pt-3 border-t border-gray-200"
                                    >
                                        <a
                                            href="{{ route("admin.notifications.index") }}"
                                            class="block w-full text-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                        >
                                            View All Notifications
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-400"
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
                                        <p class="text-sm text-gray-500 mt-2">
                                            No notifications yet
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-6 py-8">
            <!-- Admin Header with Avatar -->
            <div class="mb-6 flex items-start justify-between">
                <div class="flex-1">
                    <div class="mb-2 flex items-center gap-2">
                        <div
                            class="flex h-6 w-6 items-center justify-center rounded-md bg-gray-900"
                        >
                            <svg
                                class="h-4 w-4 text-white"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-600">
                            Admin Dashboard
                        </span>
                    </div>
                    <h1
                        class="text-4xl font-semibold tracking-tight text-gray-900"
                    >
                        Overview
                    </h1>
                    <p class="mt-1 text-gray-600">
                        Welcome back, {{ $user->name }}
                    </p>
                </div>

                <!-- User Avatar & Info -->
                <div
                    class="flex items-center gap-4"
                    x-data="{ open: false }"
                >
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <div class="relative">
                        <button
                            @click="open = !open"
                            @click.away="open = false"
                            class="relative"
                        >
                            @php
                                if ($user->profile_photo) {
                                    $avatar = \Storage::url($user->profile_photo);
                                } else {
                                    $hash = md5(strtolower(trim($user->email)));
                                    $avatar = "https://www.gravatar.com/avatar/{$hash}?s=48&d=identicon";
                                }
                            @endphp

                            <img
                                class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-200 hover:ring-gray-300 transition-all"
                                src="{{ $avatar }}"
                                alt="{{ $user->name }}"
                            />
                            @if ($user->is_admin)
                                <div
                                    class="absolute -bottom-0.5 -right-0.5 h-4 w-4 rounded-full bg-purple-500 ring-2 ring-white flex items-center justify-center"
                                >
                                    <svg
                                        class="w-2.5 h-2.5 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            @endif
                        </button>

                        <!-- User Dropdown -->
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            style="display: none"
                        >
                            <div class="p-2">
                                <a
                                    href="{{ route("dashboard") }}"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <svg
                                        class="w-4 h-4 text-gray-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                        />
                                    </svg>
                                    User Dashboard
                                </a>
                                <a
                                    href="{{ route("admin.users.index") }}"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <svg
                                        class="w-4 h-4 text-gray-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                        />
                                    </svg>
                                    Manage Users
                                </a>
                                <a
                                    href="{{ route("admin.chat.index") }}"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <svg
                                        class="w-4 h-4 text-gray-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                        />
                                    </svg>
                                    Support Chat
                                </a>
                                <div
                                    class="border-t border-gray-200 my-2"
                                ></div>
                                <form
                                    method="POST"
                                    action="{{ route("logout") }}"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-3 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                            />
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid - Apple Style -->
            <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Revenue -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Total Revenue
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                ${{ number_format($stats["total_revenue"], 2) }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">All time</p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Total Orders
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                {{ $stats["total_orders"] }}
                            </p>
                            <p class="mt-1 text-xs text-blue-600">
                                {{ $stats["active_orders"] }} active
                            </p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Total Users
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                {{ $stats["total_users"] }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">Registered</p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Devices -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Total Devices
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                {{ $stats["total_devices"] }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">In catalog</p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Pending Orders
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                {{ $stats["pending_orders"] }}
                            </p>
                            <p class="mt-1 text-xs text-orange-600">
                                Need action
                            </p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Active Rentals
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                {{ $stats["active_orders"] }}
                            </p>
                            <p class="mt-1 text-xs text-green-600">
                                In use now
                            </p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Notifications -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Total Notifications
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                {{ number_format($stats["total_notifications"]) }}
                            </p>
                            <p class="mt-1 text-xs text-blue-600">
                                {{ number_format($stats["unread_notifications"]) }}
                                unread
                            </p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100"
                        >
                            <svg
                                class="h-5 w-5 text-blue-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Support Messages -->
                <div
                    class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">
                                Support Messages
                            </p>
                            <p
                                class="mt-1 text-3xl font-semibold text-gray-900"
                            >
                                {{ number_format($stats["total_support_messages"]) }}
                            </p>
                            <p class="mt-1 text-xs text-green-600">
                                {{ number_format($stats["unread_support_messages"]) }}
                                unread
                            </p>
                        </div>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100"
                        >
                            <svg
                                class="h-5 w-5 text-green-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-6">
                <h2
                    class="mb-3 text-lg font-semibold tracking-tight text-gray-900"
                >
                    Quick Actions
                </h2>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <a
                        href="{{ route("admin.devices.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Manage Devices
                            </p>
                            <p class="text-xs text-gray-500">
                                Add, edit, remove
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.orders.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                View Orders
                            </p>
                            <p class="text-xs text-gray-500">
                                All transactions
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.users.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Manage Users
                            </p>
                            <p class="text-xs text-gray-500">
                                View, edit users
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.kyc.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                KYC Verification
                            </p>
                            <p class="text-xs text-gray-500">
                                Review submissions
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.devices.create") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Add Device
                            </p>
                            <p class="text-xs text-gray-500">New product</p>
                        </div>
                    </a>

                    <a
                        href="{{ route("devices") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-5 w-5 text-gray-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                View Store
                            </p>
                            <p class="text-xs text-gray-500">Customer view</p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.notifications.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100"
                        >
                            <svg
                                class="h-5 w-5 text-blue-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Send Notifications
                            </p>
                            <p class="text-xs text-gray-500">
                                Broadcast messages
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.chat.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100"
                        >
                            <svg
                                class="h-5 w-5 text-green-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Support Chats
                            </p>
                            <p class="text-xs text-gray-500">
                                User conversations
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Recent Orders -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 px-5 py-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">
                            Recent Orders
                        </h2>
                        <a
                            href="{{ route("admin.orders.index") }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-700"
                        >
                            View All
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    Order ID
                                </th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    Customer
                                </th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    Device
                                </th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    SKU
                                </th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    Total
                                </th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    Date
                                </th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-medium text-gray-500"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50">
                                    <td
                                        class="px-5 py-3 text-sm font-medium text-gray-900"
                                    >
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600">
                                        {{ $order->user->name }}
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-900">
                                        {{ $order->device_name }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <span
                                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 font-mono text-xs text-gray-700"
                                        >
                                            {{ $order->device?->sku ?? "—" }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-5 py-3 text-sm font-semibold text-gray-900"
                                    >
                                        {{ $order->formatted_total }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <span
                                            class="@if ($order->status === "active")
                                                bg-green-50
                                                text-green-700
                                            @elseif ($order->status === "completed")
                                                bg-blue-50
                                                text-blue-700
                                            @elseif ($order->status === "cancelled")
                                                bg-red-50
                                                text-red-700
                                            @else
                                                bg-gray-100
                                                text-gray-700
                                            @endif inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium"
                                        >
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-500">
                                        {{ $order->created_at->format("M j, Y") }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <a
                                            href="{{ route("admin.orders.show", $order) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-700"
                                        >
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="8"
                                        class="px-5 py-12 text-center text-sm text-gray-500"
                                    >
                                        No orders yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Activity: Notifications & Support -->
            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <!-- Recent Notifications -->
                <div
                    class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="border-b border-gray-100 px-5 py-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-semibold text-gray-900">
                                Recent Notifications
                            </h2>
                            <a
                                href="{{ route("admin.notifications.index") }}"
                                class="text-sm font-medium text-blue-600 hover:text-blue-700"
                            >
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @php
                            $recentNotifications = \App\Models\Notification::with("user")
                                ->orderBy("created_at", "desc")
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse ($recentNotifications as $notification)
                            <div class="px-5 py-4 hover:bg-gray-50">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @switch($notification->type)
                                            @case("info")
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                                                >
                                                    <i
                                                        class="fas fa-info text-blue-600 text-sm"
                                                    ></i>
                                                </div>

                                                @break
                                            @case("warning")
                                                <div
                                                    class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center"
                                                >
                                                    <i
                                                        class="fas fa-exclamation-triangle text-yellow-600 text-sm"
                                                    ></i>
                                                </div>

                                                @break
                                            @case("success")
                                                <div
                                                    class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"
                                                >
                                                    <i
                                                        class="fas fa-check-circle text-green-600 text-sm"
                                                    ></i>
                                                </div>

                                                @break
                                            @case("error")
                                                <div
                                                    class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center"
                                                >
                                                    <i
                                                        class="fas fa-times-circle text-red-600 text-sm"
                                                    ></i>
                                                </div>

                                                @break
                                            @default
                                                <div
                                                    class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center"
                                                >
                                                    <i
                                                        class="fas fa-bell text-gray-600 text-sm"
                                                    ></i>
                                                </div>
                                        @endswitch
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-medium text-gray-900 truncate"
                                        >
                                            {{ $notification->title }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-600 mt-1 line-clamp-2"
                                        >
                                            {{ Str::limit($notification->message, 80) }}
                                        </p>
                                        <div
                                            class="flex items-center gap-2 mt-2"
                                        >
                                            <span class="text-xs text-gray-500">
                                                To:
                                                {{ $notification->user->name ?? "Unknown" }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center">
                                <i
                                    class="fas fa-bell-slash text-gray-400 text-2xl mb-2"
                                ></i>
                                <p class="text-sm text-gray-500">
                                    No notifications sent yet
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Support Messages -->
                <div
                    class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5"
                >
                    <div class="border-b border-gray-100 px-5 py-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-semibold text-gray-900">
                                Recent Support Messages
                            </h2>
                            <a
                                href="{{ route("admin.notifications.index") }}#support"
                                class="text-sm font-medium text-blue-600 hover:text-blue-700"
                            >
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @php
                            $recentMessages = \App\Models\SupportMessage::with("user")
                                ->orderBy("created_at", "desc")
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse ($recentMessages as $message)
                            <div class="px-5 py-4 hover:bg-gray-50">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if ($message->sender_type === "user")
                                            <div
                                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"
                                            >
                                                <i
                                                    class="fas fa-user text-green-600 text-sm"
                                                ></i>
                                            </div>
                                        @else
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                                            >
                                                <i
                                                    class="fas fa-headset text-blue-600 text-sm"
                                                ></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ $message->user->name ?? "Unknown User" }}
                                            </p>
                                            @if (! $message->is_read && $message->sender_type === "user")
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                                                >
                                                    New
                                                </span>
                                            @endif
                                        </div>
                                        <p
                                            class="text-xs text-gray-600 mt-1 line-clamp-2"
                                        >
                                            {{ Str::limit($message->message, 80) }}
                                        </p>
                                        <div
                                            class="flex items-center gap-2 mt-2"
                                        >
                                            <span
                                                class="text-xs text-gray-500 capitalize"
                                            >
                                                {{ $message->sender_type }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ $message->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center">
                                <i
                                    class="fas fa-comments text-gray-400 text-2xl mb-2"
                                ></i>
                                <p class="text-sm text-gray-500">
                                    No support messages yet
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
