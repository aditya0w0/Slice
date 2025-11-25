<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Payment Under Review</title>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body
        class="bg-gray-50"
        x-data="{
            showHelp: false,
            hoursRemaining: 24,
            init() {
                // Simulate time countdown (for demo)
                setInterval(() => {
                    if (this.hoursRemaining > 0) {
                        this.hoursRemaining -= 0.1
                    }
                }, 6000) // Every 6 seconds = 0.1 hour for demo
            },
        }"
    >
        <main class="container mx-auto max-w-2xl px-4 py-16">
            <!-- Clock Icon Animation -->
            <div class="mb-8 flex justify-center">
                <div class="pending-icon-wrapper">
                    <div class="pending-circle">
                        <svg class="h-16 w-16 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <!-- Pending Message -->
            <div class="fade-in-up mb-8 text-center" style="animation-delay: 0.1s">
                <h1 class="mb-3 text-4xl font-bold text-yellow-600">Payment Under Review</h1>
                <p class="text-lg text-gray-600">
                    Your payment for order {{ $orderNumber }} is being reviewed by our team.
                </p>
            </div>

            <!-- Info Card -->
            <div
                class="fade-in-up mb-6 rounded-2xl border-2 border-yellow-200 bg-yellow-50 p-6"
                style="animation-delay: 0.3s"
            >
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="mb-2 text-lg font-semibold text-yellow-900">Manual Verification Required</h3>
                        <p class="mb-3 text-sm leading-relaxed text-yellow-800">
                            Our admin team is currently reviewing your payment to ensure security and prevent fraud.
                            This is a standard procedure for certain transactions.
                        </p>
                        <div class="mt-3 rounded-xl bg-white/50 p-4">
                            <p class="mb-2 text-sm font-medium text-yellow-900">What happens next:</p>
                            <ul class="space-y-2 text-sm text-yellow-800">
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-yellow-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>Our team will verify your payment details</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-yellow-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>You'll receive an email notification once approved</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-yellow-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>We may contact you if additional information is needed</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expected Timeline -->
            <div class="fade-in-up mb-6 rounded-2xl bg-white p-8 shadow-sm" style="animation-delay: 0.5s">
                <h2 class="mb-6 text-xl font-semibold text-gray-900">Expected Timeline</h2>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="mb-1 font-semibold text-gray-900">Review Time</h3>
                            <p class="text-sm text-gray-600">
                                Most reviews are completed within
                                <span class="font-semibold text-yellow-600">24-48 hours</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="mb-1 font-semibold text-gray-900">Notification</h3>
                            <p class="text-sm text-gray-600">
                                Check your email
                                <span class="font-semibold text-blue-600">
                                    {{ $order->user->email ?? "your@email.com" }}
                                </span>
                                for updates
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="mb-1 font-semibold text-gray-900">Processing</h3>
                            <p class="text-sm text-gray-600">Once approved, your order will be processed immediately</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="fade-in-up mb-6 rounded-2xl bg-white p-8 shadow-sm" style="animation-delay: 0.7s">
                <h2 class="mb-6 text-xl font-semibold text-gray-900">Order Details</h2>

                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Order Number</span>
                        <span class="font-semibold text-gray-900">{{ $orderNumber }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Device</span>
                        <span class="font-semibold text-gray-900">{{ $device }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Rental Duration</span>
                        <span class="font-semibold text-gray-900">{{ $duration }} months</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Payment</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($total, 0, ",", ".") }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Status</span>
                        <span
                            class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800"
                        >
                            <svg class="mr-1 h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            Under Review
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Submitted At</span>
                        <span class="font-semibold text-gray-900">{{ $submittedAt }}</span>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div
                class="fade-in-up mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-6"
                style="animation-delay: 0.9s"
            >
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="mb-2 font-semibold text-blue-900">Need Help?</h3>
                        <p class="mb-3 text-sm text-blue-800">
                            If you have questions about your order or the review process, our support team is here to
                            help.
                        </p>
                        <a
                            href="{{ route("support") }}"
                            class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700"
                        >
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="fade-in-up flex flex-col gap-4 sm:flex-row" style="animation-delay: 1.1s">
                <a
                    href="{{ route("home") }}"
                    class="flex-1 rounded-xl border-2 border-gray-300 px-6 py-3 text-center font-semibold text-gray-700 transition-colors hover:bg-gray-50"
                >
                    Back to Home
                </a>
                <a
                    href="{{ route("dashboard") }}"
                    class="flex-1 rounded-xl bg-blue-600 px-6 py-3 text-center font-semibold text-white shadow-lg transition-colors hover:bg-blue-700"
                >
                    View My Orders
                </a>
            </div>
        </main>

        <style>
            /* Fade In Up Animation */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in-up {
                animation: fadeInUp 0.6s ease-out forwards;
                opacity: 0;
            }

            /* Pending Icon Styles */
            .pending-icon-wrapper {
                width: 120px;
                height: 120px;
                animation: pendingIconScale 0.5s ease-out;
            }

            @keyframes pendingIconScale {
                0% {
                    transform: scale(0);
                    opacity: 0;
                }
                50% {
                    transform: scale(1.1);
                }
                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .pending-circle {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                background: #fef3c7;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 5px solid #f59e0b;
                box-shadow: 0 0 0 10px #fef3c7;
                animation: pendingPulse 2s ease-in-out infinite;
            }

            @keyframes pendingPulse {
                0%,
                100% {
                    transform: scale(1);
                    box-shadow: 0 0 0 10px rgba(254, 243, 199, 0.5);
                }
                50% {
                    transform: scale(1.05);
                    box-shadow: 0 0 0 20px rgba(254, 243, 199, 0);
                }
            }
        </style>
    </body>
</html>
