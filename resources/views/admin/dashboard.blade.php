<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Admin Dashboard — Slice</title>
        @vite("resources/css/app.css")
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-[#f5f5f7] antialiased">
        @include("partials.header")

        <main class="mx-auto max-w-7xl px-6 py-8">
            <!-- Admin Header - Apple Style -->
            <div class="mb-6">
                <div class="mb-2 flex items-center gap-2">
                    <div class="flex h-6 w-6 items-center justify-center rounded-md bg-gray-900">
                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-600">Admin Dashboard</span>
                </div>
                <h1 class="text-4xl font-semibold tracking-tight text-gray-900">Overview</h1>
                <p class="mt-1 text-gray-600">Welcome back, {{ $user->name }}</p>
            </div>

            <!-- Stats Grid - Apple Style -->
            <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Revenue -->
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">Total Revenue</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">
                                ${{ number_format($stats["total_revenue"], 2) }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">All time</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">Total Orders</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats["total_orders"] }}</p>
                            <p class="mt-1 text-xs text-blue-600">{{ $stats["active_orders"] }} active</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">Total Users</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats["total_users"] }}</p>
                            <p class="mt-1 text-xs text-gray-500">Registered</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">Total Devices</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats["total_devices"] }}</p>
                            <p class="mt-1 text-xs text-gray-500">In catalog</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">Pending Orders</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats["pending_orders"] }}</p>
                            <p class="mt-1 text-xs text-orange-600">Need action</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500">Active Rentals</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats["active_orders"] }}</p>
                            <p class="mt-1 text-xs text-green-600">In use now</p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            </div>

            <!-- Quick Actions -->
            <div class="mb-6">
                <h2 class="mb-3 text-lg font-semibold tracking-tight text-gray-900">Quick Actions</h2>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <a
                        href="{{ route("admin.devices.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Manage Devices</p>
                            <p class="text-xs text-gray-500">Add, edit, remove</p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.orders.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">View Orders</p>
                            <p class="text-xs text-gray-500">All transactions</p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.users.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Manage Users</p>
                            <p class="text-xs text-gray-500">View, edit users</p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.kyc.index") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">KYC Verification</p>
                            <p class="text-xs text-gray-500">Review submissions</p>
                        </div>
                    </a>

                    <a
                        href="{{ route("admin.devices.create") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Add Device</p>
                            <p class="text-xs text-gray-500">New product</p>
                        </div>
                    </a>

                    <a
                        href="{{ route("devices") }}"
                        class="group flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-900/5 transition hover:shadow-md"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <p class="text-sm font-semibold text-gray-900">View Store</p>
                            <p class="text-xs text-gray-500">Customer view</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Recent Orders -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="border-b border-gray-100 px-5 py-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">Recent Orders</h2>
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
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Order ID</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Customer</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Device</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">SKU</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Total</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-5 py-3 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-600">{{ $order->user->name }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-900">{{ $order->device_name }}</td>
                                    <td class="px-5 py-3">
                                        <span
                                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 font-mono text-xs text-gray-700"
                                        >
                                            {{ $order->device?->sku ?? "—" }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm font-semibold text-gray-900">
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
                                    <td colspan="8" class="px-5 py-12 text-center text-sm text-gray-500">
                                        No orders yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>
