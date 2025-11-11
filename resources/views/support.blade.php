<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - Slice</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" data-auth-required="true">
    @include('partials.header')

    <main class="container mx-auto px-4 py-12 max-w-6xl">
        
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-semibold text-gray-900 mb-4">How can we help?</h1>
            <p class="text-xl text-gray-600">Get answers and support for your Slice experience</p>
        </div>

        <!-- Quick Links Grid -->
        <div class="grid md:grid-cols-3 gap-6 mb-16">
            <!-- Orders & Rentals -->
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Orders & Rentals</h3>
                <p class="text-sm text-gray-600 mb-4">Track orders, manage rentals, and view your rental history</p>
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    Go to Dashboard →
                </a>
            </div>

            <!-- Delivery Tracking -->
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-green-100 mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Delivery Tracking</h3>
                <p class="text-sm text-gray-600 mb-4">Real-time updates on your device delivery status</p>
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-green-600 hover:text-green-700">
                    Track Delivery →
                </a>
            </div>

            <!-- Account & Verification -->
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-purple-100 mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Account & Verification</h3>
                <p class="text-sm text-gray-600 mb-4">Manage your profile, KYC status, and credit score</p>
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700">
                    View Profile →
                </a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-3xl shadow-sm p-8 md:p-12 mb-16">
            <h2 class="text-3xl font-semibold text-gray-900 mb-8">Frequently Asked Questions</h2>
            
            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How does device rental work?</h3>
                    <p class="text-gray-600">
                        Browse our catalog, select your device and rental duration (1-12 months), complete identity verification, 
                        and enjoy flexible monthly payments. Return or purchase at the end of your rental period.
                    </p>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What is identity verification (KYC)?</h3>
                    <p class="text-gray-600">
                        Identity verification ensures secure transactions and helps us offer you the best rental terms. 
                        Complete verification through your dashboard to unlock rentals and build your credit score.
                    </p>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How is my credit score calculated?</h3>
                    <p class="text-gray-600">
                        Your Slice credit score (300-850) is based on account age, order history, profile completeness, 
                        and payment patterns. Higher scores unlock better discounts (up to 10%) and faster approvals.
                    </p>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What does the Trusted badge mean?</h3>
                    <p class="text-gray-600">
                        The Trusted badge (blue checkmark on your avatar) appears when you reach an excellent credit score (800+), 
                        complete identity verification, and maintain good standing. Trusted users get priority support and exclusive benefits.
                    </p>
                </div>

                <!-- FAQ Item 5 -->
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How can I track my delivery?</h3>
                    <p class="text-gray-600">
                        Once your order is confirmed, you'll see a "Track Delivery" link in your dashboard and order details. 
                        Get real-time updates with our interactive map showing your device's journey from our warehouse to your door.
                    </p>
                </div>

                <!-- FAQ Item 6 -->
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What payment methods are accepted?</h3>
                    <p class="text-gray-600">
                        We accept all major credit/debit cards, bank transfers, and digital wallets. Your payment undergoes 
                        intelligent risk assessment to ensure secure transactions while maintaining a smooth checkout experience.
                    </p>
                </div>

                <!-- FAQ Item 7 -->
                <div class="border-b border-gray-100 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I extend my rental period?</h3>
                    <p class="text-gray-600">
                        Yes! You can extend your rental anytime through your dashboard. Simply select the additional months you need, 
                        and your new payment schedule will be automatically calculated.
                    </p>
                </div>

                <!-- FAQ Item 8 -->
                <div class="pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens if my order is rejected?</h3>
                    <p class="text-gray-600">
                        Orders may be rejected due to incomplete verification or risk assessment. You'll receive detailed guidance on next steps, 
                        which may include completing identity verification, improving your credit score, or contacting support for manual review.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-8 md:p-12 text-center text-white">
            <h2 class="text-3xl font-semibold mb-4">Still need help?</h2>
            <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
                Our support team is here to assist you. For immediate help, check your order status in the dashboard 
                or browse the FAQ section above.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-900 font-medium rounded-xl hover:bg-gray-100 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Go to Dashboard
                </a>
                <a href="{{ route('devices') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-700 text-white font-medium rounded-xl hover:bg-gray-600 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Browse Devices
                </a>
            </div>
        </div>

    </main>

    @include('partials.footer')
</body>
</html>
