<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Broadcast Notification — Admin — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50">
        <main class="mx-auto max-w-4xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8">
                <a
                    href="{{ route("admin.notifications.index") }}"
                    class="mb-2 inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900"
                >
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Notifications
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-semibold tracking-tight text-gray-900">Broadcast Notification</h1>
                        <p class="mt-2 text-gray-500">Compose and dispatch alerts to the user base</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route("admin.notifications.store") }}" method="POST" class="space-y-8">
                @csrf

                <!-- Card -->
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5">
                    <!-- Title & Message -->
                    <div class="space-y-6">
                        <div>
                            <label
                                for="title"
                                class="mb-2 block text-xs font-bold tracking-wider text-gray-500 uppercase"
                            >
                                Subject Line
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old("title") }}"
                                required
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-500 transition-all outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                placeholder="e.g. System Maintenance Scheduled"
                            />
                            @error("title")
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="message"
                                class="mb-2 block text-xs font-bold tracking-wider text-gray-500 uppercase"
                            >
                                Message Body
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="message"
                                name="message"
                                rows="4"
                                required
                                class="w-full resize-none rounded-xl border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-500 transition-all outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                placeholder="Detailed information for the user..."
                            >
{{ old("message") }}</textarea
                            >
                            @error("message")
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="action_url"
                                class="mb-2 block text-xs font-bold tracking-wider text-gray-500 uppercase"
                            >
                                Call to Action URL
                                <span class="font-normal text-gray-600 normal-case">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"
                                >
                                    <i class="fas fa-link text-xs"></i>
                                </div>
                                <input
                                    type="url"
                                    id="action_url"
                                    name="action_url"
                                    value="{{ old("action_url") }}"
                                    class="w-full rounded-xl border border-gray-300 py-3 pr-4 pl-10 text-gray-900 placeholder-gray-500 transition-all outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                    placeholder="https://slice.com/promo"
                                />
                            </div>
                            @error("action_url")
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="my-8 border-t border-gray-100"></div>

                    <!-- Target Audience -->
                    <div class="mb-8">
                        <label class="mb-4 block text-xs font-bold tracking-wider text-gray-500 uppercase">
                            Target Audience
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Option 1 -->
                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="target_users"
                                    value="all"
                                    class="peer sr-only"
                                    {{ old("target_users", "all") == "all" ? "checked" : "" }}
                                />
                                <div
                                    class="rounded-xl border border-gray-200 bg-white p-4 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50"
                                >
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-900">All Users</span>
                                        <div
                                            class="flex h-4 w-4 items-center justify-center rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500"
                                        >
                                            <div
                                                class="h-1.5 w-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"
                                            ></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Total fleet: {{ $stats["total_users"] ?? 0 }}</p>
                                </div>
                            </label>
                            <!-- Option 2 -->
                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="target_users"
                                    value="verified"
                                    class="peer sr-only"
                                    {{ old("target_users") == "verified" ? "checked" : "" }}
                                />
                                <div
                                    class="rounded-xl border border-gray-200 bg-white p-4 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50"
                                >
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-900">Verified</span>
                                        <div
                                            class="flex h-4 w-4 items-center justify-center rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500"
                                        >
                                            <div
                                                class="h-1.5 w-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"
                                            ></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Active: {{ $stats["verified_users"] ?? 0 }}</p>
                                </div>
                            </label>
                            <!-- Option 3 -->
                            <label class="relative cursor-pointer">
                                <input
                                    type="radio"
                                    name="target_users"
                                    value="unverified"
                                    class="peer sr-only"
                                    {{ old("target_users") == "unverified" ? "checked" : "" }}
                                />
                                <div
                                    class="rounded-xl border border-gray-200 bg-white p-4 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50"
                                >
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-900">Unverified</span>
                                        <div
                                            class="flex h-4 w-4 items-center justify-center rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500"
                                        >
                                            <div
                                                class="h-1.5 w-1.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"
                                            ></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Pending: {{ $stats["unverified_users"] ?? 0 }}</p>
                                </div>
                            </label>
                        </div>
                        @error("target_users")
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notification Level (Type) -->
                    <div>
                        <label class="mb-4 block text-xs font-bold tracking-wider text-gray-500 uppercase">
                            Severity Level
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                            <!-- Info -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="notification_type"
                                    value="info"
                                    class="peer sr-only"
                                    {{ old("notification_type", "info") == "info" ? "checked" : "" }}
                                />
                                <div
                                    class="flex flex-col items-center rounded-xl border border-gray-200 bg-white p-4 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50"
                                >
                                    <i
                                        class="fas fa-info-circle mb-2 text-xl text-gray-400 peer-checked:text-blue-600"
                                    ></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">Info</span>
                                </div>
                            </label>
                            <!-- Success -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="notification_type"
                                    value="success"
                                    class="peer sr-only"
                                    {{ old("notification_type") == "success" ? "checked" : "" }}
                                />
                                <div
                                    class="flex flex-col items-center rounded-xl border border-gray-200 bg-white p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50"
                                >
                                    <i
                                        class="fas fa-check-circle mb-2 text-xl text-gray-400 peer-checked:text-green-600"
                                    ></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">
                                        Success
                                    </span>
                                </div>
                            </label>
                            <!-- Warning -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="notification_type"
                                    value="warning"
                                    class="peer sr-only"
                                    {{ old("notification_type") == "warning" ? "checked" : "" }}
                                />
                                <div
                                    class="flex flex-col items-center rounded-xl border border-gray-200 bg-white p-4 text-center transition-all peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:bg-gray-50"
                                >
                                    <i
                                        class="fas fa-exclamation-triangle mb-2 text-xl text-gray-400 peer-checked:text-yellow-600"
                                    ></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">
                                        Warning
                                    </span>
                                </div>
                            </label>
                            <!-- Error -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="notification_type"
                                    value="error"
                                    class="peer sr-only"
                                    {{ old("notification_type") == "error" ? "checked" : "" }}
                                />
                                <div
                                    class="flex flex-col items-center rounded-xl border border-gray-200 bg-white p-4 text-center transition-all peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50"
                                >
                                    <i
                                        class="fas fa-times-circle mb-2 text-xl text-gray-400 peer-checked:text-red-600"
                                    ></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">
                                        Critical
                                    </span>
                                </div>
                            </label>
                        </div>
                        @error("notification_type")
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4">
                    <a
                        href="{{ route("admin.notifications.index") }}"
                        class="rounded-xl px-6 py-3 text-sm font-medium text-gray-600 transition-colors hover:text-gray-900"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="rounded-xl bg-blue-600 px-8 py-3 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Broadcast Now
                    </button>
                </div>
            </form>
        </main>
    </body>
</html>
