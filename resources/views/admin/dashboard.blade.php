<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Admin Dashboard — Slice</title>
        @vite("resources/css/app.css")
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50">
        @include("partials.header")

        <main class="mx-auto max-w-7xl px-6 py-12">
            <!-- Admin Badge & Welcome -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-1.5 text-sm font-semibold text-blue-800">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L4 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-.254.145a1 1 0 11-.992-1.736l.23-.132-.23-.132a1 1 0 01-.372-1.364zm-7 4a1 1 0 011.364-.372L10 8.848l1.254-.716a1 1 0 11.992 1.736L11 10.58V12a1 1 0 11-2 0v-1.42l-1.246-.712a1 1 0 01-.372-1.364zM3 11a1 1 0 011 1v1.42l1.246.712a1 1 0 11-.992 1.736l-1.75-1A1 1 0 012 14v-2a1 1 0 011-1zm14 0a1 1 0 011 1v2a1 1 0 01-.504.868l-1.75 1a1 1 0 11-.992-1.736L16 13.42V12a1 1 0 011-1zm-9.618 5.504a1 1 0 011.364-.372l.254.145V16a1 1 0 112 0v.277l.254-.145a1 1 0 11.992 1.736l-1.735.992a.995.995 0 01-1.022 0l-1.735-.992a1 1 0 01-.372-1.364z" clip-rule="evenodd"/>
                        </svg>
                        Admin Access
                    </div>
                    <h1 class="text-5xl font-semibold tracking-tight text-gray-900">Control Center</h1>
                    <p class="mt-2 text-lg text-gray-500">Manage devices, orders, and users</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Logged in as</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Revenue -->
                <div class="group overflow-hidden rounded-3xl bg-gradient-to-br from-green-500 to-emerald-600 p-6 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-100">Total Revenue</p>
                            <p class="mt-2 text-4xl font-bold text-white">${{ number_format($stats['total_revenue'], 2) }}</p>
                            <p class="mt-2 text-xs text-green-100">All time earnings</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="group overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-100">Total Orders</p>
                            <p class="mt-2 text-4xl font-bold text-white">{{ $stats['total_orders'] }}</p>
                            <p class="mt-2 text-xs text-blue-100">{{ $stats['active_orders'] }} active</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="group overflow-hidden rounded-3xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-purple-100">Total Users</p>
                            <p class="mt-2 text-4xl font-bold text-white">{{ $stats['total_users'] }}</p>
                            <p class="mt-2 text-xs text-purple-100">Registered accounts</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Devices -->
                <div class="group overflow-hidden rounded-3xl bg-gradient-to-br from-pink-500 to-rose-600 p-6 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-pink-100">Total Devices</p>
                            <p class="mt-2 text-4xl font-bold text-white">{{ $stats['total_devices'] }}</p>
                            <p class="mt-2 text-xs text-pink-100">Available in catalog</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="group overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 to-amber-600 p-6 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-orange-100">Pending Orders</p>
                            <p class="mt-2 text-4xl font-bold text-white">{{ $stats['pending_orders'] }}</p>
                            <p class="mt-2 text-xs text-orange-100">Awaiting processing</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div class="group overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-500 to-blue-600 p-6 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-cyan-100">Active Rentals</p>
                            <p class="mt-2 text-4xl font-bold text-white">{{ $stats['active_orders'] }}</p>
                            <p class="mt-2 text-xs text-cyan-100">Currently rented</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.devices.index') }}" class="group flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Manage Devices</p>
                        <p class="text-sm text-gray-500">Add, edit, remove</p>
                    </div>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="group flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">View Orders</p>
                        <p class="text-sm text-gray-500">All transactions</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Manage Users</p>
                        <p class="text-sm text-gray-500">View, edit users</p>
                    </div>
                </a>

                <a href="{{ route('admin.kyc.index') }}" class="group flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">KYC Verification</p>
                        <p class="text-sm text-gray-500">Review submissions</p>
                    </div>
                </a>

                <a href="{{ route('admin.devices.create') }}" class="group flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Add Device</p>
                        <p class="text-sm text-gray-500">New product</p>
                    </div>
                </a>

                <a href="{{ route('devices') }}" class="group flex items-center gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">View Store</p>
                        <p class="text-sm text-gray-500">Customer view</p>
                    </div>
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                        <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View All →</a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-100 bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Device</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $order->device_name }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $order->formatted_total }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                        @if($order->status === 'active') bg-green-100 text-green-800
                                        @elseif($order->status === 'completed') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>
