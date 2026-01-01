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
                input,
                button {
                    display: none;
                }
                .grid {
                    display: block;
                }
                .lg\\:col-span-2,
                .xl\\:col-span-3 {
                    width: 100%;
                }
                h1,
                h2,
                h3 {
                    font-size: 14px;
                    margin: 5px 0;
                }
                p,
                span,
                div {
                    font-size: 10px;
                }
                .mb-8,
                .mb-6,
                .mb-4,
                .py-8,
                .py-6,
                .p-6,
                .p-4 {
                    margin-bottom: 2px !important;
                    padding: 1px !important;
                }
                .gap-8,
                .gap-6,
                .gap-4 {
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
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-start gap-3">
                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-900">Berhasil!</p>
                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-red-900">Kesalahan!</p>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-100 rounded-2xl flex items-start gap-3">
                <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-yellow-900">Peringatan!</p>
                    <p class="text-sm text-yellow-700 mt-1">{{ session('warning') }}</p>
                </div>
            </div>
            @endif

            @if(session('info'))
            <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-2xl flex items-start gap-3">
                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-blue-900">Informasi</p>
                    <p class="text-sm text-blue-700 mt-1">{{ session('info') }}</p>
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-red-900">Harap perbaiki kesalahan berikut:</p>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

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
                                <h2 class="mb-6 text-2xl font-bold text-gray-900">Payment Information</h2>

                                <!-- Animated Card Preview -->
                                <div class="mb-8">
                                    <div id="card-preview" class="card-container mx-auto" style="perspective: 1000px; max-width: 380px; height: 240px;">
                                        <div class="card-flip" style="position: relative; width: 100%; height: 100%; transition: transform 0.6s; transform-style: preserve-3d;">
                                            <!-- Card Front -->
                                            <div class="card-front" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 24px; color: white; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                                                <div class="flex justify-between items-start mb-8">
                                                    <svg class="h-10 w-12" viewBox="0 0 48 48" fill="none">
                                                        <rect width="48" height="48" rx="8" fill="white" opacity="0.2"/>
                                                        <path d="M24 14L16 24H24L20 34L32 20H24L28 14H24Z" fill="white"/>
                                                    </svg>
                                                    <div id="card-type-logo" class="h-10 flex items-center justify-center">
                                                        <span class="text-xs font-semibold opacity-60">CARD</span>
                                                    </div>
                                                </div>
                                                <div class="mb-6">
                                                    <div id="card-number-display" class="text-xl font-mono tracking-wider" style="letter-spacing: 0.1em;">
                                                        •••• •••• •••• ••••
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-end">
                                                    <div class="flex-1">
                                                        <div class="text-xs opacity-60 mb-1">Card Holder</div>
                                                        <div id="card-name-display" class="font-medium text-sm">YOUR NAME</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs opacity-60 mb-1">Expires</div>
                                                        <div id="card-expiry-display" class="font-medium text-sm">MM/YY</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Card Back -->
                                            <div class="card-back" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; transform: rotateY(180deg); background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 24px; color: white; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                                                <div class="h-12 bg-black opacity-80 -mx-6 mt-6 mb-8"></div>
                                                <div class="flex justify-end mb-4">
                                                    <div class="bg-white px-4 py-2 rounded text-black font-mono">
                                                        <span id="card-cvv-display">•••</span>
                                                    </div>
                                                </div>
                                                <div class="text-xs opacity-60 text-center">
                                                    CVV is the 3 or 4 digit number on the back of your card
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Form -->
                                <div class="space-y-5">
                                    <!-- Card Number with Auto-Detection -->
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                                            Card Number
                                        </label>
                                        <div class="relative">
                                            <input
                                                type="text"
                                                id="card_number"
                                                placeholder="1234 5678 9012 3456"
                                                maxlength="19"
                                                autocomplete="cc-number"
                                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3.5 pr-14 text-lg font-mono transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                            />
                                            <div id="detected-card-icon" class="absolute right-4 top-1/2 -translate-y-1/2 h-8 w-12 flex items-center justify-center">
                                                <!-- Card icon will appear here -->
                                            </div>
                                        </div>
                                        <p id="card-error" class="mt-1 text-sm text-red-600 hidden"></p>
                                    </div>

                                    <!-- Cardholder Name -->
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                                            Cardholder Name
                                        </label>
                                        <input
                                            type="text"
                                            id="card_name"
                                            placeholder="JOHN DOE"
                                            autocomplete="cc-name"
                                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3.5 text-lg uppercase transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                        />
                                    </div>

                                    <!-- Expiry & CVV Row -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                Expiry Date
                                            </label>
                                            <input
                                                type="text"
                                                id="card_expiry"
                                                placeholder="MM / YY"
                                                maxlength="7"
                                                autocomplete="cc-exp"
                                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3.5 text-lg font-mono transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                            />
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                CVV
                                            </label>
                                            <input
                                                type="text"
                                                id="cvv"
                                                placeholder="123"
                                                maxlength="4"
                                                autocomplete="cc-csc"
                                                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3.5 text-lg font-mono transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-8 flex gap-3">
                                    <button
                                        type="button"
                                        onclick="goToStep(1)"
                                        class="flex-1 rounded-xl border-2 border-gray-300 px-8 py-4 font-semibold text-gray-700 transition-all hover:bg-gray-50 hover:border-gray-400"
                                    >
                                        ← Back
                                    </button>
                                    <button
                                        type="button"
                                        id="review-order-btn"
                                        onclick="validateAndProceed()"
                                        class="flex-1 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-4 font-semibold text-white shadow-lg shadow-blue-500/30 transition-all hover:shadow-xl hover:shadow-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Review Order →
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

            // === MODERN PAYMENT CARD SYSTEM ===
            
            let detectedCardType = null;

            // Card type detection based on BIN (Bank Identification Number)
            function detectCardType(cardNumber) {
                const cleanNumber = cardNumber.replace(/\s/g, '');
                
                // Visa: starts with 4
                if (/^4/.test(cleanNumber)) return 'visa';
                
                // Mastercard: 51-55, 2221-2720
                if (/^5[1-5]/.test(cleanNumber) || /^2[2-7]/.test(cleanNumber)) return 'mastercard';
                
                // American Express: 34, 37
                if (/^3[47]/.test(cleanNumber)) return 'amex';
                
                // Discover: 6011, 622126-622925, 644-649, 65
                if (/^6011|^64[4-9]|^65/.test(cleanNumber)) return 'discover';
                
                return null;
            }

            // Get card type logo SVG
            function getCardLogo(type) {
                const logos = {
                    visa: '<svg viewBox="0 0 48 24" class="h-8"><path fill="#1434CB" d="M19.5 5L17 19h-3l2.5-14h3zm11 0l-5 14h-3l-1-8-2 8h-3l3-14h3l1 8 2-8h3zm8 0c1.5 0 2.5 1 2.5 2.5v9c0 1.5-1 2.5-2.5 2.5h-5l1-2h4c.5 0 1-.5 1-1v-9c0-.5-.5-1-1-1h-4l-1-2h5zm-19 6c-1.5 0-2.5-1-2.5-2.5v-1c0-1.5 1-2.5 2.5-2.5h4l-1 2h-3c-.5 0-1 .5-1 1v1c0 .5.5 1 1 1h3l-1 2h-2z"/></svg>',
                    mastercard: '<svg viewBox="0 0 48 32" class="h-8"><circle cx="15" cy="16" r="10" fill="#EB001B"/><circle cx="25" cy="16" r="10" fill="#F79E1B"/><path d="M20 8c-3 2.5-4 6-4 8s1 5.5 4 8c3-2.5 4-6 4-8s-1-5.5-4-8z" fill="#FF5F00"/></svg>',
                    amex: '<svg viewBox="0 0 48 24" class="h-8"><rect width="48" height="24" rx="2" fill="#006FCF"/><text x="6" y="17" fill="white" font-size="10" font-weight="bold">AMEX</text></svg>',
                    discover: '<svg viewBox="0 0 48 24" class="h-8"><rect width="48" height="24" rx="2" fill="#FF6000"/><text x="4" y="16" fill="white" font-size="8" font-weight="bold">DISCOVER</text></svg>',
                };
                return logos[type] || '';
            }

            // Luhn algorithm for card validation
            function luhnCheck(cardNumber) {
                const digits = cardNumber.replace(/\s/g, '').split('').reverse().map(Number);
                let sum = 0;
                
                for (let i = 0; i < digits.length; i++) {
                    let digit = digits[i];
                    if (i % 2 === 1) {
                        digit *= 2;
                        if (digit > 9) digit -= 9;
                    }
                    sum += digit;
                }
                
                return sum % 10 === 0;
            }

            // Format card number with spaces
            function formatCardNumber(value) {
                const cleanValue = value.replace(/\s/g, '');
                const cardType = detectCardType(cleanValue);
                
                // Amex format: 4-6-5
                if (cardType === 'amex') {
                    return cleanValue.match(/.{1,4}|.{1,6}|.{1,5}/g)?.join(' ') || cleanValue;
                }
                
                // Standard format: 4-4-4-4
                return cleanValue.match(/.{1,4}/g)?.join(' ') || cleanValue;
            }

            // Format expiry date MM / YY
            function formatExpiry(value) {
                const cleanValue = value.replace(/\s|\//g, '');
                if (cleanValue.length >= 2) {
                    return cleanValue.substring(0, 2) + ' / ' + cleanValue.substring(2, 4);
                }
                return cleanValue;
            }

            // Validate expiry date
            function validateExpiry(expiry) {
                const parts = expiry.split('/').map(s => s.trim());
                if (parts.length !== 2) return false;
                
                const month = parseInt(parts[0]);
                const year = 2000 + parseInt(parts[1]);
                
                if (month < 1 || month > 12) return false;
                
                const now = new Date();
                const expDate = new Date(year, month - 1);
                
                return expDate > now;
            }

            // Card flip animation
            function flipCard(showBack) {
                const cardFlip = document.querySelector('.card-flip');
                if (showBack) {
                    cardFlip.style.transform = 'rotateY(180deg)';
                } else {
                    cardFlip.style.transform = 'rotateY(0deg)';
                }
            }

            // Update card preview
            function updateCardPreview() {
                const cardNumber = document.getElementById('card_number').value;
                const cardName = document.getElementById('card_name').value || 'YOUR NAME';
                const cardExpiry = document.getElementById('card_expiry').value || 'MM/YY';
                const cardCvv = document.getElementById('cvv').value || '•••';
                
                // Update displays
                document.getElementById('card-number-display').textContent = 
                    cardNumber || '•••• •••• •••• ••••';
                document.getElementById('card-name-display').textContent = cardName.toUpperCase();
                document.getElementById('card-expiry-display').textContent = cardExpiry.replace(' ', '');
                document.getElementById('card-cvv-display').textContent = cardCvv.replace(/./g, '•');
            }

            // Initialize payment inputs
            document.addEventListener('DOMContentLoaded', function() {
                const cardNumberInput = document.getElementById('card_number');
                const cardNameInput = document.getElementById('card_name');
                const expiryInput = document.getElementById('card_expiry');
                const cvvInput = document.getElementById('cvv');
                const cardError = document.getElementById('card-error');
                const detectedIcon = document.getElementById('detected-card-icon');

                // Card number input
                if (cardNumberInput) {
                    cardNumberInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\s/g, '');
                        
                        // Only allow digits
                        value = value.replace(/\D/g, '');
                        
                        // Format and update
                        e.target.value = formatCardNumber(value);
                        
                        // Detect card type
                        const cardType = detectCardType(value);
                        detectedCardType = cardType;
                        
                        // Update icon
                        if (cardType) {
                            detectedIcon.innerHTML = getCardLogo(cardType);
                            document.getElementById('card-type-logo').innerHTML = getCardLogo(cardType);
                        } else {
                            detectedIcon.innerHTML = '';
                            document.getElementById('card-type-logo').innerHTML = 
                                '<span class="text-xs font-semibold opacity-60">CARD</span>';
                        }
                        
                        // Validate with Luhn
                        const cleanValue = value.replace(/\s/g, '');
                        if (cleanValue.length >= 13) {
                            if (!luhnCheck(cleanValue)) {
                                cardError.textContent = 'Invalid card number';
                                cardError.classList.remove('hidden');
                                e.target.classList.add('border-red-500');
                                e.target.classList.remove('border-green-500');
                            } else {
                                cardError.classList.add('hidden');
                                e.target.classList.remove('border-red-500');
                                e.target.classList.add('border-green-500');
                            }
                        } else {
                            cardError.classList.add('hidden');
                            e.target.classList.remove('border-red-500', 'border-green-500');
                        }
                        
                        updateCardPreview();
                    });
                }

                // Card name input
                if (cardNameInput) {
                    cardNameInput.addEventListener('input', function(e) {
                        // Only allow letters and spaces
                        e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
                        updateCardPreview();
                    });
                }

                // Expiry input
                if (expiryInput) {
                    expiryInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\s|\//g, '');
                        value = value.replace(/\D/g, '').substring(0, 4);
                        e.target.value = formatExpiry(value);
                        updateCardPreview();
                    });
                }

                // CVV input
                if (cvvInput) {
                    cvvInput.addEventListener('input', function(e) {
                        // Only digits
                        e.target.value = e.target.value.replace(/\D/g, '');
                        
                        // Amex = 4 digits, others = 3
                        const maxLength = detectedCardType === 'amex' ? 4 : 3;
                        if (e.target.value.length > maxLength) {
                            e.target.value = e.target.value.substring(0, maxLength);
                        }
                        
                        updateCardPreview();
                    });
                    
                    cvvInput.addEventListener('focus', () => flipCard(true));
                    cvvInput.addEventListener('blur', () => flipCard(false));
                }
            });

            // Validate and proceed to next step
            function validateAndProceed() {
                const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
                const cardName = document.getElementById('card_name').value.trim();
                const expiry = document.getElementById('card_expiry').value;
                const cvv = document.getElementById('cvv').value;
                
                // Validate card number
                if (!cardNumber || cardNumber.length < 13) {
                    alert('Please enter a valid card number');
                    return;
                }
                
                if (!luhnCheck(cardNumber)) {
                    alert('Invalid card number (failed Luhn check)');
                    return;
                }
                
                // Validate name
                if (!cardName) {
                    alert('Please enter the cardholder name');
                    return;
                }
                
                // Validate expiry
                if (!expiry || !validateExpiry(expiry)) {
                    alert('Please enter a valid expiry date (MM / YY)');
                    return;
                }
                
                // Validate CVV
                const expectedCvvLength = detectedCardType === 'amex' ? 4 : 3;
                if (!cvv || cvv.length < expectedCvvLength) {
                    alert(`Please enter a valid ${expectedCvvLength}-digit CVV`);
                    return;
                }
                
                // All valid, proceed
                goToStep(3);
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
                    const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
                    const cardName = document.getElementById('card_name').value.trim();
                    const expiry = document.getElementById('card_expiry').value;
                    const cvv = document.getElementById('cvv').value;

                    if (!cardNumber || !cardName || !expiry || !cvv) {
                        alert('Please fill in all payment fields.');
                        return false;
                    }

                    if (!luhnCheck(cardNumber)) {
                        alert('Please enter a valid card number.');
                        return false;
                    }

                    if (!validateExpiry(expiry)) {
                        alert('Please enter a valid expiry date.');
                        return false;
                    }

                    const expectedCvvLength = detectedCardType === 'amex' ? 4 : 3;
                    if (cvv.length < expectedCvvLength) {
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

            // Close modal on overlay click
            document.getElementById('terms-modal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeTermsModal();
                }
            });
        </script>
    </body>
</html>
