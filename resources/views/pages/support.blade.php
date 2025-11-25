<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Support - Slice</title>
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body class="bg-gray-50" data-auth-required="true">
        @include("partials.header")

        <main class="container mx-auto max-w-6xl px-4 py-12">
            <!-- Hero Section -->
            <div class="mb-16 text-center">
                <h1 class="mb-4 text-5xl font-semibold text-gray-900">How can we help?</h1>
                <p class="text-xl text-gray-600">Get answers and support for your Slice experience</p>
            </div>

            <!-- Quick Links Grid -->
            <div class="mb-16 grid gap-6 md:grid-cols-3">
                <!-- Orders & Rentals -->
                <div class="rounded-2xl bg-white p-8 shadow-sm transition-shadow hover:shadow-md">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">Orders & Rentals</h3>
                    <p class="mb-4 text-sm text-gray-600">Track orders, manage rentals, and view your rental history</p>
                    <a href="{{ route("dashboard") }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        Go to Dashboard →
                    </a>
                </div>

                <!-- Delivery Tracking -->
                <div class="rounded-2xl bg-white p-8 shadow-sm transition-shadow hover:shadow-md">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">Delivery Tracking</h3>
                    <p class="mb-4 text-sm text-gray-600">Real-time updates on your device delivery status</p>
                    <a href="{{ route("dashboard") }}" class="text-sm font-medium text-green-600 hover:text-green-700">
                        Track Delivery →
                    </a>
                </div>

                <!-- Account & Verification -->
                <div class="rounded-2xl bg-white p-8 shadow-sm transition-shadow hover:shadow-md">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">Account & Verification</h3>
                    <p class="mb-4 text-sm text-gray-600">Manage your profile, KYC status, and credit score</p>
                    <a
                        href="{{ route("dashboard") }}"
                        class="text-sm font-medium text-purple-600 hover:text-purple-700"
                    >
                        View Profile →
                    </a>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mb-16 rounded-3xl bg-white p-8 shadow-sm md:p-12">
                <h2 class="mb-8 text-3xl font-semibold text-gray-900">Frequently Asked Questions</h2>

                <div class="space-y-6">
                    <!-- FAQ Item 1 -->
                    <div class="border-b border-gray-100 pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">How does device rental work?</h3>
                        <p class="text-gray-600">
                            Browse our catalog, select your device and rental duration (1-12 months), complete identity
                            verification, and enjoy flexible monthly payments. Return or purchase at the end of your
                            rental period.
                        </p>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="border-b border-gray-100 pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">What is identity verification (KYC)?</h3>
                        <p class="text-gray-600">
                            Identity verification ensures secure transactions and helps us offer you the best rental
                            terms. Complete verification through your dashboard to unlock rentals and build your credit
                            score.
                        </p>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="border-b border-gray-100 pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">How is my credit score calculated?</h3>
                        <p class="text-gray-600">
                            Your Slice credit score (300-850) is based on account age, order history, profile
                            completeness, and payment patterns. Higher scores unlock better discounts (up to 10%) and
                            faster approvals.
                        </p>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="border-b border-gray-100 pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">What does the Trusted badge mean?</h3>
                        <p class="text-gray-600">
                            The Trusted badge (blue checkmark on your avatar) appears when you reach an excellent credit
                            score (800+), complete identity verification, and maintain good standing. Trusted users get
                            priority support and exclusive benefits.
                        </p>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="border-b border-gray-100 pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">How can I track my delivery?</h3>
                        <p class="text-gray-600">
                            Once your order is confirmed, you'll see a "Track Delivery" link in your dashboard and order
                            details. Get real-time updates with our interactive map showing your device's journey from
                            our warehouse to your door.
                        </p>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="border-b border-gray-100 pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">What payment methods are accepted?</h3>
                        <p class="text-gray-600">
                            We accept all major credit/debit cards, bank transfers, and digital wallets. Your payment
                            undergoes intelligent risk assessment to ensure secure transactions while maintaining a
                            smooth checkout experience.
                        </p>
                    </div>

                    <!-- FAQ Item 7 -->
                    <div class="border-b border-gray-100 pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">Can I extend my rental period?</h3>
                        <p class="text-gray-600">
                            Yes! You can extend your rental anytime through your dashboard. Simply select the additional
                            months you need, and your new payment schedule will be automatically calculated.
                        </p>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="pb-6">
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">What happens if my order is rejected?</h3>
                        <p class="text-gray-600">
                            Orders may be rejected due to incomplete verification or risk assessment. You'll receive
                            detailed guidance on next steps, which may include completing identity verification,
                            improving your credit score, or contacting support for manual review.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="rounded-3xl bg-gradient-to-br from-gray-900 to-gray-800 p-8 text-center text-white md:p-12">
                <h2 class="mb-4 text-3xl font-semibold">Still need help?</h2>
                <p class="mx-auto mb-8 max-w-2xl text-gray-300">
                    Our support team is here to assist you. For immediate help, check your order status in the dashboard
                    or browse the FAQ section above.
                </p>
                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <a
                        href="{{ route("dashboard") }}"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-medium text-gray-900 transition-all hover:bg-gray-100"
                    >
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                        Go to Dashboard
                    </a>
                    <a
                        href="{{ route("devices") }}"
                        class="inline-flex items-center justify-center rounded-xl bg-gray-700 px-6 py-3 font-medium text-white transition-all hover:bg-gray-600"
                    >
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                            />
                        </svg>
                        Browse Devices
                    </a>
                </div>
            </div>
        </main>

        @include("partials.footer")
    </body>
</html>
