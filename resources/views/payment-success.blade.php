<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful — Slice</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50" data-auth-required="true" x-data="paymentSuccess({{ $order->status == 'paid' ? 'true' : 'false' }})" x-init="init()">
    @include('partials.header')

    <main class="mx-auto max-w-3xl px-6 py-12 min-h-screen">
        <!-- Success Icon with Animation -->
        <div class="text-center mb-8">
            <div class="success-checkmark mx-auto mb-6">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                    <div class="icon-fix"></div>
                </div>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Payment Successful!</h1>
            <p class="text-lg text-gray-600">Your rental order {{ $orderNumber }} has been received.</p>
        </div>

        <!-- Progress Tracker Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Delivery Status Tracker</h2>
            
            <div class="space-y-6">
                <!-- Step 1: Pembayaran Diterima -->
                <div class="progress-step completed" id="step-1">
                    <div class="flex items-start gap-4">
                        <div class="step-icon-wrapper">
                            <div class="step-icon completed">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="step-line"></div>
                        </div>
                        <div class="flex-1 pb-8">
                            <h3 class="font-semibold text-gray-900 mb-1">Payment Received</h3>
                            <p class="text-sm text-gray-600 mb-2">Your payment has been successfully verified</p>
                            <span class="text-xs text-gray-500">{{ $order->payment_verified_at ?? now()->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Order Processing -->
                <div class="progress-step" :class="{ 'completed': steps[2].status === 'completed' }" id="step-2">
                    <div class="flex items-start gap-4">
                        <div class="step-icon-wrapper">
                            <div class="step-icon" :class="steps[2].status">
                                <template x-if="steps[2].status === 'completed'">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </template>
                                <template x-if="steps[2].status === 'processing'">
                                    <div class="spinner"></div>
                                </template>
                            </div>
                            <div class="step-line" :style="steps[2].status === 'completed' ? 'background: linear-gradient(to bottom, #10b981 0%, #e5e7eb 100%)' : ''"></div>
                        </div>
                        <div class="flex-1 pb-8">
                            <h3 class="font-semibold text-gray-900 mb-1">Order Processing</h3>
                            <p class="text-sm text-gray-600">Our team is preparing your device</p>
                            <span x-show="steps[2].timestamp" x-text="steps[2].timestamp" class="text-xs text-gray-500 block mt-2"></span>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Item Picked Up -->
                <div class="progress-step" :class="{ 'completed': steps[3].status === 'completed' }" id="step-3">
                    <div class="flex items-start gap-4">
                        <div class="step-icon-wrapper">
                            <div class="step-icon" :class="steps[3].status">
                                <template x-if="steps[3].status === 'completed'">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </template>
                                <template x-if="steps[3].status === 'processing'">
                                    <div class="spinner"></div>
                                </template>
                                <template x-if="steps[3].status === 'pending'">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                </template>
                            </div>
                            <div class="step-line" :style="steps[3].status === 'completed' ? 'background: linear-gradient(to bottom, #10b981 0%, #e5e7eb 100%)' : ''"></div>
                        </div>
                        <div class="flex-1 pb-8">
                            <h3 class="font-semibold mb-1" :class="steps[3].status === 'pending' ? 'text-gray-400' : 'text-gray-900'">Item Picked Up</h3>
                            <p class="text-sm" :class="steps[3].status === 'pending' ? 'text-gray-500' : 'text-gray-600'">Device ready for courier pickup</p>
                            <span x-show="steps[3].timestamp" x-text="steps[3].timestamp" class="text-xs text-gray-500 block mt-2"></span>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Out for Delivery -->
                <div class="progress-step" :class="{ 'completed': steps[4].status === 'completed' }" id="step-4">
                    <div class="flex items-start gap-4">
                        <div class="step-icon-wrapper">
                            <div class="step-icon" :class="steps[4].status">
                                <template x-if="steps[4].status === 'processing'">
                                    <div class="spinner"></div>
                                </template>
                                <template x-if="steps[4].status === 'pending'">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                    </svg>
                                </template>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold mb-1" :class="steps[4].status === 'processing' ? 'text-blue-600' : 'text-gray-400'">Out for Delivery</h3>
                            <p class="text-sm" :class="steps[4].status === 'processing' ? 'text-blue-600' : 'text-gray-500'">Your rental device is on the way to your address!</p>
                            <span x-show="steps[4].timestamp" x-text="steps[4].timestamp" class="text-xs text-gray-500 block mt-2"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Details</h2>
            
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
                    <span class="text-gray-600">Delivery Address</span>
                    <span class="font-semibold text-gray-900 text-right max-w-xs">{{ $address }}</span>
                </div>
                
                @if($discountPercentage > 0)
                <div class="pt-4 border-t">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-600">Loyalty Discount</span>
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded">
                                {{ $creditTier === 'excellent' ? 'Top Member' : ($creditTier === 'very_good' ? 'Premium Member' : 'Valued Member') }}
                            </span>
                        </div>
                        <span class="font-semibold text-green-600">-{{ $discountPercentage }}%</span>
                    </div>
                    <p class="text-xs text-gray-500">Thank you for being a trusted customer! 🎉</p>
                </div>
                @endif
                
                <div class="pt-4 border-t flex justify-between">
                    <span class="text-gray-600">Total Payment</span>
                    <span class="text-xl font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('home') }}" class="flex-1 text-center px-6 py-3 border-2 border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                Back to Home
            </a>
            @if(in_array($order->status, ['processing', 'picked_up', 'shipped']))
            <a href="{{ route('delivery.track', $order->id) }}" class="flex-1 text-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 rounded-xl font-semibold text-white hover:from-green-700 hover:to-green-800 transition-colors shadow-lg">
                🚚 Track Delivery
            </a>
            @endif
            <a href="{{ route('orders.show', $order->id) }}" class="flex-1 text-center px-6 py-3 bg-blue-600 rounded-xl font-semibold text-white hover:bg-blue-700 transition-colors shadow-lg">
                View Order Details
            </a>
        </div>
    </main>

    <style>
        /* Success Checkmark Animation */
        .success-checkmark {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .check-icon {
            width: 120px;
            height: 120px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #10b981;
            background-color: #d1fae5;
        }

        .icon-line {
            height: 5px;
            background-color: #10b981;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .icon-line.line-tip {
            top: 56px;
            left: 25px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }

        .icon-line.line-long {
            top: 48px;
            right: 15px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }

        .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(16, 185, 129, 0.5);
            animation: icon-circle 0.4s ease-in-out 0.75s forwards;
        }
        
        @keyframes icon-circle {
            from {
                transform: scale(1);
                opacity: 1;
            }
            to {
                transform: scale(1.1);
                opacity: 0;
            }
        }

        .icon-fix {
            top: 12px;
            width: 7px;
            left: 34px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background-color: #d1fae5;
        }

        @keyframes icon-line-tip {
            0% {
                width: 0;
                left: 1px;
                top: 19px;
            }
            54% {
                width: 0;
                left: 1px;
                top: 19px;
            }
            70% {
                width: 50px;
                left: -8px;
                top: 37px;
            }
            84% {
                width: 17px;
                left: 21px;
                top: 48px;
            }
            100% {
                width: 25px;
                left: 25px;
                top: 56px;
            }
        }

        @keyframes icon-line-long {
            0% {
                width: 0;
                right: 46px;
                top: 54px;
            }
            65% {
                width: 0;
                right: 46px;
                top: 54px;
            }
            84% {
                width: 55px;
                right: 0px;
                top: 35px;
            }
            100% {
                width: 47px;
                right: 15px;
                top: 48px;
            }
        }

        /* Progress Steps Animation */
        .step-icon-wrapper {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .step-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .step-icon.completed {
            background-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .step-icon.processing {
            background-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .step-icon.pending {
            background-color: #e5e7eb;
            border: 2px solid #d1d5db;
        }

        .step-line {
            width: 2px;
            flex: 1;
            background-color: #e5e7eb;
            position: absolute;
            top: 48px;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .progress-step:last-child .step-line {
            display: none;
        }

        .progress-step.completed .step-line {
            background: linear-gradient(to bottom, #10b981 0%, #e5e7eb 100%);
        }

        /* Spinner Animation */
        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Progressive Animation */
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

        .progress-step {
            animation: fadeInUp 0.6s ease-out;
        }

        #step-1 { animation-delay: 0.1s; }
        #step-2 { animation-delay: 0.3s; }
        #step-3 { animation-delay: 0.5s; }
        #step-4 { animation-delay: 0.7s; }
    </style>

    <script>
        function paymentSuccess(autoProgress = false) {
            return {
                steps: {
                    1: { 
                        status: 'completed', 
                        timestamp: '{{ now()->format('d M Y, H:i') }}' 
                    },
                    2: { 
                        status: '{{ in_array($order->status, ['processing', 'picked_up', 'shipped']) ? 'completed' : 'processing' }}',
                        timestamp: '{{ $order->processed_at ? $order->processed_at->format('d M Y, H:i') : '' }}'
                    },
                    3: { 
                        status: '{{ in_array($order->status, ['picked_up', 'shipped']) ? 'completed' : (in_array($order->status, ['processing']) ? 'processing' : 'pending') }}',
                        timestamp: '{{ $order->picked_up_at ? $order->picked_up_at->format('d M Y, H:i') : '' }}'
                    },
                    4: { 
                        status: '{{ $order->status == 'shipped' ? 'processing' : 'pending' }}',
                        timestamp: '{{ $order->shipped_at ? $order->shipped_at->format('d M Y, H:i') : '' }}'
                    }
                },
                
                init() {
                    // Auto-progress animation for fresh orders (status = 'paid')
                    if (autoProgress) {
                        // Step 2: Complete after 3 seconds
                        setTimeout(() => {
                            this.steps[2].status = 'completed';
                            this.steps[2].timestamp = new Date().toLocaleString('en-GB', { 
                                day: 'numeric', 
                                month: 'short', 
                                year: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });
                            this.steps[3].status = 'processing';
                        }, 3000);
                        
                        // Step 3: Complete after 6 seconds
                        setTimeout(() => {
                            this.steps[3].status = 'completed';
                            this.steps[3].timestamp = new Date().toLocaleString('en-GB', { 
                                day: 'numeric', 
                                month: 'short', 
                                year: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });
                            this.steps[4].status = 'processing';
                        }, 6000);
                    }
                }
            }
        }
    </script>
</body>
</html>
