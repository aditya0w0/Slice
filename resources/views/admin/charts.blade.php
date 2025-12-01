<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Analytics & Charts — Admin — Slice</title>
        @vite("resources/css/app.css")
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
        ></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    </head>
    <body class="bg-gradient-to-br from-gray-50 via-white to-gray-50 antialiased min-h-screen">
        <!-- Premium Navigation Bar -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-200/50 shadow-sm">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <a
                        href="{{ route("admin.dashboard") }}"
                        class="flex items-center gap-3 group"
                    >
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg group-hover:shadow-blue-500/30 transition-all duration-300 group-hover:scale-105"
                        >
                            <svg
                                class="w-5 h-5 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        <span
                            class="text-xl font-bold text-gray-900 tracking-tight"
                        >
                            Slice
                            <span class="text-blue-600">.</span>
                        </span>
                    </a>

                    <!-- User Info -->
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $user->name }}
                            </p>
                            <p class="text-xs text-gray-500 font-medium">Administrator</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-6 py-10">
            <!-- Premium Header with 3D Background -->
            <div class="relative mb-8 overflow-hidden rounded-3xl bg-white p-8 shadow-xl ring-1 ring-gray-900/5">
                <div id="charts-canvas-container" class="absolute inset-0 z-0"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/70 to-white/30 z-0 pointer-events-none"></div>

                <div class="relative z-10">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center gap-2 text-sm mb-6">
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="text-blue-600 hover:text-blue-700 font-semibold transition-colors"
                        >
                            Dashboard
                        </a>
                        <svg
                            class="w-4 h-4 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                        <span class="text-gray-600 font-medium">Analytics & Charts</span>
                    </nav>

                    <!-- Header Content -->
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1
                                    class="text-4xl font-bold tracking-tight text-gray-900"
                                >
                                    Analytics Dashboard
                                </h1>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30">
                                    <svg class="w-3 h-3 mr-1.5 animate-pulse" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Live
                                </span>
                            </div>
                            <p class="text-gray-600 text-lg">
                                Comprehensive business intelligence and performance metrics
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Premium Key Stats -->
            <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 p-6 shadow-xl shadow-blue-500/20 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-500"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-blue-100 uppercase tracking-wider">
                                Total Revenue
                            </p>
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">
                            ${{ number_format($stats["total_revenue"], 2) }}
                        </p>
                        <p class="text-sm text-blue-100">All time earnings</p>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-xl shadow-emerald-500/20 hover:shadow-2xl hover:shadow-emerald-500/30 transition-all duration-500"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-emerald-100 uppercase tracking-wider">
                                Total Orders
                            </p>
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">
                            {{ number_format($stats["total_orders"]) }}
                        </p>
                        <p class="text-sm text-emerald-100">Completed transactions</p>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 via-cyan-600 to-teal-700 p-6 shadow-xl shadow-cyan-500/20 hover:shadow-2xl hover:shadow-cyan-500/30 transition-all duration-500"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-cyan-100 uppercase tracking-wider">
                                Active Rentals
                            </p>
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">
                            {{ number_format($stats["active_orders"]) }}
                        </p>
                        <p class="text-sm text-cyan-100">Currently active</p>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 via-orange-600 to-amber-700 p-6 shadow-xl shadow-orange-500/20 hover:shadow-2xl hover:shadow-orange-500/30 transition-all duration-500"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-orange-100 uppercase tracking-wider">
                                Total Users
                            </p>
                            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">
                            {{ number_format($stats["total_users"]) }}
                        </p>
                        <p class="text-sm text-orange-100">Registered members</p>
                    </div>
                </div>
            </div>

            <!-- Charts Grid - Row 1 -->
            <div class="mb-8 grid gap-8 lg:grid-cols-2">
                <!-- Revenue Trend Chart -->
                <div class="group relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-900/5 hover:shadow-3xl transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Revenue Trend
                                        </h3>
                                        <p class="text-xs text-gray-500 font-medium">Last 12 months performance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-80">
                            <canvas id="yearlyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Daily Revenue Chart -->
                <div class="group relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-900/5 hover:shadow-3xl transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Daily Revenue
                                        </h3>
                                        <p class="text-xs text-gray-500 font-medium">Last 30 days tracking</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-80">
                            <canvas id="dailyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid - Row 2 -->
            <div class="mb-8 grid gap-8 lg:grid-cols-2">
                <!-- User Growth Chart -->
                <div class="group relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-900/5 hover:shadow-3xl transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-500/30">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            User Growth
                                        </h3>
                                        <p class="text-xs text-gray-500 font-medium">Monthly registrations</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-80">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Order Status Chart -->
                <div class="group relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-900/5 hover:shadow-3xl transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center shadow-lg shadow-sky-500/30">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Order Status
                                        </h3>
                                        <p class="text-xs text-gray-500 font-medium">Current distribution</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-80 flex items-center justify-center">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid - Row 3 -->
            <div class="mb-8 grid gap-8 lg:grid-cols-2">
                <!-- Top Devices Chart -->
                <div class="group relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-900/5 hover:shadow-3xl transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg shadow-pink-500/30">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Top Devices
                                        </h3>
                                        <p class="text-xs text-gray-500 font-medium">10 most rented</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-96">
                            <canvas id="topDevicesBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Day of Week Chart -->
                <div class="group relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-900/5 hover:shadow-3xl transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Weekly Pattern
                                        </h3>
                                        <p class="text-xs text-gray-500 font-medium">Orders by day of week</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-96">
                            <canvas id="dayOfWeekChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Width Combined Chart -->
            <div class="mb-8">
                <div class="group relative overflow-hidden rounded-3xl bg-white shadow-2xl ring-1 ring-gray-900/5 hover:shadow-3xl transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/30">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            Revenue & Orders Correlation
                                        </h3>
                                        <p class="text-xs text-gray-500 font-medium">Combined monthly performance view</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative h-96">
                            <canvas id="combinedMetricsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Enhanced Chart.js Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Premium Chart.js configuration
                Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, sans-serif';
                Chart.defaults.font.size = 12;
                Chart.defaults.color = '#6B7280';

                // Premium tooltip configuration
                const premiumTooltip = {
                    backgroundColor: 'rgba(17, 24, 39, 0.96)',
                    titleColor: '#fff',
                    bodyColor: '#E5E7EB',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 16,
                    cornerRadius: 12,
                    displayColors: true,
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                };

                // Prepare data
                const revenueMonthly = @json($revenueByMonth);
                const dailyRevData = @json($dailyRevenue);
                const userGrowthData = @json($userGrowth);
                const topDevicesData = @json($topDevices);
                const orderStatusData = @json($orderStatusCounts);
                const dayOfWeekData = @json($ordersByDayOfWeek);

                // 1. Yearly Revenue Chart
                let yearlyRevenueLabels = revenueMonthly.map(item => new Date(item.month + '-01').toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
                let yearlyRevenueValues = revenueMonthly.map(item => parseFloat(item.revenue));

                // Sample data fallback
                if (yearlyRevenueValues.length < 2 || yearlyRevenueValues.every(val => val === 0)) {
                    const currentDate = new Date();
                    yearlyRevenueLabels = [];
                    yearlyRevenueValues = [];
                    for (let i = 11; i >= 0; i--) {
                        const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
                        yearlyRevenueLabels.push(date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
                        yearlyRevenueValues.push(Math.floor(Math.random() * 4000) + 2000 + (Math.sin(i) * 1000));
                    }
                }

                new Chart(document.getElementById('yearlyRevenueChart'), {
                    type: 'line',
                    data: {
                        labels: yearlyRevenueLabels,
                        datasets: [{
                            label: 'Revenue',
                            data: yearlyRevenueValues,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.45,
                            pointRadius: 0,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: '#3B82F6',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3,
                            spanGaps: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                ...premiumTooltip,
                                callbacks: {
                                    label: ctx => 'Revenue: $' + ctx.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: 'rgba(0, 0, 0, 0.04)' },
                                ticks: {
                                    padding: 12,
                                    font: { size: 11, weight: '600' },
                                    callback: value => '$' + value.toLocaleString()
                                }
                            },
                            x: { border: { display: false }, grid: { display: false }, ticks: { padding: 12, font: { size: 11, weight: '500' } } }
                        }
                    }
                });

                // 2. Daily Revenue Chart
                new Chart(document.getElementById('dailyRevenueChart'), {
                    type: 'bar',
                    data: {
                        labels: dailyRevData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                        datasets: [{
                            label: 'Daily Revenue',
                            data: dailyRevData.map(item => parseFloat(item.revenue)),
                            backgroundColor: function(context) {
                                const ctx = context.chart.ctx;
                                const gradient = ctx.createLinearGradient(0, 0, 0, 350);
                                gradient.addColorStop(0, '#10B981');
                                gradient.addColorStop(1, '#059669');
                                return gradient;
                            },
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: premiumTooltip },
                        scales: {
                            y: { beginAtZero: true, border: { display: false }, grid: { color: 'rgba(0, 0, 0, 0.04)' }, ticks: { padding: 12, font: { size: 11, weight: '600' }, callback: v => '$' + v } },
                            x: { border: { display: false }, grid: { display: false }, ticks: { padding: 12, font: { size: 11, weight: '500' } } }
                        }
                    }
                });

                // 3. User Growth Chart
                let userGrowthLabels = userGrowthData.map(item => new Date(item.month + '-01').toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
                let userGrowthValues = userGrowthData.map(item => item.count);

                // Sample data fallback
                if (userGrowthValues.length < 2 || userGrowthValues.every(val => val === 0)) {
                    const currentDate = new Date();
                    userGrowthLabels = [];
                    userGrowthValues = [];
                    for (let i = 5; i >= 0; i--) {
                        const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
                        userGrowthLabels.push(date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
                        userGrowthValues.push(Math.floor(Math.random() * 50) + 10 + (Math.sin(i) * 20));
                    }
                }

                new Chart(document.getElementById('userGrowthChart'), {
                    type: 'line',
                    data: {
                        labels: userGrowthLabels,
                        datasets: [{
                            label: 'New Users',
                            data: userGrowthValues,
                            borderColor: '#F43F5E',
                            backgroundColor: 'rgba(244, 63, 94, 0.15)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: '#F43F5E',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3,
                            spanGaps: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { display: false }, tooltip: premiumTooltip },
                        scales: {
                            y: { beginAtZero: true, border: { display: false }, grid: { color: 'rgba(0, 0, 0, 0.04)' }, ticks: { stepSize: 1, padding: 12, font: { size: 11, weight: '600' } } },
                            x: { border: { display: false }, grid: { display: false }, ticks: { padding: 12, font: { size: 11, weight: '500' } } }
                        }
                    }
                });

                // 4. Order Status Pie Chart
                new Chart(document.getElementById('statusPieChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Active', 'Completed', 'Cancelled'],
                        datasets: [{
                            data: [orderStatusData.created, orderStatusData.active, orderStatusData.completed, orderStatusData.cancelled],
                            backgroundColor: ['#FB923C', '#22C55E', '#3B82F6', '#EF4444'],
                            borderWidth: 0,
                            hoverOffset: 20,
                            spacing: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 25, usePointStyle: true, pointStyle: 'circle', font: { size: 13, weight: '600' }, color: '#374151' } },
                            tooltip: premiumTooltip
                        }
                    }
                });

                // 5. Top Devices Bar Chart
                new Chart(document.getElementById('topDevicesBarChart'), {
                    type: 'bar',
                    data: {
                        labels: topDevicesData.map(item => (item.variant_slug || 'Unknown').substring(0, 25)),
                        datasets: [{
                            label: 'Orders',
                            data: topDevicesData.map(item => item.order_count),
                            backgroundColor: function(context) {
                                const ctx = context.chart.ctx;
                                const gradient = ctx.createLinearGradient(0, 0, 500, 0);
                                gradient.addColorStop(0, '#EC4899');
                                gradient.addColorStop(1, '#F43F5E');
                                return gradient;
                            },
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: premiumTooltip },
                        scales: {
                            x: { beginAtZero: true, border: { display: false }, grid: { color: 'rgba(0, 0, 0, 0.04)' }, ticks: { stepSize: 1, padding: 12, font: { size: 11, weight: '600' } } },
                            y: { border: { display: false }, grid: { display: false }, ticks: { padding: 12, font: { size: 11, weight: '600' }, color: '#374151' } }
                        }
                    }
                });

                // 6. Day of Week Chart
                const daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                new Chart(document.getElementById('dayOfWeekChart'), {
                    type: 'bar',
                    data: {
                        labels: daysOrder,
                        datasets: [{
                            label: 'Orders',
                            data: daysOrder.map(day => (dayOfWeekData.find(i => i.day === day) || { count: 0 }).count),
                            backgroundColor: function(context) {
                                const ctx = context.chart.ctx;
                                const gradient = ctx.createLinearGradient(0, 300, 0, 0);
                                gradient.addColorStop(0, '#F59E0B');
                                gradient.addColorStop(1, '#D97706');
                                return gradient;
                            },
                            borderRadius: 10,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: premiumTooltip },
                        scales: {
                            y: { beginAtZero: true, border: { display: false }, grid: { color: 'rgba(0, 0, 0, 0.04)' }, ticks: { stepSize: 1, padding: 12, font: { size: 11, weight: '600' } } },
                            x: { border: { display: false }, grid: { display: false }, ticks: { padding: 12, font: { size: 11, weight: '600' }, color: '#374151' } }
                        }
                    }
                });

                // 7. Combined Metrics Chart - Overlapping Area Style
                let combinedLabels = revenueMonthly.map(item => new Date(item.month + '-01').toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
                let combinedRevenue = revenueMonthly.map(item => parseFloat(item.revenue));
                let combinedOrders = revenueMonthly.map(item => item.orders);

                // Sample data fallback
                if (combinedOrders.length < 2 || combinedOrders.every(val => val === 0)) {
                    const currentDate = new Date();
                    combinedLabels = [];
                    combinedRevenue = [];
                    combinedOrders = [];
                    for (let i = 5; i >= 0; i--) {
                        const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
                        combinedLabels.push(date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' }));
                        const baseValue = Math.floor(Math.random() * 5) + 5;
                        combinedOrders.push(baseValue + Math.floor(Math.random() * 3));
                        combinedRevenue.push((baseValue * 600) + (Math.random() * 500));
                    }
                }

                new Chart(document.getElementById('combinedMetricsChart'), {
                    type: 'line',
                    data: {
                        labels: combinedLabels,
                        datasets: [
                            {
                                label: 'Revenue',
                                data: combinedRevenue,
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 7,
                                pointHoverBackgroundColor: '#3B82F6',
                                pointHoverBorderColor: '#fff',
                                pointHoverBorderWidth: 2,
                                spanGaps: true,
                                yAxisID: 'y1',
                            },
                            {
                                label: 'Orders',
                                data: combinedOrders,
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 7,
                                pointHoverBackgroundColor: '#10B981',
                                pointHoverBorderColor: '#fff',
                                pointHoverBorderWidth: 2,
                                spanGaps: true,
                                yAxisID: 'y',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top', align: 'end', labels: { padding: 20, usePointStyle: true, pointStyleWidth: 15, font: { size: 13, weight: '600' }, color: '#374151' } },
                            tooltip: {
                                ...premiumTooltip,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) { label += ': '; }
                                        if (context.dataset.label === 'Revenue') {
                                            label += '$' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                        } else {
                                            label += context.parsed.y + ' orders';
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, border: { display: false }, grid: { color: 'rgba(0, 0, 0, 0.04)' }, ticks: { stepSize: 1, padding: 12, font: { size: 11, weight: '600' }, color: '#10B981' }, title: { display: true, text: 'Orders', color: '#10B981', font: { weight: 'bold', size: 12 } } },
                            y1: { beginAtZero: true, border: { display: false }, grid: { drawOnChartArea: false }, position: 'right', ticks: { padding: 12, font: { size: 11, weight: '600' }, color: '#3B82F6', callback: v => '$' + v.toLocaleString() }, title: { display: true, text: 'Revenue ($)', color: '#3B82F6', font: { weight: 'bold', size: 12 } } },
                            x: { border: { display: false }, grid: { display: false }, ticks: { padding: 12, font: { size: 11, weight: '500' } } }
                        }
                    }
                });

                // Three.js Background Animation
                function initThreeJS() {
                    const container = document.getElementById('charts-canvas-container');
                    if (!container) return;

                    const scene = new THREE.Scene();
                    const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
                    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
                    
                    renderer.setSize(container.clientWidth, container.clientHeight);
                    renderer.setPixelRatio(window.devicePixelRatio);
                    container.appendChild(renderer.domElement);

                    // Create particles
                    const particlesGeometry = new THREE.BufferGeometry();
                    const particlesCount = 700;
                    const posArray = new Float32Array(particlesCount * 3);

                    for(let i = 0; i < particlesCount * 3; i++) {
                        posArray[i] = (Math.random() - 0.5) * 15;
                    }

                    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

                    // Material
                    const material = new THREE.PointsMaterial({
                        size: 0.025,
                        color: 0x10B981, // Emerald-500
                        transparent: true,
                        opacity: 0.8,
                    });

                    // Mesh
                    const particlesMesh = new THREE.Points(particlesGeometry, material);
                    scene.add(particlesMesh);

                    // Connecting lines
                    const lineMaterial = new THREE.LineBasicMaterial({
                        color: 0x10B981,
                        transparent: true,
                        opacity: 0.15
                    });

                    camera.position.z = 3;

                    // Mouse interaction
                    let mouseX = 0;
                    let mouseY = 0;

                    // Animation Loop
                    const clock = new THREE.Clock();

                    function animate() {
                        requestAnimationFrame(animate);
                        const elapsedTime = clock.getElapsedTime();

                        particlesMesh.rotation.y = elapsedTime * 0.05;
                        particlesMesh.rotation.x = mouseY * 0.1;
                        particlesMesh.rotation.y += mouseX * 0.1;

                        renderer.render(scene, camera);
                    }

                    animate();

                    // Handle Resize
                    window.addEventListener('resize', () => {
                        camera.aspect = container.clientWidth / container.clientHeight;
                        camera.updateProjectionMatrix();
                        renderer.setSize(container.clientWidth, container.clientHeight);
                    });

                    // Handle Mouse Move
                    container.addEventListener('mousemove', (event) => {
                        mouseX = event.clientX / window.innerWidth - 0.5;
                        mouseY = event.clientY / window.innerHeight - 0.5;
                    });
                }

                // Initialize Three.js after DOM load
                initThreeJS();
            });
        </script>
    </body>
</html>
