<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Your Cart - Slice</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap"
            rel="stylesheet"
        />
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20">
        @include("partials.header")

        <main class="mx-auto max-w-4xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Your Cart</h1>
                <p class="mt-2 text-gray-600">Review your selected devices before checkout</p>
            </div>

            @if ($items->isEmpty())
                <!-- Empty Cart State -->
                <div class="rounded-3xl bg-white p-12 text-center shadow-lg">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">Your cart is empty</h3>
                    <p class="mt-2 text-gray-600">Start adding devices to rent</p>
                    <a
                        href="{{ route("devices") }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 font-semibold text-white shadow-lg transition-all hover:shadow-xl"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Browse Devices
                    </a>
                </div>
            @else
                <!-- Cart Items -->
                <div class="space-y-4">
                    @php
                        $grandTotal = 0;
                    @endphp

                    @foreach ($items as $it)
                        @php
                            $itemTotal = $it->total_price ?? 0;
                            $grandTotal += $itemTotal;
                        @endphp

                        <div
                            class="group overflow-hidden rounded-3xl bg-white p-6 shadow-lg transition-all hover:shadow-xl"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ ucwords(str_replace("-", " ", $it->variant_slug)) }}
                                    </h3>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        @if ($it->capacity)
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"
                                            >
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"
                                                    />
                                                    <path
                                                        d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"
                                                    />
                                                    <path
                                                        d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"
                                                    />
                                                </svg>
                                                {{ $it->capacity }}
                                            </span>
                                        @endif

                                        <span
                                            class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700"
                                        >
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{ $it->months }} {{ Str::plural("month", $it->months) }}
                                        </span>
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700"
                                        >
                                            Qty: {{ $it->quantity ?? 1 }}
                                        </span>
                                    </div>
                                    <div class="mt-3 text-sm text-gray-600">
                                        Rp {{ number_format($it->price_monthly ?? 0, 0, ",", ".") }}/month ×
                                        {{ $it->months }} months × {{ $it->quantity ?? 1 }} unit
                                    </div>
                                </div>
                                <div class="ml-6 text-right">
                                    <div class="text-2xl font-bold text-gray-900">
                                        Rp {{ number_format($itemTotal, 0, ",", ".") }}
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500">Total</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="mt-8 rounded-3xl bg-gradient-to-br from-blue-600 to-purple-600 p-8 shadow-xl">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Grand Total</p>
                            <p class="mt-1 text-4xl font-bold">Rp {{ number_format($grandTotal, 0, ",", ".") }}</p>
                            <p class="mt-2 text-sm text-blue-100">
                                {{ $items->count() }} {{ Str::plural("item", $items->count()) }} in cart
                            </p>
                        </div>
                        <a
                            href="{{ route("checkout.index") }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-4 font-semibold text-blue-600 shadow-lg transition-transform hover:scale-105"
                        >
                            <span>Proceed to Checkout</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <div class="mt-6 text-center">
                    <a
                        href="{{ route("devices") }}"
                        class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            @endif
        </main>
    </body>
</html>
