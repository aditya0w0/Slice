<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slice - Enterprise Apple Device Management</title>
    <meta name="description" content="Premium Apple device rental infrastructure for scaling teams. Capital efficient, fully managed, secure.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-hover:hover { background: rgba(255, 255, 255, 0.06); border-color: rgba(255, 255, 255, 0.1); }
        .text-gradient { background: linear-gradient(to right, #60A5FA, #34D399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .aurora-bg {
            background:
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(16, 185, 129, 0.08), transparent 25%);
        }

        /* Alpine.js Animations */
        [data-animate] {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-animate].animate-in {
            opacity: 1;
            transform: translateY(0);
        }

        [data-animate="fade-in-up"] {
            transform: translateY(50px);
        }

        [data-animate="fade-in-left"] {
            transform: translateX(-50px);
        }

        [data-animate="fade-in-right"] {
            transform: translateX(50px);
        }

        [data-animate="scale-in"] {
            transform: scale(0.9);
        }

        [data-animate="scale-in"].animate-in {
            transform: scale(1);
        }

        /* Stagger animations */
        [data-animate].animate-in:nth-child(1) { transition-delay: 0.1s; }
        [data-animate].animate-in:nth-child(2) { transition-delay: 0.2s; }
        [data-animate].animate-in:nth-child(3) { transition-delay: 0.3s; }
        [data-animate].animate-in:nth-child(4) { transition-delay: 0.4s; }
        [data-animate].animate-in:nth-child(5) { transition-delay: 0.5s; }
        [data-animate].animate-in:nth-child(6) { transition-delay: 0.6s; }

        /* Hover effects */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.5);
        }

        /* Typing animation */
        .typing::after {
            content: '|';
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Pulse glow */
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }

        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
            to { box-shadow: 0 0 40px rgba(59, 130, 246, 0.6); }
        }
    </style>
</head>
<body class="bg-[#0B0C10] text-slate-300 antialiased selection:bg-blue-500/30 selection:text-blue-200" x-data="app()">

    <nav class="fixed w-full z-50 top-0 start-0 border-b border-white/5 bg-[#0B0C10]/80 backdrop-blur-xl transition-all duration-300" :class="{ 'bg-[#0B0C10]/95': scrolled }" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="w-8 h-8 rounded-lg bg-linear-to-br from-blue-600 to-teal-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="self-center text-xl font-bold whitespace-nowrap text-white tracking-tight">Slice<span class="text-blue-500">.</span></span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-4 rtl:space-x-reverse">
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors py-2 px-4">Sign In</a>
                <a href="#pricing" class="text-sm bg-white text-black hover:bg-slate-200 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg px-5 py-2.5 text-center transition-all">Get Started</a>
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-slate-400 rounded-lg md:hidden hover:bg-white/5 focus:outline-none" aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-white/5 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0">
                    <li><a href="#" class="block py-2 px-3 text-white rounded md:bg-transparent md:p-0" aria-current="page">Product</a></li>
                    <li><a href="#features" class="block py-2 px-3 text-slate-400 rounded hover:bg-white/5 md:hover:bg-transparent md:hover:text-white md:p-0 transition-colors">Solutions</a></li>
                    <li><a href="#pricing" class="block py-2 px-3 text-slate-400 rounded hover:bg-white/5 md:hover:bg-transparent md:hover:text-white md:p-0 transition-colors">Pricing</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="aurora-bg min-h-screen pt-32 overflow-hidden">

        <section class="relative px-4 mx-auto max-w-7xl text-center lg:pt-20 pb-16" data-animate="fade-in-up">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full glass mb-8 border border-blue-500/20 pulse-glow" data-animate="scale-in">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span class="text-xs font-medium text-blue-200 tracking-wide uppercase">New: Apple Vision Pro Available</span>
            </div>

            <h1 class="mb-6 text-5xl font-extrabold tracking-tight leading-none text-white md:text-7xl lg:text-8xl" data-animate="fade-in-up">
                Hardware as a <br/>
                <span class="text-gradient">Service Infrastructure</span>
            </h1>

            <p class="mb-8 text-lg font-normal text-slate-400 lg:text-xl sm:px-16 lg:px-48 max-w-4xl mx-auto" data-animate="fade-in-up">
                Equip your team with the latest Apple ecosystem. Zero upfront capital.
                Automated lifecycle management. <span class="text-white font-medium">Scale your velocity, not your assets.</span>
            </p>

            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4" data-animate="fade-in-up">
                <a href="#pricing" class="inline-flex justify-center items-center py-4 px-8 text-base font-semibold text-center text-black rounded-xl bg-white hover:bg-slate-200 focus:ring-4 focus:ring-blue-900 transition-all shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] card-hover">
                    Deploy Devices
                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </a>
                <a href="#" class="inline-flex justify-center items-center py-4 px-8 text-base font-medium text-center text-white rounded-xl glass glass-hover focus:ring-4 focus:ring-gray-800 transition-all card-hover">
                    Talk to Sales
                </a>
            </div>

            <div class="mt-20 pt-10 border-t border-white/5" data-animate="fade-in-up">
                <p class="text-sm text-slate-500 mb-6 uppercase tracking-widest font-semibold">Powering next-gen teams at</p>
                <div class="flex flex-wrap justify-center gap-8 md:gap-16 opacity-40 grayscale">
                    <div class="h-8 flex items-center font-bold text-xl font-mono float-animation">ACME<span class="text-blue-500">CORP</span></div>
                    <div class="h-8 flex items-center font-bold text-xl font-sans tracking-tighter float-animation" style="animation-delay: 0.5s">STRATOS</div>
                    <div class="h-8 flex items-center font-bold text-xl font-serif italic float-animation" style="animation-delay: 1s">Velvet</div>
                    <div class="h-8 flex items-center font-bold text-xl font-mono tracking-widest float-animation" style="animation-delay: 1.5s">NEXUS</div>
                    <div class="h-8 flex items-center font-bold text-xl font-sans float-animation" style="animation-delay: 2s">KINETIC</div>
                </div>
            </div>
        </section>

        <section id="features" class="py-24 px-4 mx-auto max-w-7xl">
            <div class="mb-16 md:text-center max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">The Operating System for IT</h2>
                <p class="mt-4 text-slate-400">We don't just rent hardware. We provide a full-stack platform to manage your company's physical infrastructure.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 md:grid-rows-2 gap-4 h-auto md:h-[600px]">

                <div class="md:col-span-2 md:row-span-2 rounded-3xl glass p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                        <svg class="w-64 h-64 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                    </div>
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center mb-6 text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">M3 Chip Inventory</h3>
                            <p class="text-slate-400">Priority access to the latest Apple Silicon. From MacBook Air to Ultra-high performance Mac Studio clusters. Delivered in 24 hours.</p>
                        </div>
                        <div class="mt-8">
                            <div class="bg-white/5 rounded-xl p-4 border border-white/10 backdrop-blur-md max-w-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-mono text-slate-300">MacBook Pro 16" M3 Max</span>
                                    <span class="text-xs px-2 py-1 bg-green-500/20 text-green-400 rounded-full">In Stock</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-1.5">
                                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl glass p-8 glass-hover transition-all cursor-pointer">
                    <div class="w-10 h-10 rounded-lg bg-teal-500/20 flex items-center justify-center mb-4 text-teal-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-2">Instant MDM</h4>
                    <p class="text-sm text-slate-400">Devices arrive pre-enrolled in Jamf or Kandji. Zero-touch deployment for your IT team.</p>
                </div>

                <div class="rounded-3xl glass p-8 glass-hover transition-all cursor-pointer">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center mb-4 text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-2">OpEx Model</h4>
                    <p class="text-sm text-slate-400">Shift from heavy CapEx to predictable monthly OpEx. Keep cash flow for growth.</p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-linear-to-br from-slate-900 via-slate-800 to-slate-900" data-animate="fade-in-up">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4" data-animate="fade-in-up">Infrastructure at Scale</h2>
                    <p class="text-xl text-slate-300 max-w-3xl mx-auto" data-animate="fade-in-up" data-animate-delay="200">Trusted by enterprise teams worldwide to power their hardware infrastructure</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-8 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-all duration-300" data-animate="fade-in-up" data-animate-delay="400">
                        <div class="text-4xl md:text-5xl font-bold text-blue-400 mb-2" x-data="{ count: 0 }" x-init="$el.innerText = '{{ $currencyService->formatIdr($pricingIdr['stats']['revenue']) }}'; setTimeout(() => { $el.innerText = '{{ $currencyService->formatIdr($pricingIdr['stats']['revenue']) }}'; }, 1000);" x-text="count">{{ $currencyService->formatIdr($pricingIdr['stats']['revenue']) }}</div>
                        <div class="text-slate-300 font-medium">Annual Revenue Processed</div>
                    </div>

                    <div class="text-center p-8 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-all duration-300" data-animate="fade-in-up" data-animate-delay="600">
                        <div class="text-4xl md:text-5xl font-bold text-emerald-400 mb-2" x-data="{ count: 0 }" x-init="$el.innerText = '12k+'; setTimeout(() => { $el.innerText = '12k+'; }, 1000);" x-text="count">12k+</div>
                        <div class="text-slate-300 font-medium">Active Devices Managed</div>
                    </div>

                    <div class="text-center p-8 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-all duration-300" data-animate="fade-in-up" data-animate-delay="800">
                        <div class="text-4xl md:text-5xl font-bold text-purple-400 mb-2" x-data="{ count: 0 }" x-init="$el.innerText = '99.9%'; setTimeout(() => { $el.innerText = '99.9%'; }, 1000);" x-text="count">99.9%</div>
                        <div class="text-slate-300 font-medium">Uptime SLA</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 max-w-7xl mx-auto px-4" data-animate="fade-in-up">
             <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-white">Device Catalog</h2>
                    <p class="text-slate-400 mt-2">Ready for immediate dispatch.</p>
                </div>
                <a href="{{ route('devices') }}" class="text-blue-400 hover:text-blue-300 font-medium flex items-center">
                    View all hardware
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $devices = [
                        ['name' => 'MacBook Pro', 'icon' => 'laptop', 'color' => 'blue', 'status' => 'In Stock', 'price' => $pricingIdr['devices']['macbook_pro']],
                        ['name' => 'iPhone 15 Pro', 'icon' => 'smartphone', 'color' => 'purple', 'status' => 'In Stock', 'price' => $pricingIdr['devices']['iphone_15_pro']],
                        ['name' => 'iPad Pro', 'icon' => 'tablet', 'color' => 'emerald', 'status' => 'Limited', 'price' => $pricingIdr['devices']['ipad_pro']],
                        ['name' => 'Studio Display', 'icon' => 'monitor', 'color' => 'orange', 'status' => 'In Stock', 'price' => $pricingIdr['devices']['studio_display']]
                    ];
                @endphp
                @foreach($devices as $index => $device)
                <div class="group relative rounded-3xl glass p-6 hover:scale-105 transition-all duration-500 hover:shadow-2xl hover:shadow-{{ $device['color'] }}-500/20 border border-white/10 hover:border-{{ $device['color'] }}-500/30" data-animate="fade-in-up" data-animate-delay="{{ ($index + 1) * 200 }}">
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4 z-10">
                        <span class="px-2 py-1 bg-{{ $device['color'] }}-500/20 text-{{ $device['color'] }}-400 text-xs font-medium rounded-full border border-{{ $device['color'] }}-500/30">
                            {{ $device['status'] }}
                        </span>
                    </div>

                    <!-- Device Icon -->
                    <div class="relative mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-{{ $device['color'] }}-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            @if($device['icon'] === 'laptop')
                                <svg class="w-8 h-8 text-{{ $device['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                            @elseif($device['icon'] === 'smartphone')
                                <svg class="w-8 h-8 text-{{ $device['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            @elseif($device['icon'] === 'tablet')
                                <svg class="w-8 h-8 text-{{ $device['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3 3-3 3-3-3 3-3zM12 22l3-3-3-3-3 3 3 3zM2 12l3 3-3 3-3-3 3-3zM22 12l-3 3 3 3 3-3-3-3z"/>
                                </svg>
                            @elseif($device['icon'] === 'monitor')
                                <svg class="w-8 h-8 text-{{ $device['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>

                        <!-- Device Image Placeholder -->
                        <div class="aspect-square bg-linear-to-br from-{{ $device['color'] }}-500/10 to-{{ $device['color'] }}-900/5 rounded-2xl border border-{{ $device['color'] }}-500/20 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                            <div class="text-xs text-{{ $device['color'] }}-400/60 font-mono tracking-wider">
                                {{ $device['name'] }}
                            </div>
                        </div>
                    </div>

                    <!-- Device Info -->
                    <div class="space-y-3">
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-{{ $device['color'] }}-300 transition-colors">{{ $device['name'] }}</h3>
                            <p class="text-sm text-slate-400">Latest Apple Silicon</p>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-white/5">
                            <div class="text-2xl font-bold text-white">{{ $currencyService->formatIdr($device['price']) }}<span class="text-sm font-normal text-slate-500">/mo</span></div>
                            <button class="px-4 py-2 bg-{{ $device['color'] }}-500/20 hover:bg-{{ $device['color'] }}-500/30 text-{{ $device['color'] }}-400 rounded-lg text-sm font-medium transition-colors border border-{{ $device['color'] }}-500/30">
                                Deploy
                            </button>
                        </div>
                    </div>

                    <!-- Hover Glow Effect -->
                    <div class="absolute inset-0 rounded-3xl bg-{{ $device['color'] }}-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                </div>
                @endforeach
            </div>
        </section>

        <section id="pricing" class="py-24 relative overflow-hidden" data-animate="fade-in-up">
             <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-white sm:text-4xl" data-animate="fade-in-up">Transparent Scaling</h2>
                    <p class="mt-4 text-xl text-slate-400" data-animate="fade-in-up" data-animate-delay="200">Simple pricing that grows with your team.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="rounded-2xl glass p-8 flex flex-col hover:scale-105 transition-all duration-300" data-animate="fade-in-up" data-animate-delay="400">
                        <h3 class="text-xl font-semibold text-white">Starter</h3>
                        <div class="my-4">
                            <span class="text-4xl font-bold text-white">{{ $currencyService->formatIdr($pricingIdr['starter']) }}</span>
                            <span class="text-slate-500">/device</span>
                        </div>
                        <ul class="space-y-4 mb-8 flex-1 text-slate-300 text-sm">
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>Latest Models</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>AppleCare+ Included</li>
                        </ul>
                        <a href="#" class="w-full block text-center py-2.5 rounded-lg border border-white/20 text-white hover:bg-white hover:text-black transition-colors font-medium text-sm">Start Renting</a>
                    </div>

                    <div class="rounded-2xl bg-linear-to-b from-blue-600/20 to-blue-900/10 border border-blue-500/30 p-8 flex flex-col relative transform md:-translate-y-4 hover:scale-105 transition-all duration-300" data-animate="fade-in-up" data-animate-delay="600">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase">Best Value</span>
                        </div>
                        <h3 class="text-xl font-semibold text-white">Growth</h3>
                        <div class="my-4">
                            <span class="text-4xl font-bold text-white">{{ $currencyService->formatIdr($pricingIdr['growth']) }}</span>
                            <span class="text-slate-500">/user bundle</span>
                        </div>
                        <p class="text-xs text-blue-200 mb-6">MacBook + iPhone + iPad included</p>
                        <ul class="space-y-4 mb-8 flex-1 text-slate-300 text-sm">
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>Everything in Starter</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>MDM Setup</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>24/7 Slack Support</li>
                        </ul>
                        <a href="#" class="w-full block text-center py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition-colors font-medium text-sm shadow-lg shadow-blue-500/25">Deploy Team</a>
                    </div>

                    <div class="rounded-2xl glass p-8 flex flex-col hover:scale-105 transition-all duration-300" data-animate="fade-in-up" data-animate-delay="800">
                        <h3 class="text-xl font-semibold text-white">Enterprise</h3>
                        <div class="my-4">
                            <span class="text-4xl font-bold text-white">Custom</span>
                        </div>
                        <ul class="space-y-4 mb-8 flex-1 text-slate-300 text-sm">
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>Volume Discounts</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>SOC 2 Type II</li>
                            <li class="flex items-center"><svg class="w-4 h-4 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>Dedicated Account Mgr</li>
                        </ul>
                        <a href="#" class="w-full block text-center py-2.5 rounded-lg border border-white/20 text-white hover:bg-white hover:text-black transition-colors font-medium text-sm">Contact Sales</a>
                    </div>
                </div>
            </div>
        </section>

        <footer class="border-t border-white/5 bg-[#050507]" data-animate="fade-in-up">
            <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <span class="text-xl font-bold text-white tracking-tight">Slice<span class="text-blue-500">.</span></span>
                        <p class="ml-4 text-sm text-slate-500">© {{ date('Y') }} Slice Inc. All rights reserved.</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-slate-500 hover:text-white transition-colors">Privacy</a>
                        <a href="#" class="text-slate-500 hover:text-white transition-colors">Terms</a>
                        <a href="#" class="text-slate-500 hover:text-white transition-colors">Twitter</a>
                    </div>
                </div>
            </div>
        </footer>

    </main>

    <script>
        function app() {
            return {
                init() {
                    this.animateCounters();
                    this.initScrollAnimations();
                },

                animateCounters() {
                    const counters = document.querySelectorAll('[data-counter]');
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-counter'));
                        let current = 0;
                        const increment = target / 100;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            counter.textContent = this.formatNumber(Math.floor(current));
                        }, 20);
                    });
                },

                formatNumber(num) {
                    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M+';
                    if (num >= 1000) return (num / 1000).toFixed(0) + 'k+';
                    return num;
                },

                initScrollAnimations() {
                    const observerOptions = {
                        threshold: 0.1,
                        rootMargin: '0px 0px -50px 0px'
                    };

                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('animate-in');
                            }
                        });
                    }, observerOptions);

                    document.querySelectorAll('[data-animate]').forEach(el => {
                        observer.observe(el);
                    });
                }
            }
        }
    </script>

</body>
</html>
