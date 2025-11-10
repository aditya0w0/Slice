<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Dashboard — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50 text-gray-900">
        @include("partials.header")

        <main class="mx-auto max-w-7xl px-6 py-12">
            <!-- Welcome section -->
            <div class="mb-12">
                <h1 class="text-5xl font-semibold tracking-tight text-gray-900">Welcome back.</h1>
                <p class="mt-3 text-xl text-gray-500">{{ $user->name }}</p>
            </div>

            <!-- Stats Grid -->
            <div class="mb-12 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-blue-600 p-8 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Active Rentals</p>
                            <p class="mt-2 text-4xl font-semibold text-white">{{ $orders->where('status', 'active')->count() }}</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-purple-500 to-purple-600 p-8 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-100">Total Orders</p>
                            <p class="mt-2 text-4xl font-semibold text-white">{{ $orders->count() }}</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-pink-500 to-pink-600 p-8 shadow-lg transition-transform hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-pink-100">Cart Items</p>
                            <p class="mt-2 text-4xl font-semibold text-white">{{ $cartCount ?? 0 }}</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Current Rental (if any) -->
                @php
                    $activeOrder = $orders->first();
                @endphp

                @if($activeOrder)
                <div class="lg:col-span-2">
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                        <div class="p-8">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wider text-blue-600">Currently Renting</div>
                                    <h2 class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeOrder->variant_slug }}</h2>
                                    <p class="mt-2 text-sm text-gray-500">
                                        Due {{ $activeOrder->created_at->addDays(7)->format('M j, Y') }}
                                    </p>
                                </div>
                                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200">
                                    <svg class="h-10 w-10 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex gap-3">
                                <a href="/orders/{{ $activeOrder->id }}" class="inline-flex items-center rounded-full bg-blue-600 px-6 py-3 text-sm font-medium text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700">
                                    View Details
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                <button class="inline-flex items-center rounded-full border border-gray-200 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                                    Extend Rental
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="space-y-6">
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                            <div class="mt-4 space-y-3">
                                <a href="{{ route('devices') }}" class="flex items-center gap-3 rounded-xl p-3 transition hover:bg-gray-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Browse Devices</p>
                                        <p class="text-xs text-gray-500">Find your next rental</p>
                                    </div>
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                <a href="{{ route('cart.index') }}" class="flex items-center gap-3 rounded-xl p-3 transition hover:bg-gray-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">View Cart</p>
                                        <p class="text-xs text-gray-500">{{ $cartCount ?? 0 }} items</p>
                                    </div>
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                <a href="#" class="flex items-center gap-3 rounded-xl p-3 transition hover:bg-gray-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-pink-100">
                                        <svg class="h-5 w-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Settings</p>
                                        <p class="text-xs text-gray-500">Manage your account</p>
                                    </div>
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Order History -->
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                        <div class="p-6">
                            <h3 class="text-sm font-semibold text-gray-900">Recent Orders</h3>
                            <div class="mt-4">
                                @if($orders->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($orders->take(3) as $order)
                                        <div class="flex items-center gap-3 rounded-xl border border-gray-100 p-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                                <svg class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $order->variant_slug }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('M j, Y') }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No orders yet</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Explore Devices -->
            <div class="mt-12">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-900">Explore Devices</h2>
                    <a href="{{ route('devices') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        View All →
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 p-8 shadow-lg transition hover:scale-105">
                        <h3 class="text-3xl font-semibold text-white">iPhone</h3>
                        <p class="mt-2 text-sm text-slate-300">From $50/mo</p>
                        <div class="mt-6 text-slate-400">→</div>
                    </div>

                    <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-cyan-500 p-8 shadow-lg transition hover:scale-105">
                        <h3 class="text-3xl font-semibold text-white">iPad</h3>
                        <p class="mt-2 text-sm text-blue-100">From $40/mo</p>
                        <div class="mt-6 text-blue-200">→</div>
                    </div>

                    <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 p-8 shadow-lg transition hover:scale-105">
                        <h3 class="text-3xl font-semibold text-white">MacBook</h3>
                        <p class="mt-2 text-sm text-purple-100">From $100/mo</p>
                        <div class="mt-6 text-purple-200">→</div>
                    </div>

                    <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-red-500 to-orange-500 p-8 shadow-lg transition hover:scale-105">
                        <h3 class="text-3xl font-semibold text-white">Watch</h3>
                        <p class="mt-2 text-sm text-red-100">From $30/mo</p>
                        <div class="mt-6 text-red-200">→</div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
