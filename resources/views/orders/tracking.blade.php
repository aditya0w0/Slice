<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Track Delivery - Order {{ $order->invoice_number }}</title>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite("resources/css/app.css")
    </head>
    <body class="h-screen overflow-hidden bg-gray-50">
        <main class="container mx-auto flex h-full max-w-7xl flex-col px-4 py-4" x-data="deliveryTracking()">
            <!-- Header Section -->
            <div class="mb-4 flex-shrink-0">
                <a
                    href="{{ route("orders.show", $order->id) }}"
                    class="mb-2 inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                >
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Back to Order Details
                </a>
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold text-gray-900">Track Delivery</h1>
                    <p class="text-sm text-gray-600">{{ $order->invoice_number }}</p>
                    @if ($order->estimated_delivery_date)
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                            ETA: {{ $order->estimated_delivery_date->format("M j") }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid min-h-0 flex-1 gap-4 lg:grid-cols-3">
                <!-- Left Column: Animated Map (For Investors!) -->
                <div class="flex min-h-0 flex-col lg:col-span-2">
                    <div class="max-h-[575px] flex-1 overflow-hidden rounded-2xl bg-white shadow-lg">
                        <!-- Real-Time Delivery Map -->
                        <div class="relative h-full bg-gray-50">
                            <!-- Map Background -->
                            <svg
                                class="absolute inset-0 h-full w-full"
                                viewBox="0 0 800 500"
                                preserveAspectRatio="xMidYMid slice"
                            >
                                <defs>
                                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#e5e7eb" stroke-width="1" />
                                    </pattern>
                                </defs>
                                <rect width="800" height="500" fill="#f9fafb" />
                                <rect width="800" height="500" fill="url(#grid)" />

                                <!-- Streets -->
                                <line x1="0" y1="150" x2="800" y2="150" stroke="#d1d5db" stroke-width="3" />
                                <line x1="0" y1="300" x2="800" y2="300" stroke="#d1d5db" stroke-width="3" />
                                <line x1="200" y1="0" x2="200" y2="500" stroke="#d1d5db" stroke-width="3" />
                                <line x1="500" y1="0" x2="500" y2="500" stroke="#d1d5db" stroke-width="3" />
                                <line x1="650" y1="0" x2="650" y2="500" stroke="#d1d5db" stroke-width="3" />

                                <!-- Buildings -->
                                <rect x="50" y="50" width="100" height="80" fill="#e5e7eb" opacity="0.5" />
                                <rect x="250" y="180" width="120" height="100" fill="#e5e7eb" opacity="0.5" />
                                <rect x="550" y="80" width="90" height="150" fill="#e5e7eb" opacity="0.5" />
                                <rect x="100" y="350" width="150" height="120" fill="#e5e7eb" opacity="0.5" />
                                <rect x="550" y="320" width="180" height="140" fill="#e5e7eb" opacity="0.5" />
                            </svg>

                            <!-- Animated Route -->
                            <svg class="pointer-events-none absolute inset-0 h-full w-full" viewBox="0 0 800 500">
                                <defs>
                                    <linearGradient id="routeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color: #3b82f6; stop-opacity: 1" />
                                        <stop offset="100%" style="stop-color: #2563eb; stop-opacity: 1" />
                                    </linearGradient>
                                </defs>
                                <path
                                    d="M 150 100 Q 300 200, 450 250 T 620 370"
                                    fill="none"
                                    stroke="url(#routeGradient)"
                                    stroke-width="6"
                                    stroke-linecap="round"
                                    stroke-dasharray="1000"
                                    stroke-dashoffset="1000"
                                    style="animation: dash 4s linear infinite"
                                />
                            </svg>

                            <!-- Warehouse Marker -->
                            <div class="absolute" style="top: 80px; left: 130px">
                                <div class="relative">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full border-4 border-white bg-blue-600 shadow-lg"
                                    >
                                        <svg
                                            class="h-6 w-6 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                            />
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute top-14 left-1/2 -translate-x-1/2 rounded bg-white px-2 py-1 text-xs font-medium whitespace-nowrap shadow-sm"
                                    >
                                        Warehouse
                                    </div>
                                </div>
                            </div>

                            <!-- Animated Driver Position -->
                            @php
                                $progress = $order->delivery_progress;
                                $driverX = 130 + ($progress / 100) * 500;
                                $driverY = 80 + ($progress / 100) * 300;
                            @endphp

                            <div
                                class="absolute transition-all duration-1000 ease-in-out"
                                style="top: {{ $driverY }}px; left: {{ $driverX }}px"
                            >
                                <div
                                    class="@if(in_array($order->delivery_status, ['out_for_delivery', 'shipped'])) animate-bounce @endif relative"
                                >
                                    <div
                                        class="pulse-ring flex h-14 w-14 items-center justify-center rounded-full border-4 border-white bg-gradient-to-r from-red-500 to-red-600 shadow-2xl"
                                    >
                                        <svg
                                            class="h-7 w-7 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"
                                            />
                                        </svg>
                                    </div>
                                    @if (in_array($order->delivery_status, ["out_for_delivery", "shipped"]))
                                        <div
                                            class="absolute -top-10 left-1/2 -translate-x-1/2 rounded-full bg-red-600 px-3 py-1 text-xs font-semibold whitespace-nowrap text-white shadow-lg"
                                        >
                                            On the way! 🚚
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Customer Location Marker -->
                            <div class="absolute" style="top: 380px; left: 630px">
                                <div class="relative">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full border-4 border-white bg-green-600 shadow-lg"
                                    >
                                        <svg
                                            class="h-6 w-6 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                            />
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute top-14 left-1/2 -translate-x-1/2 rounded bg-white px-2 py-1 text-xs font-medium whitespace-nowrap shadow-sm"
                                    >
                                        Your Location
                                    </div>
                                </div>
                            </div>

                            <!-- ETA Badge -->
                            @if ($order->estimated_delivery_date)
                                <div class="absolute top-4 right-4 rounded-xl bg-white px-4 py-3 shadow-lg">
                                    <div class="mb-1 text-xs text-gray-600">Estimated Arrival</div>
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ $order->estimated_delivery_date->format("M j") }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $order->estimated_delivery_date->format("g:i A") }}
                                    </div>
                                </div>
                            @endif

                            <!-- Progress Indicator (Investor Bait!) -->
                            <div
                                class="absolute right-4 bottom-4 left-4 rounded-xl bg-white p-4 shadow-lg backdrop-blur-sm"
                            >
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $order->delivery_status_label }}
                                    </span>
                                    <span class="text-sm font-bold text-blue-600">
                                        {{ round($order->delivery_progress) }}%
                                    </span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-gray-200">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-1000"
                                        style="width: {{ $order->delivery_progress }}%"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-4 overflow-y-auto">
                    <!-- Delivery Timeline -->
                    <div class="rounded-2xl bg-white p-4 shadow-lg">
                        <h2 class="mb-4 text-lg font-bold text-gray-900">Timeline</h2>
                        <div class="space-y-3">
                            @foreach ($timeline as $index => $step)
                                <div class="flex items-start gap-3">
                                    <!-- Icon -->
                                    <div class="relative shrink-0">
                                        @if ($step["completed"])
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600"
                                            >
                                                <svg
                                                    class="h-4 w-4 text-white"
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
                                        @elseif ($index === $currentStageIndex)
                                            <div
                                                class="flex h-8 w-8 animate-pulse items-center justify-center rounded-full bg-blue-600"
                                            >
                                                <div class="h-2 w-2 rounded-full bg-white"></div>
                                            </div>
                                        @else
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200"
                                            >
                                                <div class="h-1.5 w-1.5 rounded-full bg-gray-400"></div>
                                            </div>
                                        @endif

                                        <!-- Connector -->
                                        @if ($index < count($timeline) - 1)
                                            <div
                                                class="{{ $step["completed"] ? "bg-green-600" : "bg-gray-200" }} absolute top-8 left-1/2 h-3 w-0.5 -translate-x-1/2"
                                            ></div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="{{ $step["completed"] ? "" : "opacity-50" }} flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $step["label"] }}</h3>

                                        @if ($step["timestamp"])
                                            <p class="text-xs text-gray-600">
                                                {{ $step["timestamp"]->format("M j, g:i A") }}
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400">Pending</p>
                                        @endif

                                        @if ($index === $currentStageIndex && ! $step["completed"])
                                            <span
                                                class="mt-1 inline-block rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700"
                                            >
                                                In Progress
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tracking Info -->
                    @if ($order->tracking_number || $order->courier_name)
                        <div class="rounded-2xl bg-white p-4 shadow-lg">
                            <h2 class="mb-3 text-base font-bold text-gray-900">Tracking Info</h2>
                            <div class="space-y-2">
                                @if ($order->tracking_number)
                                    <div>
                                        <p class="text-xs text-gray-500">Tracking Number</p>
                                        <p class="font-mono text-sm font-semibold text-gray-900">
                                            {{ $order->tracking_number }}
                                        </p>
                                    </div>
                                @endif

                                @if ($order->courier_name)
                                    <div>
                                        <p class="text-xs text-gray-500">Courier</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $order->courier_name }}</p>
                                    </div>
                                @endif

                                @if ($order->courier_phone)
                                    <div>
                                        <p class="text-xs text-gray-500">Contact</p>
                                        <a
                                            href="tel:{{ $order->courier_phone }}"
                                            class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                                        >
                                            {{ $order->courier_phone }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Order Details -->
                    <div class="rounded-2xl bg-white p-4 shadow-lg">
                        <h2 class="mb-3 text-base font-bold text-gray-900">Order Details</h2>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs text-gray-500">Device</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $order->device_name }}</p>
                                @if ($order->capacity)
                                    <p class="text-xs text-gray-600">{{ $order->capacity }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Order Date</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $order->created_at->format("M j, Y") }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Duration</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $order->months }} {{ Str::plural("month", $order->months) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="rounded-2xl bg-gray-100 p-4">
                        <h3 class="mb-2 text-sm font-semibold text-gray-900">Need Help?</h3>
                        <p class="mb-2 text-xs text-gray-600">Contact support for questions.</p>
                        <a
                            href="mailto:support@slice.com"
                            class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-3 py-2 text-xs font-medium text-white hover:bg-gray-800"
                        >
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            Support
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <style>
            @keyframes pulse-ring {
                0%,
                100% {
                    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
                }
                50% {
                    box-shadow: 0 0 0 15px rgba(239, 68, 68, 0);
                }
            }
            @keyframes dash {
                0% {
                    stroke-dashoffset: 1000;
                }
                100% {
                    stroke-dashoffset: 0;
                }
            }
            .pulse-ring {
                animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
        </style>

        <script>
            function deliveryTracking() {
                return {
                    progress: {{ $order->delivery_progress }},
                    estimatedTime:
                        '{{ $order->estimated_delivery_date ? $order->estimated_delivery_date->diffForHumans() : "TBD" }}',
                };
            }
        </script>
    </body>
</html>
