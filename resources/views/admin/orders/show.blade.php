<!DOCTYPE html>
<html lang="en" x-data="{ showStatusModal: false, newStatus: '{{ $order->status }}' }">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Order #{{ $order->id }} — Admin — Slice</title>
        @vite("resources/css/app.css")
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50">
        @include("partials.header")

        <main class="mx-auto max-w-5xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('admin.orders.index') }}" class="mb-2 inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Orders
                </a>
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-4xl font-semibold tracking-tight text-gray-900">Order #{{ $order->id }}</h1>
                        <p class="mt-2 text-gray-500">Placed {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                        @if($order->status === 'active') bg-green-100 text-green-800
                        @elseif($order->status === 'completed') bg-blue-100 text-blue-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-500/20">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Info -->
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Customer Information</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $order->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">User ID</p>
                                <p class="font-mono text-sm text-gray-700">#{{ $order->user->id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Device Info -->
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Device Details</h2>
                        <div class="flex items-start gap-6">
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200">
                                @php
                                    $deviceName = strtolower($order->device_name ?? $order->variant_slug);
                                @endphp
                                @if(str_contains($deviceName, 'iphone'))
                                    <svg class="h-10 w-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                @elseif(str_contains($deviceName, 'ipad'))
                                    <svg class="h-10 w-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21 5H3c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 12H3V7h18v10z"/>
                                    </svg>
                                @else
                                    <svg class="h-10 w-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-semibold text-gray-900">{{ $order->device_name }}</h3>
                                @if($order->capacity)
                                <p class="mt-1 text-sm text-gray-500">{{ $order->capacity }} Storage</p>
                                @endif
                                <div class="mt-4 flex items-center gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Monthly Rate</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $order->formatted_price }}/mo</p>
                                    </div>
                                    <div class="h-8 w-px bg-gray-200"></div>
                                    <div>
                                        <p class="text-xs text-gray-500">Duration</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $order->months }} {{ Str::plural('month', $order->months) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Order Summary</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Monthly Rate</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $order->formatted_price }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Duration</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $order->months }} {{ Str::plural('month', $order->months) }}</span>
                            </div>
                            @if($order->capacity)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Storage</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $order->capacity }}</span>
                            </div>
                            @endif
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-base font-bold text-blue-600">{{ $order->formatted_total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Admin Actions</h2>
                        <div class="space-y-3">
                            <button @click="showStatusModal = true" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Change Status
                            </button>

                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 transition hover:bg-red-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Update Modal -->
            <div x-show="showStatusModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="showStatusModal = false">
                <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl" @click.stop>
                    <h3 class="mb-6 text-2xl font-semibold text-gray-900">Update Order Status</h3>
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-6">
                            <label class="mb-3 block text-sm font-semibold text-gray-900">Select New Status</label>
                            <div class="space-y-2">
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-gray-200 p-4 transition hover:border-yellow-400" :class="newStatus === 'created' ? 'border-yellow-400 bg-yellow-50' : ''">
                                    <input type="radio" name="status" value="created" x-model="newStatus" class="h-4 w-4 text-yellow-600" />
                                    <div>
                                        <span class="font-medium text-gray-900">Created</span>
                                        <p class="text-xs text-gray-500">Order placed, awaiting processing</p>
                                    </div>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-gray-200 p-4 transition hover:border-green-400" :class="newStatus === 'active' ? 'border-green-400 bg-green-50' : ''">
                                    <input type="radio" name="status" value="active" x-model="newStatus" class="h-4 w-4 text-green-600" />
                                    <div>
                                        <span class="font-medium text-gray-900">Active</span>
                                        <p class="text-xs text-gray-500">Device delivered, rental active</p>
                                    </div>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-gray-200 p-4 transition hover:border-blue-400" :class="newStatus === 'completed' ? 'border-blue-400 bg-blue-50' : ''">
                                    <input type="radio" name="status" value="completed" x-model="newStatus" class="h-4 w-4 text-blue-600" />
                                    <div>
                                        <span class="font-medium text-gray-900">Completed</span>
                                        <p class="text-xs text-gray-500">Rental period ended, device returned</p>
                                    </div>
                                </label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-gray-200 p-4 transition hover:border-red-400" :class="newStatus === 'cancelled' ? 'border-red-400 bg-red-50' : ''">
                                    <input type="radio" name="status" value="cancelled" x-model="newStatus" class="h-4 w-4 text-red-600" />
                                    <div>
                                        <span class="font-medium text-gray-900">Cancelled</span>
                                        <p class="text-xs text-gray-500">Order cancelled or refunded</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition hover:bg-blue-700">
                                Update Status
                            </button>
                            <button type="button" @click="showStatusModal = false" class="rounded-xl border border-gray-200 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
