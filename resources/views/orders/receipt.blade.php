<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Receipt #{{ $order->id }} - Slice</title>
        @vite("resources/css/app.css")
        <style>
            @media print {
                body {
                    margin: 0;
                    padding: 20px;
                    background: white;
                }
                .no-print {
                    display: none !important;
                }
                .receipt-container {
                    max-width: 100% !important;
                    box-shadow: none !important;
                }
            }
            @page {
                size: A4;
                margin: 0;
            }
        </style>
    </head>
    <body class="bg-gray-50">
        <!-- Print/Download Button (hidden on print) -->
        <div class="no-print fixed top-4 right-4 z-50 flex gap-3">
            <button
                onclick="window.print()"
                class="flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-blue-700"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                    ></path>
                </svg>
                Print / Save as PDF
            </button>
            <a
                href="{{ route("orders.show", $order->id) }}"
                class="flex items-center gap-2 rounded-xl bg-gray-100 px-6 py-3 font-semibold text-gray-700 transition hover:bg-gray-200"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    ></path>
                </svg>
                Back to Order
            </a>
        </div>

        <!-- Receipt Container -->
        <div class="flex min-h-screen items-center justify-center p-4 sm:p-8">
            <div class="receipt-container mx-auto w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                <!-- Header with Logo -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-10 sm:px-12">
                    <div class="flex items-start justify-between">
                        <div>
                            <!-- Logo -->
                            <div class="mb-4">
                                <h1 class="text-4xl font-black tracking-tight text-white">SLICE</h1>
                                <p class="mt-1 text-sm text-blue-100">Apple Device Rental</p>
                            </div>
                            <h2 class="mb-2 text-2xl font-bold text-white">RECEIPT</h2>
                            <p class="text-sm text-blue-100">Order #{{ str_pad($order->id, 6, "0", STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="mb-1 text-sm text-blue-100">Date Issued</p>
                            <p class="font-semibold text-white">{{ $order->created_at->format("M d, Y") }}</p>
                            <p class="mt-1 text-xs text-blue-100">{{ $order->created_at->format("h:i A") }}</p>
                        </div>
                    </div>
                </div>

                <!-- Company & Customer Info -->
                <div class="grid grid-cols-1 gap-8 border-b border-gray-200 px-8 py-8 sm:grid-cols-2 sm:px-12">
                    <!-- From -->
                    <div>
                        <h3 class="mb-3 text-xs font-semibold tracking-wider text-gray-500 uppercase">From</h3>
                        <div class="text-gray-900">
                            <p class="mb-1 text-lg font-bold">Slice Technologies</p>
                            <p class="text-sm text-gray-600">Apple Device Rental Service</p>
                            <p class="mt-2 text-sm text-gray-600">Jakarta, Indonesia</p>
                            <p class="text-sm text-gray-600">support@slice.com</p>
                        </div>
                    </div>

                    <!-- To -->
                    <div>
                        <h3 class="mb-3 text-xs font-semibold tracking-wider text-gray-500 uppercase">Bill To</h3>
                        <div class="text-gray-900">
                            <p class="mb-1 text-lg font-bold">{{ $order->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                            @if ($order->user->phone)
                                <p class="text-sm text-gray-600">{{ $order->user->phone }}</p>
                            @endif

                            @if ($order->user->address)
                                <p class="mt-2 text-sm text-gray-600">{{ $order->user->address }}</p>
                            @endif

                            @if ($order->user->city)
                                <p class="text-sm text-gray-600">
                                    {{ $order->user->city }}@if ($order->user->zip_code), {{ $order->user->zip_code }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="px-8 py-8 sm:px-12">
                    <h3 class="mb-6 text-xs font-semibold tracking-wider text-gray-500 uppercase">Order Details</h3>

                    <!-- Table Header -->
                    <div class="mb-4 grid grid-cols-12 gap-4 border-b-2 border-gray-200 pb-3">
                        <div class="col-span-6 text-xs font-semibold text-gray-600 uppercase">Item</div>
                        <div class="col-span-2 text-center text-xs font-semibold text-gray-600 uppercase">Duration</div>
                        <div class="col-span-2 text-right text-xs font-semibold text-gray-600 uppercase">Rate</div>
                        <div class="col-span-2 text-right text-xs font-semibold text-gray-600 uppercase">Amount</div>
                    </div>

                    <!-- Order Item -->
                    <div class="grid grid-cols-12 gap-4 border-b border-gray-100 py-4">
                        <div class="col-span-6">
                            <p class="mb-1 font-semibold text-gray-900">{{ $order->device_name }}</p>
                            @if ($order->capacity)
                                <p class="text-sm text-gray-500">{{ $order->capacity }} Storage</p>
                            @endif

                            <p class="mt-1 text-xs text-gray-400">SKU: {{ strtoupper($order->variant_slug) }}</p>
                        </div>
                        <div class="col-span-2 text-center">
                            <p class="font-semibold text-gray-900">{{ $order->months }}</p>
                            <p class="text-xs text-gray-500">{{ Str::plural("month", $order->months) }}</p>
                        </div>
                        <div class="col-span-2 text-right">
                            <p class="font-semibold text-gray-900">{{ $order->formatted_price }}</p>
                            <p class="text-xs text-gray-500">/month</p>
                        </div>
                        <div class="col-span-2 text-right">
                            <p class="font-bold text-gray-900">{{ $order->formatted_total }}</p>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="mt-8 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-gray-600">Subtotal</p>
                            <p class="font-semibold text-gray-900">{{ $order->formatted_total }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-gray-600">Tax</p>
                            <p class="font-semibold text-gray-900">Rp 0</p>
                        </div>
                        <div class="mt-3 border-t-2 border-gray-300 pt-3">
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-bold text-gray-900">Total</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $order->formatted_total }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div
                        class="{{ $order->status === "paid" || $order->status === "active" ? "border border-green-200 bg-green-50" : "border border-yellow-200 bg-yellow-50" }} mt-8 rounded-xl p-4"
                    >
                        <div class="flex items-center gap-3">
                            @if ($order->status === "paid" || $order->status === "active")
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                    <svg
                                        class="h-6 w-6 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-green-900">Payment Confirmed</p>
                                    <p class="text-sm text-green-700">This order has been paid in full</p>
                                </div>
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100">
                                    <svg
                                        class="h-6 w-6 text-yellow-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-yellow-900">Payment {{ ucfirst($order->status) }}</p>
                                    <p class="text-sm text-yellow-700">Status: {{ ucfirst($order->status) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 bg-gray-50 px-8 py-8 sm:px-12">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <!-- Terms -->
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900">Terms & Conditions</h4>
                            <ul class="space-y-1 text-xs text-gray-600">
                                <li>• Rental period starts from delivery date</li>
                                <li>• Monthly payments are due on the same date each month</li>
                                <li>• Device must be returned in good condition</li>
                                <li>• Late returns may incur additional charges</li>
                            </ul>
                        </div>

                        <!-- Contact -->
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900">Need Help?</h4>
                            <p class="mb-2 text-xs text-gray-600">For questions about this receipt, please contact:</p>
                            <p class="text-sm font-medium text-blue-600">support@slice.com</p>
                            <p class="mt-4 text-xs text-gray-500">Thank you for choosing Slice!</p>
                        </div>
                    </div>

                    <!-- Receipt ID & Timestamp -->
                    <div class="mt-8 border-t border-gray-200 pt-6 text-center">
                        <p class="text-xs text-gray-400">
                            Receipt ID: RCP-{{ str_pad($order->id, 8, "0", STR_PAD_LEFT) }} | Generated:
                            {{ now()->format('M d, Y \a\t h:i A') }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">This is an official receipt from Slice Technologies</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
