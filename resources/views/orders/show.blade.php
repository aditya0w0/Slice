<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Order #{{ $order->id }} — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50" data-auth-required="true">
        @include("partials.header")

        <main class="mx-auto max-w-5xl px-6 py-12">
            <!-- Back button -->
            <div class="mb-8">
                <a
                    href="{{ route("dashboard") }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900"
                >
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Success message -->
            @if (session("success"))
                <div class="mb-8 rounded-2xl bg-green-50 p-6 ring-1 ring-green-500/20">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-green-900">Order Confirmed!</h3>
                            <p class="text-sm text-green-700">{{ session("success") }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order Header -->
            <div class="mb-8">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-4xl font-semibold tracking-tight text-gray-900">Order Details</h1>
                        <p class="mt-2 text-lg text-gray-500">Order #{{ $order->id }}</p>
                        <p class="mt-1 text-sm text-gray-400">
                            Placed {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span
                            class="@if ($order->status === "active")
                                bg-green-100
                                text-green-800
                            @elseif ($order->status === "completed")
                                bg-blue-100
                                text-blue-800
                            @else
                                bg-yellow-100
                                text-yellow-800
                            @endif inline-flex items-center rounded-full px-4 py-2 text-sm font-medium"
                        >
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Device Card -->
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                        <div class="p-8">
                            <h2 class="text-sm font-semibold tracking-wider text-gray-500 uppercase">Device</h2>
                            <div class="mt-6 flex items-start gap-6">
                                <!-- Device Image/Icon -->
                                <div
                                    class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200"
                                >
                                    @php
                                        $deviceName = strtolower($order->device_name ?? $order->variant_slug);
                                    @endphp

                                    @if (str_contains($deviceName, "iphone"))
                                        <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                                            />
                                        </svg>
                                    @elseif (str_contains($deviceName, "ipad"))
                                        <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M21 5H3c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 12H3V7h18v10z"
                                            />
                                        </svg>
                                    @elseif (str_contains($deviceName, "mac"))
                                        <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"
                                            />
                                        </svg>
                                    @else
                                        <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                                            />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Device Info -->
                                <div class="flex-1">
                                    <h3 class="text-2xl font-semibold text-gray-900">{{ $order->device_name }}</h3>
                                    @if ($order->capacity)
                                        <p class="mt-1 text-sm text-gray-500">{{ $order->capacity }} Storage</p>
                                    @endif

                                    <div class="mt-4 flex items-center gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Monthly Rate</p>
                                            <p class="text-lg font-semibold text-gray-900">
                                                {{ $order->formatted_price }}/mo
                                            </p>
                                        </div>
                                        <div class="h-8 w-px bg-gray-200"></div>
                                        <div>
                                            <p class="text-xs text-gray-500">Duration</p>
                                            <p class="text-lg font-semibold text-gray-900">
                                                {{ $order->months }} {{ Str::plural("month", $order->months) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                        <div class="p-8">
                            <h2 class="text-sm font-semibold tracking-wider text-gray-500 uppercase">Order Timeline</h2>
                            <div class="mt-6 space-y-6">
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100"
                                        >
                                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <div class="mt-2 h-full w-px bg-gray-200"></div>
                                    </div>
                                    <div class="flex-1 pb-8">
                                        <p class="font-semibold text-gray-900">Order Placed</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $order->created_at->format("M j, Y g:i A") }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="{{ $order->status !== "created" ? "bg-green-100" : "bg-gray-100" }} flex h-10 w-10 items-center justify-center rounded-full"
                                        >
                                            <svg
                                                class="{{ $order->status !== "created" ? "text-green-600" : "text-gray-400" }} h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>
                                        </div>
                                        <div class="mt-2 h-full w-px bg-gray-200"></div>
                                    </div>
                                    <div class="flex-1 pb-8">
                                        <p class="font-semibold text-gray-900">Processing</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $order->status !== "created" ? "Completed" : "Pending" }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="{{ in_array($order->status, ["processing", "picked_up", "shipped", "delivered"]) ? "bg-green-100" : "bg-gray-100" }} flex h-10 w-10 items-center justify-center rounded-full"
                                        >
                                            <svg
                                                class="{{ in_array($order->status, ["processing", "picked_up", "shipped", "delivered"]) ? "text-green-600" : "text-gray-400" }} h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">Delivery</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $order->status === "delivered" ? "Delivered" : (in_array($order->status, ["processing", "picked_up", "shipped"]) ? "In Transit" : "Pending") }}
                                        </p>
                                        @if (in_array($order->status, ["processing", "picked_up", "shipped"]))
                                            <a
                                                href="{{ route("delivery.track", $order->id) }}"
                                                class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-700"
                                            >
                                                Track delivery
                                                <svg
                                                    class="h-3 w-3"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                        <div class="p-6">
                            <h2 class="text-sm font-semibold tracking-wider text-gray-500 uppercase">Summary</h2>
                            <div class="mt-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Monthly Rate</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $order->formatted_price }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Duration</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $order->months }} {{ Str::plural("month", $order->months) }}
                                    </span>
                                </div>
                                @if ($order->capacity)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Storage</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $order->capacity }}</span>
                                    </div>
                                @endif

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-base font-semibold text-gray-900">Total</span>
                                        <span class="text-base font-bold text-blue-600">
                                            {{ $order->formatted_total }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                        <div class="p-6">
                            <h2 class="text-sm font-semibold tracking-wider text-gray-500 uppercase">Actions</h2>
                            <div class="mt-4 space-y-3">
                                @if (in_array($order->status, ["paid", "processing", "picked_up", "shipped"]))
                                    <a
                                        href="{{ route("delivery.track", $order->id) }}"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-green-700 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-green-500/30 transition hover:from-green-700 hover:to-green-800"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"
                                            />
                                        </svg>
                                        🚚 Track Delivery
                                    </a>
                                @endif

                                <button
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                        />
                                    </svg>
                                    Download Receipt
                                </button>

                                <a
                                    href="{{ route("support") }}"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    Contact Support
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-gray-50 to-gray-100 p-6">
                        <h2 class="text-sm font-semibold tracking-wider text-gray-500 uppercase">Customer</h2>
                        <div class="mt-4">
                            <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
