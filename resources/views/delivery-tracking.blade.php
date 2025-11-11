<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Delivery - Order {{ $order->invoice_number }}</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    @include('partials.header')

    <main class="container mx-auto px-4 py-8 max-w-7xl" x-data="deliveryTracking()">
        <!-- Header Section -->
        <div class="mb-8">
            <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Order Details
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Track Your Delivery</h1>
            <p class="text-gray-600">Order {{ $order->invoice_number }}</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column: Map -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Dummy Map -->
                    <div class="relative h-[500px] bg-gray-100">
                        <!-- Map Background -->
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 800 500" preserveAspectRatio="xMidYMid slice">
                            <!-- Grid lines to simulate map -->
                            <defs>
                                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#e5e7eb" stroke-width="1"/>
                                </pattern>
                            </defs>
                            <rect width="800" height="500" fill="#f9fafb"/>
                            <rect width="800" height="500" fill="url(#grid)"/>
                            
                            <!-- Fake streets -->
                            <line x1="0" y1="150" x2="800" y2="150" stroke="#d1d5db" stroke-width="3"/>
                            <line x1="0" y1="300" x2="800" y2="300" stroke="#d1d5db" stroke-width="3"/>
                            <line x1="200" y1="0" x2="200" y2="500" stroke="#d1d5db" stroke-width="3"/>
                            <line x1="500" y1="0" x2="500" y2="500" stroke="#d1d5db" stroke-width="3"/>
                            <line x1="650" y1="0" x2="650" y2="500" stroke="#d1d5db" stroke-width="3"/>
                            
                            <!-- Fake buildings -->
                            <rect x="50" y="50" width="100" height="80" fill="#e5e7eb" opacity="0.5"/>
                            <rect x="250" y="180" width="120" height="100" fill="#e5e7eb" opacity="0.5"/>
                            <rect x="550" y="80" width="90" height="150" fill="#e5e7eb" opacity="0.5"/>
                            <rect x="100" y="350" width="150" height="120" fill="#e5e7eb" opacity="0.5"/>
                            <rect x="550" y="320" width="180" height="140" fill="#e5e7eb" opacity="0.5"/>
                        </svg>

                        <!-- Route Line (animated) -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 800 500">
                            <defs>
                                <linearGradient id="routeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#2563eb;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <!-- Animated delivery route -->
                            <path 
                                d="M 150 100 Q 300 200, 450 250 T 650 400" 
                                fill="none" 
                                stroke="url(#routeGradient)" 
                                stroke-width="6" 
                                stroke-linecap="round"
                                stroke-dasharray="1000"
                                :stroke-dashoffset="1000 - (progress * 10)"
                                style="transition: stroke-dashoffset 2s ease-out;"
                            />
                        </svg>

                        <!-- Markers -->
                        <!-- Origin (Warehouse) -->
                        <div class="absolute" style="top: 80px; left: 130px;">
                            <div class="relative">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="absolute top-14 left-1/2 -translate-x-1/2 whitespace-nowrap bg-white px-2 py-1 rounded shadow-sm text-xs font-medium">
                                    Warehouse
                                </div>
                            </div>
                        </div>

                        <!-- Current Position (Animated Driver) -->
                        <div 
                            class="absolute transition-all duration-1000 ease-in-out"
                            :style="`top: ${driverPosition.y}px; left: ${driverPosition.x}px;`"
                        >
                            <div class="relative animate-bounce">
                                <div class="w-14 h-14 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-2xl border-4 border-white pulse-ring">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                    </svg>
                                </div>
                                @if($order->status === 'shipped')
                                <div class="absolute -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap bg-red-600 text-white px-3 py-1 rounded-full shadow-lg text-xs font-semibold">
                                    On the way! 🚚
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Destination (Customer) -->
                        <div class="absolute" style="top: 380px; left: 630px;">
                            <div class="relative">
                                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <div class="absolute top-14 left-1/2 -translate-x-1/2 whitespace-nowrap bg-white px-2 py-1 rounded shadow-sm text-xs font-medium">
                                    Your Location
                                </div>
                            </div>
                        </div>

                        <!-- ETA Badge -->
                        <div class="absolute top-4 right-4 bg-white rounded-xl shadow-lg px-4 py-3">
                            <div class="text-xs text-gray-600 mb-1">Estimated Time</div>
                            <div class="text-2xl font-bold text-gray-900" x-text="estimatedTime"></div>
                        </div>

                        <!-- Progress Indicator -->
                        <div class="absolute bottom-4 left-4 right-4 bg-white rounded-xl shadow-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Delivery Progress</span>
                                <span class="text-sm font-bold text-blue-600" x-text="Math.round(progress) + '%'"></span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div 
                                    class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000"
                                    :style="`width: ${progress}%`"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="space-y-6">
                <!-- Delivery Status Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Delivery Status</h2>
                    
                    <div class="space-y-4">
                        @foreach($deliveryStages as $index => $stage)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-1">
                                @if($stage['completed'])
                                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                @elseif($index === $currentStage)
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center animate-pulse">
                                        <div class="w-3 h-3 bg-white rounded-full"></div>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                                @endif
                            </div>
                            
                            <div class="flex-1 {{ $stage['completed'] ? '' : 'opacity-50' }}">
                                <h3 class="font-semibold text-gray-900">{{ $stage['name'] }}</h3>
                                @if($stage['timestamp'])
                                    <p class="text-sm text-gray-500">{{ $stage['timestamp'] }}</p>
                                @elseif($index === $currentStage)
                                    <p class="text-sm text-blue-600 font-medium">In progress...</p>
                                @else
                                    <p class="text-sm text-gray-400">Pending</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Details Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Order Details</h2>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Number</span>
                            <span class="font-semibold">{{ $order->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Items</span>
                            <span class="font-semibold">{{ $order->quantity }}</span>
                        </div>
                        <div class="pt-3 border-t">
                            <div class="text-gray-600 mb-1">Delivery Address</div>
                            <div class="font-medium text-gray-900">{{ $order->user->address ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->city ?? '' }}, {{ $order->user->zip_code ?? '' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="font-bold mb-2">Need Help?</h3>
                    <p class="text-sm text-blue-100 mb-4">Contact our support team if you have any questions about your delivery.</p>
                    <a href="{{ route('support') }}" class="inline-flex items-center gap-2 bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-blue-50 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes pulse-ring {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            50% {
                box-shadow: 0 0 0 15px rgba(239, 68, 68, 0);
            }
        }

        .pulse-ring {
            animation: pulse-ring 2s infinite;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        function deliveryTracking() {
            return {
                progress: {{ $routePoints['current']['progress'] }},
                estimatedTime: '{{ $order->status === "delivered" ? "Delivered!" : ($order->status === "shipped" ? "16 min" : "Pending") }}',
                driverPosition: {
                    x: 150 + ({{ $routePoints['current']['progress'] }} / 100) * 500,
                    y: 100 + ({{ $routePoints['current']['progress'] }} / 100) * 300,
                },
                
                init() {
                    // Simulate real-time updates
                    @if($order->status === 'shipped')
                    setInterval(() => {
                        if (this.progress < 95) {
                            this.progress += 0.5;
                            this.driverPosition.x = 150 + (this.progress / 100) * 500;
                            this.driverPosition.y = 100 + (this.progress / 100) * 300;
                            
                            // Update ETA
                            let minutesLeft = Math.max(1, Math.round(16 - (this.progress / 100) * 16));
                            this.estimatedTime = minutesLeft + ' min';
                        }
                    }, 3000);
                    @endif
                }
            }
        }
    </script>
</body>
</html>
