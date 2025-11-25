<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Checkout - Slice</title>
        @vite("resources/css/app.css")
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50">
        @include("partials.header")

        <main class="container mx-auto max-w-4xl px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <a
                    href="{{ route('cart.index') }}"
                    class="mb-4 inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                >
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Back to Cart
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                <p class="mt-2 text-gray-600">Complete your rental order</p>
            </div>

            @if($items->isEmpty())
                <div class="rounded-2xl bg-white p-12 text-center shadow-lg">
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
                    <p class="mt-2 text-gray-600">Add some devices to your cart first</p>
                    <a
                        href="{{ route('devices') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 font-semibold text-white shadow-lg transition-all hover:shadow-xl"
                    >
                        Browse Devices
                    </a>
                </div>
            @else
                <div class="grid gap-8 lg:grid-cols-3">
                    <!-- Order Summary -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="rounded-2xl bg-white p-6 shadow-lg">
                            <h2 class="mb-4 text-xl font-bold text-gray-900">Order Summary</h2>

                            <div class="space-y-4">
                                @foreach($items as $item)
                                    <div class="flex items-center gap-4 rounded-lg border p-4">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900">{{ $item->variant_slug ?? 'Device' }}</h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $item->months ?? 1 }} months × {{ $item->quantity ?? 1 }} unit(s)
                                                @if($item->capacity)
                                                    ({{ $item->capacity }})
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">Rp {{ number_format($item->total_price ?? 0, 0, ',', '.') }}</p>
                                            <p class="text-sm text-gray-500">Rp {{ number_format(($item->price_monthly ?? 0), 0, ',', '.') }}/month</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 border-t pt-4">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="rounded-2xl bg-white p-6 shadow-lg">
                            <h2 class="mb-4 text-xl font-bold text-gray-900">Payment Method</h2>

                            <form action="{{ route('checkout.complete') }}" method="POST">
                                @csrf

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="payment_method" value="bank_transfer" checked class="text-blue-600">
                                                <span class="ml-2">Bank Transfer</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="payment_method" value="credit_card" class="text-blue-600">
                                                <span class="ml-2">Credit Card</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="mt-6 w-full rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 font-semibold text-white shadow-lg transition-all hover:shadow-xl"
                                >
                                    Complete Order
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="space-y-6">
                        <div class="rounded-2xl bg-white p-6 shadow-lg">
                            <h3 class="mb-4 text-lg font-bold text-gray-900">Order Information</h3>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Items</span>
                                    <span class="font-semibold">{{ $items->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="text-gray-600">Delivery Address</div>
                                    <div class="font-medium text-gray-900">{{ auth()->user()->address ?? 'Please update your address' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-blue-50 p-6">
                            <h3 class="mb-2 font-bold text-blue-900">Need Help?</h3>
                            <p class="text-sm text-blue-700 mb-4">
                                Contact our support team if you have any questions about your order.
                            </p>
                            <a
                                href="{{ route('pages.support') }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700"
                            >
                                Contact Support →
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </body>
</html>