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
                    href="{{ route('admin.notifications.index') }}"
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
            <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Card -->
                <div class="bg-white rounded-3xl p-8 shadow-sm ring-1 ring-gray-900/5">

                    <!-- Title & Message -->
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Subject Line <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all outline-none"
                                placeholder="e.g. System Maintenance Scheduled">
                            @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Message Body <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="4" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all outline-none resize-none"
                                placeholder="Detailed information for the user...">{{ old('message') }}</textarea>
                            @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="action_url" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Call to Action URL <span class="text-gray-600 font-normal normal-case">(Optional)</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-link text-xs"></i>
                                </div>
                                <input type="url" id="action_url" name="action_url" value="{{ old('action_url') }}"
                                    class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-3 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all outline-none"
                                    placeholder="https://slice.com/promo">
                            </div>
                            @error('action_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-8"></div>

                    <!-- Target Audience -->
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Target Audience <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Option 1 -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="target_users" value="all" class="peer sr-only" {{ old('target_users', 'all') == 'all' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 transition-all">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-bold text-gray-900">All Users</span>
                                        <div class="w-4 h-4 rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Total fleet: {{ $stats['total_users'] ?? 0 }}</p>
                                </div>
                            </label>
                            <!-- Option 2 -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="target_users" value="verified" class="peer sr-only" {{ old('target_users') == 'verified' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 transition-all">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-bold text-gray-900">Verified</span>
                                        <div class="w-4 h-4 rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Active: {{ $stats['verified_users'] ?? 0 }}</p>
                                </div>
                            </label>
                            <!-- Option 3 -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="target_users" value="unverified" class="peer sr-only" {{ old('target_users') == 'unverified' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 transition-all">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-bold text-gray-900">Unverified</span>
                                        <div class="w-4 h-4 rounded-full border border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                            <div class="w-1.5 h-1.5 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Pending: {{ $stats['unverified_users'] ?? 0 }}</p>
                                </div>
                            </label>
                        </div>
                        @error('target_users') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Notification Level (Type) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Severity Level <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <!-- Info -->
                            <label class="cursor-pointer">
                                <input type="radio" name="notification_type" value="info" class="peer sr-only" {{ old('notification_type', 'info') == 'info' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 transition-all text-center">
                                    <i class="fas fa-info-circle text-xl mb-2 text-gray-400 peer-checked:text-blue-600"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">Info</span>
                                </div>
                            </label>
                            <!-- Success -->
                            <label class="cursor-pointer">
                                <input type="radio" name="notification_type" value="success" class="peer sr-only" {{ old('notification_type') == 'success' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-green-50 peer-checked:border-green-500 transition-all text-center">
                                    <i class="fas fa-check-circle text-xl mb-2 text-gray-400 peer-checked:text-green-600"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">Success</span>
                                </div>
                            </label>
                            <!-- Warning -->
                            <label class="cursor-pointer">
                                <input type="radio" name="notification_type" value="warning" class="peer sr-only" {{ old('notification_type') == 'warning' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-yellow-50 peer-checked:border-yellow-500 transition-all text-center">
                                    <i class="fas fa-exclamation-triangle text-xl mb-2 text-gray-400 peer-checked:text-yellow-600"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">Warning</span>
                                </div>
                            </label>
                            <!-- Error -->
                            <label class="cursor-pointer">
                                <input type="radio" name="notification_type" value="error" class="peer sr-only" {{ old('notification_type') == 'error' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-red-50 peer-checked:border-red-500 transition-all text-center">
                                    <i class="fas fa-times-circle text-xl mb-2 text-gray-400 peer-checked:text-red-600"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-gray-900">Critical</span>
                                </div>
                            </label>
                        </div>
                        @error('notification_type') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.notifications.index') }}" class="px-6 py-3 rounded-xl text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Broadcast Now
                    </button>
                </div>
            </form>
        </main>
    </body>
</html>
