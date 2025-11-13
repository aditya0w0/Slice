<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Checkout - Slice</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap"
            rel="stylesheet"
        />
        @vite("resources/css/app.css")
        <style>
            .step-inactive {
                opacity: 0.5;
                cursor: not-allowed;
            }
            .step-active {
                opacity: 1;
            }
            .step-complete {
                opacity: 1;
            }
            .content-section {
                display: none;
            }
            .content-section.active {
                display: block;
            }
            @media print {
                @page {
                    size: letter;
                    margin: 0.5in;
                }
                body {
                    margin: 0;
                    font-size: 9px;
                    line-height: 1.1;
                }
                .no-print {
                    display: none !important;
                }
                main {
                    padding: 5mm;
                    max-width: none;
                }
                .rounded-2xl {
                    border-radius: 0;
                }
                .shadow-sm {
                    box-shadow: none;
                }
                input, button {
                    display: none;
                }
                .grid {
                    display: block;
                }
                .lg\\:col-span-2, .xl\\:col-span-3 {
                    width: 100%;
                }
                h1, h2, h3 {
                    font-size: 14px;
                    margin: 5px 0;
                }
                p, span, div {
                    font-size: 10px;
                }
                .mb-8, .mb-6, .mb-4, .py-8, .py-6, .p-6, .p-4 {
                    margin-bottom: 2px !important;
                    padding: 1px !important;
                }
                .gap-8, .gap-6, .gap-4 {
                    gap: 2px !important;
                }
                .space-y-6 > * + * {
                    margin-top: 2px !important;
                }
            }
        </style>
    </head>
    <body class="bg-gray-50" data-auth-required="true">
        <div class="no-print">
            @include("partials.header")
        </div>

        <main class="mx-auto min-h-screen max-w-[1400px] px-6 py-8">
            <!-- Progress Steps -->
            <div class="mb-8 flex items-center justify-center gap-4">
                <div class="step-indicator flex items-center gap-2" data-step="1">
                    <div
                        class="step-circle flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white"
                    >
                        1
                    </div>
                    <span class="step-label font-medium text-gray-900">Delivery Info</span>
                </div>
                <div class="step-line h-px w-16 bg-gray-300"></div>
                <div class="step-indicator step-inactive flex items-center gap-2" data-step="2">
                    <div
                        class="step-circle flex h-8 w-8 items-center justify-center rounded-full bg-gray-300 text-sm font-semibold text-gray-600"
                    >
                        2
                    </div>
                    <span class="step-label font-medium text-gray-400">Payment</span>
                </div>
                <div class="step-line h-px w-16 bg-gray-300"></div>
                <div class="step-indicator step-inactive flex items-center gap-2" data-step="3">
                    <div
                        class="step-circle flex h-8 w-8 items-center justify-center rounded-full bg-gray-300 text-sm font-semibold text-gray-600"
                    >
                        3
                    </div>
                    <span class="step-label font-medium text-gray-400">Complete</span>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3 xl:grid-cols-5">
                <!-- Left Column - Dynamic Content -->
                <div class="lg:col-span-2 xl:col-span-3">
                    <div class="space-y-6">
                        <!-- STEP 1: Delivery Information -->
                        <div class="content-section active" id="step-1">
                            <div class="rounded-2xl bg-white p-6 shadow-sm">
                                <div class="mb-4 flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-gray-900">Delivery Information</h2>
                                    <button
                                        type="button"
                                        onclick="window.history.back()"
                                        class="text-gray-400 hover:text-gray-600"
                                    >
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <div class="mb-6">
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Full Name *</label>
                                    <input
                                        type="text"
                                        id="full_name"
                                        value="{{ old("full_name", auth()->user()->legal_name ?? auth()->user()->name) }}"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                        required
                                    />
                                </div>

                                <div class="mb-6">
                                    <div class="mb-3 flex items-center justify-between">
                                        <label class="block text-sm font-medium text-gray-900">Delivery Location</label>
                                        <button
                                            type="button"
                                            onclick="openAddressModal()"
                                            class="flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 4v16m8-8H4"
                                                />
                                            </svg>
                                            Change Address
                                        </button>
                                    </div>

                                    <div
                                        id="selected-address"
                                        class="rounded-xl border-2 border-blue-500 bg-blue-50 p-4"
                                    >
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-600"
                                            >
                                                <svg
                                                    class="h-5 w-5 text-white"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="mb-1 flex items-center gap-2">
                                                    <p id="address-label" class="font-semibold text-gray-900">Home</p>
                                                    <span
                                                        class="rounded-full bg-blue-600 px-2 py-0.5 text-xs font-medium text-white"
                                                    >
                                                        Selected
                                                    </span>
                                                </div>
                                                <p id="address-display" class="mb-2 text-sm text-gray-600">
                                                    @if (auth()->user()->address)
                                                        {{ auth()->user()->address }}@if (auth()->user()->city), {{ auth()->user()->city }}
                                                        @endif
                                                        @if (auth()->user()->zip_code)
                                                            {{ auth()->user()->zip_code }}
                                                        @endif
                                                    @else
                                                            Click "Change Address" to add your delivery location
                                                    @endif
                                                </p>
                                                <p
                                                    id="address-phone"
                                                    class="flex items-center gap-1 text-sm text-gray-500"
                                                >
                                                    <svg
                                                        class="h-3.5 w-3.5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                                        />
                                                    </svg>
                                                    <span>{{ auth()->user()->phone ?? "No phone number" }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="hidden">
                                    <input
                                        type="text"
                                        id="address"
                                        value="{{ old("address", auth()->user()->address) }}"
                                    />
                                    <input type="text" id="city" value="{{ old("city", auth()->user()->city) }}" />
                                    <input
                                        type="text"
                                        id="zip_code"
                                        value="{{ old("zip_code", auth()->user()->zip_code) }}"
                                    />
                                    <input type="text" id="phone" value="{{ old("phone", auth()->user()->phone) }}" />
                                </div>

                                <button
                                    type="button"
                                    onclick="goToStep(2)"
                                    class="w-full rounded-xl bg-blue-600 px-8 py-3.5 font-semibold text-white transition-all hover:bg-blue-700"
                                >
                                    Continue to Payment
                                </button>
                            </div>
                        </div>

                        <!-- STEP 2: Payment Method -->
                        <div class="content-section" id="step-2">
                            <div class="rounded-2xl bg-white p-6 shadow-sm">
                                <h2 class="mb-4 text-xl font-bold text-gray-900">Payment Method</h2>

                                <!-- Credit Card Option -->
                                <div class="mb-4">
                                    <button
                                        type="button"
                                        onclick="togglePaymentSection()"
                                        class="flex w-full items-center justify-between rounded-xl border-2 border-gray-300 p-4 transition-colors hover:border-blue-500"
                                    >
                                        <span class="font-medium text-gray-900">Credit Card</span>
                                        <svg
                                            id="payment-toggle-icon"
                                            class="h-5 w-5 text-gray-400 transition-transform"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 9l-7 7-7-7"
                                            />
                                        </svg>
                                    </button>

                                    <div id="payment-form" class="mt-4 hidden space-y-4">
                                        <!-- Payment cards selection -->
                                        <div class="grid grid-cols-4 gap-3">
                                            <button
                                                type="button"
                                                class="payment-card-btn active flex aspect-square items-center justify-center rounded-lg border-2 border-blue-600 bg-white p-3 transition-colors hover:border-blue-600"
                                            >
                                                <img
                                                    src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg"
                                                    alt="VISA"
                                                    class="h-6"
                                                />
                                            </button>
                                            <button
                                                type="button"
                                                class="payment-card-btn flex aspect-square items-center justify-center rounded-lg border-2 border-gray-200 bg-white p-3 transition-colors hover:border-blue-600"
                                            >
                                                <img
                                                    src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                                    alt="Mastercard"
                                                    class="h-6"
                                                />
                                            </button>
                                            <button
                                                type="button"
                                                class="payment-card-btn flex aspect-square items-center justify-center rounded-lg border-2 border-gray-200 bg-white p-3 transition-colors hover:border-blue-600"
                                            >
                                                <img
                                                    src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg"
                                                    alt="Citi"
                                                    class="h-6"
                                                />
                                            </button>
                                            <button
                                                type="button"
                                                class="payment-card-btn flex aspect-square items-center justify-center rounded-lg border-2 border-gray-200 bg-white p-3 transition-colors hover:border-blue-600"
                                            >
                                                <span class="text-xs font-bold text-gray-600">Other</span>
                                            </button>
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                                Card Number
                                            </label>
                                            <input
                                                type="text"
                                                id="card_number"
                                                placeholder="1234 5678 9012 3456"
                                                maxlength="19"
                                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                            />
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-gray-700">
                                                    Expiry Date
                                                </label>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <select
                                                        id="exp_month"
                                                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                                    >
                                                        <option value="">MM</option>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}">
                                                                {{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <select
                                                        id="exp_year"
                                                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                                    >
                                                        <option value="">YYYY</option>
                                                        @for ($i = 2025; $i <= 2035; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-gray-700">CVV</label>
                                                <input
                                                    type="text"
                                                    id="cvv"
                                                    placeholder="123"
                                                    maxlength="3"
                                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3 pt-4">
                                    <button
                                        type="button"
                                        onclick="goToStep(1)"
                                        class="flex-1 rounded-xl border-2 border-gray-300 px-8 py-3.5 font-semibold text-gray-700 transition-all hover:bg-gray-50"
                                    >
                                        Back
                                    </button>
                                    <button
                                        type="button"
                                        onclick="goToStep(3)"
                                        class="flex-1 rounded-xl bg-blue-600 px-8 py-3.5 font-semibold text-white transition-all hover:bg-blue-700"
                                    >
                                        Review Order
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: Final Confirmation -->
                        <div class="content-section" id="step-3">
                            <div class="rounded-2xl bg-white p-6 shadow-sm">
                                <h2 class="mb-4 text-xl font-bold text-gray-900">Confirm Your Rental</h2>

                                <!-- Order Summary -->
                                <div class="mb-6 space-y-4">
                                    <div class="border-b pb-4">
                                        <h3 class="mb-3 font-semibold text-gray-900">Delivery Information</h3>
                                        <div class="grid gap-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Name:</span>
                                                <span id="review-name" class="font-medium">-</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Phone:</span>
                                                <span id="review-phone" class="font-medium">-</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Address:</span>
                                                <span id="review-address" class="font-medium">-</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-b pb-4">
                                        <h3 class="mb-3 font-semibold text-gray-900">Payment Method</h3>
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-16 items-center justify-center rounded bg-gray-100">
                                                <span class="text-xs font-bold text-blue-600">VISA</span>
                                            </div>
                                            <div>
                                                <p class="font-medium">Credit Card</p>
                                                <p id="review-card" class="text-sm text-gray-600">
                                                    •••• •••• •••• ••••
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms Checkbox -->
                                <div class="mb-6">
                                    <label class="flex cursor-pointer items-start gap-2">
                                        <input
                                            type="checkbox"
                                            id="terms-checkbox"
                                            class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            required
                                        />
                                        <span class="text-sm text-gray-600">
                                            By clicking this, I agree to Slice's
                                            <button
                                                type="button"
                                                onclick="showTermsModal()"
                                                class="text-blue-600 hover:underline"
                                            >
                                                Terms & Conditions
                                            </button>
                                            and
                                            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                                        </span>
                                    </label>
                                </div>

                                <div class="flex gap-3">
                                    <button
                                        type="button"
                                        onclick="goToStep(2)"
                                        class="flex-1 rounded-xl border-2 border-gray-300 px-8 py-3.5 font-semibold text-gray-700 transition-all hover:bg-gray-50"
                                    >
                                        Back
                                    </button>
                                    <button
                                        type="button"
                                        onclick="submitOrder()"
                                        class="flex-1 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-3.5 font-semibold text-white shadow-lg transition-all hover:shadow-xl"
                                    >
                                        Proceed to Payment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary & Rental Info (Sticky) -->
                <div class="lg:col-span-1 xl:col-span-2">
                    <div class="sticky top-6 max-h-[calc(100vh-3rem)] space-y-4 overflow-y-auto">
                        <!-- Order Summary Card -->
                        <div class="rounded-2xl bg-white p-6 shadow-sm">
                            <!-- Device Card -->
                            <div class="mb-6 flex gap-4 border-b pb-6">
                                @if ($device->image)
                                    <img
                                        src="{{ $device->image }}"
                                        alt="{{ $device->name }}"
                                        class="h-24 w-24 rounded-xl bg-gray-100 object-cover"
                                    />
                                @else
                                    <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-gray-100">
                                        <svg
                                            class="h-12 w-12 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $device->name }}</h3>
                                    @if ($capacity)
                                        <p class="mt-1 text-sm text-gray-600">Size: {{ $capacity }}</p>
                                    @endif

                                    <p class="mt-2 text-lg font-bold text-gray-900">
                                        Rp {{ number_format($device->price_monthly, 0, ",", ".") }}
                                    </p>
                                </div>
                            </div>

                            <div class="mb-4 space-y-3 border-b pb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">
                                        Rp {{ number_format($subtotal, 0, ",", ".") }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Rental Duration</span>
                                    <span class="font-medium text-gray-900">{{ $months }} months</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Quantity</span>
                                    <span class="font-medium text-gray-900">{{ $quantity }} unit(s)</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Taxes</span>
                                    <span class="font-medium text-gray-900">Rp 0</span>
                                </div>
                            </div>

                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-gray-900">
                                    Rp {{ number_format($total, 0, ",", ".") }}
                                </span>
                            </div>

                            <!-- Promo Code (Optional) -->
                            <div class="border-t pt-4">
                                <p class="mb-2 text-sm font-medium text-gray-900">Do you have a promotional code?</p>
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        placeholder="Enter code"
                                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                    />
                                    <button
                                        class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white transition-colors hover:bg-gray-800"
                                    >
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Full-Width Card - Compact Info Bar -->
            <div class="mt-6">
                <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
                    <div class="grid divide-x divide-gray-100 lg:grid-cols-3">
                        <!-- Rental Information -->
                        <div class="p-6">
                            <div class="mb-3 flex items-center gap-3">
                                <div class="rounded-lg bg-blue-50 p-2">
                                    <svg
                                        class="h-5 w-5 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $months }} Month Rental</h3>
                                    <p class="text-xs text-gray-600">Flexible terms</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Certified devices, 1-2 day delivery, warranty included</p>
                        </div>

                        <!-- Security -->
                        <div class="p-6">
                            <div class="mb-3 flex items-center gap-3">
                                <div class="rounded-lg bg-green-50 p-2">
                                    <svg
                                        class="h-5 w-5 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Secure Payment</h3>
                                    <p class="text-xs text-gray-600">SSL & PCI Compliant</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Bank-level encryption protects your payment</p>
                        </div>

                        <!-- Support -->
                        <div class="p-6">
                            <div class="mb-3 flex items-center gap-3">
                                <div class="rounded-lg bg-blue-50 p-2">
                                    <svg
                                        class="h-5 w-5 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">24/7 Support</h3>
                                    <p class="text-xs text-gray-600">Always available</p>
                                </div>
                            </div>
                            <button class="text-sm font-medium text-blue-600 transition-colors hover:text-blue-700">
                                Contact Support →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Address Selection Modal -->
        <div id="address-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
            <div class="relative flex max-h-[85vh] w-full max-w-2xl flex-col overflow-hidden rounded-3xl bg-white">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-200 p-6">
                    <h2 class="text-2xl font-bold text-gray-900">Select Delivery Location</h2>
                    <button onclick="closeAddressModal()" class="p-1 text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Saved Addresses List -->
                <div class="flex-1 overflow-y-auto p-6">
                    <div id="addresses-list" class="space-y-3">
                        <!-- Address cards will be dynamically inserted here -->
                    </div>
                </div>

                <!-- Add New Address Button -->
                <div class="border-t border-gray-200 bg-gray-50 p-6">
                    <button
                        onclick="showAddAddressForm()"
                        class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 px-6 py-4 text-gray-600 transition-all hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="font-semibold">Add New Address</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Add/Edit Address Form Modal -->
        <div id="address-form-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/50 p-4">
            <div class="relative w-full max-w-lg rounded-3xl bg-white p-8">
                <button
                    onclick="closeAddressFormModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
                <h2 id="form-modal-title" class="mb-6 text-2xl font-bold text-gray-900">Add New Address</h2>

                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Address Label</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button
                                type="button"
                                onclick="selectAddressType('Home')"
                                class="address-type-btn flex items-center justify-center gap-2 rounded-lg border-2 border-gray-300 px-4 py-3 font-medium transition-colors hover:border-blue-500"
                                data-type="Home"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>
                                <span>Home</span>
                            </button>
                            <button
                                type="button"
                                onclick="selectAddressType('Office')"
                                class="address-type-btn flex items-center justify-center gap-2 rounded-lg border-2 border-gray-300 px-4 py-3 font-medium transition-colors hover:border-blue-500"
                                data-type="Office"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                <span>Office</span>
                            </button>
                            <button
                                type="button"
                                onclick="selectAddressType('Other')"
                                class="address-type-btn flex items-center justify-center gap-2 rounded-lg border-2 border-gray-300 px-4 py-3 font-medium transition-colors hover:border-blue-500"
                                data-type="Other"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <span>Other</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Street Address *</label>
                        <textarea
                            id="modal-address"
                            placeholder="Enter your complete street address"
                            rows="2"
                            class="w-full resize-none rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">City *</label>
                            <input
                                type="text"
                                id="modal-city"
                                placeholder="City"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Zip Code *</label>
                            <input
                                type="text"
                                id="modal-zip"
                                placeholder="Zip Code"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Phone Number (Optional)</label>
                        <input
                            type="tel"
                            id="modal-phone"
                            placeholder="+62 888 999 1222"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                        <input
                            type="text"
                            id="modal-notes"
                            placeholder="e.g., Building name, floor, landmark"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        />
                    </div>

                    <button
                        onclick="saveNewAddress()"
                        class="mt-6 w-full rounded-xl bg-blue-600 px-8 py-3.5 font-semibold text-white transition-all hover:bg-blue-700"
                    >
                        Save Address
                    </button>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions Modal -->
        <div id="terms-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
            <div class="relative max-h-[80vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white p-8">
                <button onclick="closeTermsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
                <h2 class="mb-4 text-2xl font-bold text-gray-900">Terms & Conditions</h2>
                <div class="prose prose-sm max-w-none text-gray-600">
                    <h3 class="font-semibold text-gray-900">1. Rental Agreement</h3>
                    <p>
                        By renting a device from Slice, you agree to return the device in the same condition as
                        received, normal wear and tear excepted.
                    </p>

                    <h3 class="mt-4 font-semibold text-gray-900">2. Payment Terms</h3>
                    <p>
                        Payment is due at the time of booking. Monthly rental fees are non-refundable except as provided
                        in our cancellation policy.
                    </p>

                    <h3 class="mt-4 font-semibold text-gray-900">3. Device Care</h3>
                    <p>
                        You are responsible for the device during the rental period. Any damage beyond normal wear and
                        tear may result in additional charges.
                    </p>

                    <h3 class="mt-4 font-semibold text-gray-900">4. Late Returns</h3>
                    <p>
                        Late returns will incur additional daily charges. Please contact us if you need to extend your
                        rental period.
                    </p>

                    <h3 class="mt-4 font-semibold text-gray-900">5. Cancellation</h3>
                    <p>
                        Free cancellation up to 12 hours before pickup. Cancellations after this time may be subject to
                        fees.
                    </p>
                </div>
                <button
                    onclick="closeTermsModal()"
                    class="mt-6 w-full rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
                >
                    I Understand
                </button>
            </div>
        </div>

        <script>
            let currentStep = 1;
            let selectedAddressType = 'Home';

            // Sample addresses - in production, this would come from your database
            let savedAddresses = [
                @if(auth()->user()->address)
                {
                    id: 1,
                    type: 'Home',
                    address: '{{ auth()->user()->address }}',
                    city: '{{ auth()->user()->city ?? '' }}',
                    zip: '{{ auth()->user()->zip_code ?? '' }}',
                    phone: '{{ auth()->user()->phone ?? '' }}',
                    notes: ''
                },
                @endif
                {
                    id: 2,
                    type: 'Office',
                    address: 'Pacific Century Place, SCBD Lot 10',
                    city: 'Jakarta',
                    zip: '12190',
                    phone: '+62 21 5797 3700',
                    notes: 'Tower A, 15th Floor'
                },
                {
                    id: 3,
                    type: 'Home',
                    address: 'Jl. Kemang Raya No. 45',
                    city: 'Jakarta Selatan',
                    zip: '12730',
                    phone: '+62 812 3456 7890',
                    notes: 'Green house with white fence'
                }
            ];

            function getAddressIcon(type) {
                const icons = {
                    'Home': '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                    'Office': '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                    'Other': '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
                };
                return icons[type] || icons['Other'];
            }

            function openAddressModal() {
                renderAddressList();
                document.getElementById('address-modal').classList.remove('hidden');
                document.getElementById('address-modal').classList.add('flex');
            }

            function closeAddressModal() {
                document.getElementById('address-modal').classList.add('hidden');
                document.getElementById('address-modal').classList.remove('flex');
            }

            function renderAddressList() {
                const container = document.getElementById('addresses-list');
                container.innerHTML = '';

                savedAddresses.forEach(addr => {
                    const addressCard = document.createElement('div');
                    addressCard.className = 'relative rounded-xl border-2 border-gray-200 p-4 hover:border-blue-500 cursor-pointer transition-all bg-white';
                    addressCard.onclick = () => selectAddress(addr);

                    addressCard.innerHTML = `
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 shrink-0">
                                ${getAddressIcon(addr.type)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-gray-900">${addr.type}</p>
                                </div>
                                <p class="text-sm text-gray-600 mb-1">${addr.address}</p>
                                <p class="text-sm text-gray-500">${addr.city} ${addr.zip}</p>
                                ${addr.phone ? `<p class="text-sm text-gray-500 mt-1 flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> ${addr.phone}</p>` : ''}
                                ${addr.notes ? `<p class="text-xs text-gray-400 mt-1">${addr.notes}</p>` : ''}
                            </div>
                            <button onclick="event.stopPropagation(); deleteAddress(${addr.id})" class="text-red-500 hover:text-red-700 p-1">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    `;

                    container.appendChild(addressCard);
                });
            }

            function selectAddress(addr) {
                // Update hidden inputs
                document.getElementById('address').value = addr.address;
                document.getElementById('city').value = addr.city;
                document.getElementById('zip_code').value = addr.zip;
                document.getElementById('phone').value = addr.phone || '';

                // Update display
                document.getElementById('address-label').textContent = addr.type;
                document.getElementById('address-display').textContent = `${addr.address}, ${addr.city} ${addr.zip}`;
                document.getElementById('address-phone').innerHTML = `
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span>${addr.phone || 'No phone number'}</span>
                `;

                closeAddressModal();
            }

            function showAddAddressForm() {
                document.getElementById('form-modal-title').textContent = 'Add New Address';
                selectedAddressType = 'Home';

                // Clear form
                document.getElementById('modal-address').value = '';
                document.getElementById('modal-city').value = '';
                document.getElementById('modal-zip').value = '';
                document.getElementById('modal-phone').value = '';
                document.getElementById('modal-notes').value = '';

                // Reset address type buttons
                document.querySelectorAll('.address-type-btn').forEach(btn => {
                    btn.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600');
                    btn.classList.add('border-gray-300');
                });
                document.querySelector('[data-type="Home"]').classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600');

                document.getElementById('address-form-modal').classList.remove('hidden');
                document.getElementById('address-form-modal').classList.add('flex');
            }

            function closeAddressFormModal() {
                document.getElementById('address-form-modal').classList.add('hidden');
                document.getElementById('address-form-modal').classList.remove('flex');
            }

            function selectAddressType(type) {
                selectedAddressType = type;
                document.querySelectorAll('.address-type-btn').forEach(btn => {
                    btn.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600');
                    btn.classList.add('border-gray-300');
                });
                event.target.classList.remove('border-gray-300');
                event.target.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600');
            }

            function saveNewAddress() {
                const address = document.getElementById('modal-address').value.trim();
                const city = document.getElementById('modal-city').value.trim();
                const zip = document.getElementById('modal-zip').value.trim();
                const phone = document.getElementById('modal-phone').value.trim();
                const notes = document.getElementById('modal-notes').value.trim();

                if (!address || !city || !zip) {
                    alert('Please fill in all required fields (Address, City, Zip Code)');
                    return;
                }

                // Add new address to the list
                const newAddress = {
                    id: savedAddresses.length + 1,
                    type: selectedAddressType,
                    address: address,
                    city: city,
                    zip: zip,
                    phone: phone,
                    notes: notes
                };

                savedAddresses.push(newAddress);

                // Select the newly added address
                selectAddress(newAddress);

                closeAddressFormModal();
                closeAddressModal();
            }

            function deleteAddress(id) {
                if (confirm('Are you sure you want to delete this address?')) {
                    savedAddresses = savedAddresses.filter(addr => addr.id !== id);
                    renderAddressList();
                }
            }

            function togglePaymentSection() {
                const form = document.getElementById('payment-form');
                const icon = document.getElementById('payment-toggle-icon');
                form.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            }

            function goToStep(step) {
                // Validate current step before moving forward
                if (step > currentStep) {
                    if (!validateStep(currentStep)) {
                        return;
                    }
                }

                // Hide all sections
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.remove('active');
                });

                // Show target section
                document.getElementById('step-' + step).classList.add('active');

                // Update step indicators
                updateStepIndicators(step);

                // Update review data if going to step 3
                if (step === 3) {
                    updateReview();
                }

                currentStep = step;

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function validateStep(step) {
                if (step === 1) {
                    const fullName = document.getElementById('full_name').value.trim();
                    const phone = document.getElementById('phone').value.trim();
                    const address = document.getElementById('address').value.trim();

                    if (!fullName || !phone || !address) {
                        alert('Please fill in all required delivery information fields.');
                        return false;
                    }
                    return true;
                }

                if (step === 2) {
                    const cardNumber = document.getElementById('card_number').value.trim();
                    const expMonth = document.getElementById('exp_month').value;
                    const expYear = document.getElementById('exp_year').value;
                    const cvv = document.getElementById('cvv').value.trim();

                    if (!cardNumber || !expMonth || !expYear || !cvv) {
                        alert('Please fill in all required payment information fields.');
                        return false;
                    }

                    if (cardNumber.replace(/\s/g, '').length < 13) {
                        alert('Please enter a valid card number.');
                        return false;
                    }

                    if (cvv.length < 3) {
                        alert('Please enter a valid CVV.');
                        return false;
                    }

                    return true;
                }

                return true;
            }

            function updateStepIndicators(activeStep) {
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNumber = index + 1;
                    const circle = indicator.querySelector('.step-circle');
                    const label = indicator.querySelector('.step-label');

                    indicator.classList.remove('step-active', 'step-inactive', 'step-complete');

                    if (stepNumber < activeStep) {
                        // Completed step
                        indicator.classList.add('step-complete');
                        circle.classList.remove('bg-gray-300', 'bg-blue-600');
                        circle.classList.add('bg-green-600');
                        circle.querySelector('.text-gray-600')?.classList.remove('text-gray-600');
                        circle.querySelector('.text-sm')?.classList.add('text-white');
                        label.classList.remove('text-gray-400');
                        label.classList.add('text-green-600');
                    } else if (stepNumber === activeStep) {
                        // Active step
                        indicator.classList.add('step-active');
                        circle.classList.remove('bg-gray-300', 'bg-green-600');
                        circle.classList.add('bg-blue-600');
                        circle.querySelector('.text-sm')?.classList.remove('text-gray-600');
                        circle.querySelector('.text-sm')?.classList.add('text-white');
                        label.classList.remove('text-gray-400', 'text-green-600');
                        label.classList.add('text-gray-900');
                    } else {
                        // Inactive step
                        indicator.classList.add('step-inactive');
                        circle.classList.remove('bg-blue-600', 'bg-green-600');
                        circle.classList.add('bg-gray-300');
                        circle.querySelector('.text-white')?.classList.remove('text-white');
                        circle.querySelector('.text-sm')?.classList.add('text-gray-600');
                        label.classList.remove('text-gray-900', 'text-green-600');
                        label.classList.add('text-gray-400');
                    }
                });
            }

            function updateReview() {
                // Update delivery info
                document.getElementById('review-name').textContent = document.getElementById('full_name').value;
                document.getElementById('review-phone').textContent = document.getElementById('phone').value;
                document.getElementById('review-address').textContent = document.getElementById('address').value;

                // Update payment info
                const cardNumber = document.getElementById('card_number').value;
                const last4 = cardNumber.replace(/\s/g, '').slice(-4);
                document.getElementById('review-card').textContent = '•••• •••• •••• ' + last4;
            }

            function submitOrder() {
                const termsChecked = document.getElementById('terms-checkbox').checked;
                if (!termsChecked) {
                    alert('Please agree to the Terms & Conditions');
                    return;
                }

                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("checkout.confirm") }}';

                // CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Device info
                const fields = {
                    'device_slug': '{{ $device->slug }}',
                    'months': '{{ $months }}',
                    'quantity': '{{ $quantity }}',
                    'capacity': '{{ $capacity }}',
                    'full_name': document.getElementById('full_name').value,
                    'phone': document.getElementById('phone').value,
                    'address': document.getElementById('address').value,
                    'city': document.getElementById('city')?.value || 'N/A',
                    'zip_code': document.getElementById('zip_code')?.value || '00000',
                };

                Object.keys(fields).forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = fields[key];
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }

            function showTermsModal() {
                document.getElementById('terms-modal').classList.remove('hidden');
                document.getElementById('terms-modal').classList.add('flex');
            }

            function closeTermsModal() {
                document.getElementById('terms-modal').classList.add('hidden');
                document.getElementById('terms-modal').classList.remove('flex');
            }

            // Payment card selection
            document.querySelectorAll('.payment-card-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.payment-card-btn').forEach(b => {
                        b.classList.remove('border-blue-600', 'active');
                        b.classList.add('border-gray-200');
                    });
                    this.classList.remove('border-gray-200');
                    this.classList.add('border-blue-600', 'active');
                });
            });

            // Format card number input
            document.getElementById('card_number')?.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '');
                let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formattedValue;
            });

            // CVV validation
            document.getElementById('cvv')?.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });

            // Close modal on overlay click
            document.getElementById('terms-modal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeTermsModal();
                }
            });
        </script>
    </body>
</html>
