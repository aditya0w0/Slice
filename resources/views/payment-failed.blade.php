<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Rejected - Suspicious Activity</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" data-auth-required="true" x-data="{ expandDetails: false }">
    <main class="container mx-auto px-4 py-16 max-w-2xl">
        
        <!-- Error Icon Animation -->
        <div class="flex justify-center mb-8">
            <div class="error-icon-wrapper">
                <div class="error-checkmark">
                    <div class="error-circle">
                        <div class="error-cross">
                            <span class="cross-line cross-line-left"></span>
                            <span class="cross-line cross-line-right"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div class="text-center mb-8 fade-in-up" style="animation-delay: 0.1s;">
            <h1 class="text-4xl font-bold text-red-600 mb-3">Payment Rejected</h1>
            <p class="text-lg text-gray-600">Your payment for order {{ $orderNumber }} has been rejected.</p>
        </div>

        <!-- Reason Card -->
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6 mb-6 fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    @if(session('kyc_required'))
                        <h3 class="text-lg font-semibold text-red-900 mb-3">Identity Verification Required</h3>
                        <p class="text-sm text-red-800 leading-relaxed mb-4">
                            Complete identity verification to continue with your order.
                        </p>
                        <div class="bg-white rounded-lg p-5 mb-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Verification Process</h4>
                            <div class="space-y-3 text-sm text-gray-700">
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">1</div>
                                    <div>
                                        <p class="font-medium text-gray-900">Visit your dashboard</p>
                                        <p class="text-gray-600 text-xs mt-0.5">Access the verification section</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">2</div>
                                    <div>
                                        <p class="font-medium text-gray-900">Upload identification</p>
                                        <p class="text-gray-600 text-xs mt-0.5">Government-issued ID required</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">3</div>
                                    <div>
                                        <p class="font-medium text-gray-900">Verification review</p>
                                        <p class="text-gray-600 text-xs mt-0.5">Typically completed within 24 hours</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(session('credit_failure'))
                        <h3 class="text-lg font-semibold text-red-900 mb-3">Account Verification Required</h3>
                        <p class="text-sm text-red-800 leading-relaxed mb-4">
                            Your account requires additional verification before proceeding with this order.
                        </p>
                        <div class="bg-white rounded-lg p-5">
                            <h4 class="font-semibold text-gray-900 mb-3">Next Steps</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p>Complete your account profile with accurate information</p>
                                <p>Ensure all required fields are properly filled</p>
                                <p>Contact support if you need assistance</p>
                            </div>
                        </div>
                    @else
                        <h3 class="text-lg font-semibold text-red-900 mb-3">Payment Review Required</h3>
                        <p class="text-sm text-red-800 leading-relaxed">
                            This transaction requires additional review for security purposes.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 fade-in-up" style="animation-delay: 0.5s;">
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
                    <span class="text-gray-600">Attempted Payment</span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Status</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Rejected
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Rejection Time</span>
                    <span class="font-semibold text-gray-900">{{ $rejectedAt }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 fade-in-up" style="animation-delay: 0.7s;">
            <a href="{{ route('devices') }}" class="flex-1 text-center px-6 py-3.5 bg-gray-900 rounded-xl font-medium text-white hover:bg-gray-800 transition-all">
                Continue Shopping
            </a>
            <a href="{{ route('support') }}" class="flex-1 text-center px-6 py-3.5 border border-gray-300 rounded-xl font-medium text-gray-900 hover:bg-gray-50 transition-all">
                Contact Support
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

        /* Error Icon Styles */
        .error-icon-wrapper {
            width: 120px;
            height: 120px;
            animation: errorIconScale 0.5s ease-out;
        }

        @keyframes errorIconScale {
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

        .error-checkmark {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: block;
            stroke-width: 3;
            stroke: #fff;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #ef4444;
            animation: errorFill 0.4s ease-in-out 0.4s forwards, errorScale 0.3s ease-in-out 0.9s both;
        }

        .error-circle {
            width: 120px;
            height: 120px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 5px solid #ef4444;
        }

        .error-circle::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #fee2e2;
            z-index: -1;
        }

        .error-cross {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
        }

        .cross-line {
            display: block;
            position: absolute;
            height: 5px;
            width: 60px;
            background-color: #ef4444;
            border-radius: 2px;
            top: 50%;
            left: 50%;
        }

        .cross-line-left {
            transform: translate(-50%, -50%) rotate(45deg);
            animation: crossLineLeft 0.75s ease;
        }

        .cross-line-right {
            transform: translate(-50%, -50%) rotate(-45deg);
            animation: crossLineRight 0.75s ease 0.1s;
        }

        @keyframes errorFill {
            100% {
                box-shadow: inset 0px 0px 0px 60px #ef4444;
            }
        }

        @keyframes errorScale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes crossLineLeft {
            0% {
                width: 0;
                transform: translate(-50%, -50%) rotate(45deg);
            }
            100% {
                width: 60px;
                transform: translate(-50%, -50%) rotate(45deg);
            }
        }

        @keyframes crossLineRight {
            0% {
                width: 0;
                transform: translate(-50%, -50%) rotate(-45deg);
            }
            100% {
                width: 60px;
                transform: translate(-50%, -50%) rotate(-45deg);
            }
        }
    </style>
</body>
</html>
