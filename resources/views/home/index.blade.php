<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Slice - Enterprise Apple Device Management</title>
        <meta
            name="description"
            content="Premium Apple device rental infrastructure for scaling teams. Capital efficient, fully managed, secure."
        />

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />

        @vite(["resources/css/app.css", "resources/js/app.js"])

        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .glass-hover:hover {
                background: rgba(255, 255, 255, 0.06);
                border-color: rgba(255, 255, 255, 0.1);
            }
            .text-gradient {
                background: linear-gradient(to right, #60a5fa, #34d399);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
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

            [data-animate='fade-in-up'] {
                transform: translateY(50px);
            }

            [data-animate='fade-in-left'] {
                transform: translateX(-50px);
            }

            [data-animate='fade-in-right'] {
                transform: translateX(50px);
            }

            [data-animate='scale-in'] {
                transform: scale(0.9);
            }

            [data-animate='scale-in'].animate-in {
                transform: scale(1);
            }

            /* Stagger animations */
            [data-animate].animate-in:nth-child(1) {
                transition-delay: 0.1s;
            }
            [data-animate].animate-in:nth-child(2) {
                transition-delay: 0.2s;
            }
            [data-animate].animate-in:nth-child(3) {
                transition-delay: 0.3s;
            }
            [data-animate].animate-in:nth-child(4) {
                transition-delay: 0.4s;
            }
            [data-animate].animate-in:nth-child(5) {
                transition-delay: 0.5s;
            }
            [data-animate].animate-in:nth-child(6) {
                transition-delay: 0.6s;
            }

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
                0%,
                50% {
                    opacity: 1;
                }
                51%,
                100% {
                    opacity: 0;
                }
            }

            /* Floating animation */
            @keyframes float {
                0%,
                100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-10px);
                }
            }

            .float-animation {
                animation: float 3s ease-in-out infinite;
            }

            /* Pulse glow */
            .pulse-glow {
                animation: pulse-glow 2s ease-in-out infinite alternate;
            }

            @keyframes pulse-glow {
                from {
                    box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
                }
                to {
                    box-shadow: 0 0 40px rgba(59, 130, 246, 0.6);
                }
            }
        </style>
    </head>
    <body
        class="bg-[#0B0C10] text-slate-300 antialiased selection:bg-blue-500/30 selection:text-blue-200"
        x-data="app()"
    >
        <nav
            class="fixed start-0 top-0 z-50 w-full border-b border-white/5 bg-[#0B0C10]/80 backdrop-blur-xl transition-all duration-300"
            :class="{ 'bg-[#0B0C10]/95': scrolled }"
            x-data="{ scrolled: false }"
            @scroll.window="scrolled = (window.pageYOffset > 50)"
        >
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between p-4">
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-blue-600 to-teal-500 shadow-lg shadow-blue-500/20"
                    >
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                    </div>
                    <span class="self-center text-xl font-bold tracking-tight whitespace-nowrap text-white">
                        Slice
                        <span class="text-blue-500">.</span>
                    </span>
                </a>
                <div class="flex space-x-3 md:order-2 md:space-x-4 rtl:space-x-reverse">
                    <a
                        href="{{ route("login") }}"
                        class="px-4 py-2 text-sm font-medium text-slate-300 transition-colors hover:text-white"
                    >
                        Sign In
                    </a>
                    <a
                        href="#pricing"
                        class="rounded-lg bg-white px-5 py-2.5 text-center text-sm font-semibold text-black transition-all hover:bg-slate-200 focus:ring-4 focus:ring-blue-300 focus:outline-none"
                    >
                        Get Started
                    </a>
                    <button
                        data-collapse-toggle="navbar-sticky"
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-slate-400 hover:bg-white/5 focus:outline-none md:hidden"
                        aria-controls="navbar-sticky"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Open main menu</span>
                        <svg
                            class="h-5 w-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 17 14"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15"
                            />
                        </svg>
                    </button>
                </div>
                <div class="hidden w-full items-center justify-between md:order-1 md:flex md:w-auto" id="navbar-sticky">
                    <ul
                        class="mt-4 flex flex-col rounded-lg border border-white/5 p-4 font-medium md:mt-0 md:flex-row md:space-x-8 md:border-0 md:p-0 rtl:space-x-reverse"
                    >
                        <li>
                            <a
                                href="#"
                                class="block rounded px-3 py-2 text-white md:bg-transparent md:p-0"
                                aria-current="page"
                            >
                                Product
                            </a>
                        </li>
                        <li>
                            <a
                                href="#features"
                                class="block rounded px-3 py-2 text-slate-400 transition-colors hover:bg-white/5 md:p-0 md:hover:bg-transparent md:hover:text-white"
                            >
                                Solutions
                            </a>
                        </li>
                        <li>
                            <a
                                href="#pricing"
                                class="block rounded px-3 py-2 text-slate-400 transition-colors hover:bg-white/5 md:p-0 md:hover:bg-transparent md:hover:text-white"
                            >
                                Pricing
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="aurora-bg min-h-screen overflow-hidden pt-32">
            <section class="relative mx-auto max-w-7xl px-4 pb-16 text-center lg:pt-20" data-animate="fade-in-up">
                <div
                    class="glass pulse-glow mb-8 inline-flex items-center gap-2 rounded-full border border-blue-500/20 px-3 py-1"
                    data-animate="scale-in"
                >
                    <span class="relative flex h-2 w-2">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"
                        ></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                    </span>
                    <span class="text-xs font-medium tracking-wide text-blue-200 uppercase">
                        New: Apple Vision Pro Available
                    </span>
                </div>

                <h1
                    class="mb-6 text-5xl leading-none font-extrabold tracking-tight text-white md:text-7xl lg:text-8xl"
                    data-animate="fade-in-up"
                >
                    Hardware as a
                    <br />
                    <span class="text-gradient">Service Infrastructure</span>
                </h1>

                <p
                    class="mx-auto mb-8 max-w-4xl text-lg font-normal text-slate-400 sm:px-16 lg:px-48 lg:text-xl"
                    data-animate="fade-in-up"
                >
                    Equip your team with the latest Apple ecosystem. Zero upfront capital. Automated lifecycle
                    management.
                    <span class="font-medium text-white">Scale your velocity, not your assets.</span>
                </p>

                <div
                    class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4"
                    data-animate="fade-in-up"
                >
                    <a
                        href="#pricing"
                        class="card-hover inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-center text-base font-semibold text-black shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] transition-all hover:bg-slate-200 focus:ring-4 focus:ring-blue-900"
                    >
                        Deploy Devices
                        <svg
                            class="ms-2 h-3.5 w-3.5 rtl:rotate-180"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 10"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9"
                            />
                        </svg>
                    </a>
                    <a
                        href="#"
                        class="glass glass-hover card-hover inline-flex items-center justify-center rounded-xl px-8 py-4 text-center text-base font-medium text-white transition-all focus:ring-4 focus:ring-gray-800"
                    >
                        Talk to Sales
                    </a>
                </div>

                <div class="mt-20 border-t border-white/5 pt-10" data-animate="fade-in-up">
                    <p class="mb-6 text-sm font-semibold tracking-widest text-slate-500 uppercase">
                        Powering next-gen teams at
                    </p>
                    <div class="flex flex-wrap justify-center gap-8 opacity-40 grayscale md:gap-16">
                        <div class="float-animation flex h-8 items-center font-mono text-xl font-bold">
                            ACME
                            <span class="text-blue-500">CORP</span>
                        </div>
                        <div
                            class="float-animation flex h-8 items-center font-sans text-xl font-bold tracking-tighter"
                            style="animation-delay: 0.5s"
                        >
                            STRATOS
                        </div>
                        <div
                            class="float-animation flex h-8 items-center font-serif text-xl font-bold italic"
                            style="animation-delay: 1s"
                        >
                            Velvet
                        </div>
                        <div
                            class="float-animation flex h-8 items-center font-mono text-xl font-bold tracking-widest"
                            style="animation-delay: 1.5s"
                        >
                            NEXUS
                        </div>
                        <div
                            class="float-animation flex h-8 items-center font-sans text-xl font-bold"
                            style="animation-delay: 2s"
                        >
                            KINETIC
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="mx-auto max-w-7xl px-4 py-24">
                <div class="mx-auto mb-16 max-w-3xl md:text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        The Operating System for IT
                    </h2>
                    <p class="mt-4 text-slate-400">
                        We don't just rent hardware. We provide a full-stack platform to manage your company's physical
                        infrastructure.
                    </p>
                </div>

                <div class="grid h-auto grid-cols-1 gap-4 md:h-[600px] md:grid-cols-3 md:grid-rows-2">
                    <div class="glass group relative overflow-hidden rounded-3xl p-8 md:col-span-2 md:row-span-2">
                        <div
                            class="absolute top-0 right-0 p-8 opacity-20 transition-opacity duration-500 group-hover:opacity-30"
                        >
                            <svg class="h-64 w-64 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"
                                />
                            </svg>
                        </div>
                        <div class="relative z-10 flex h-full flex-col justify-between">
                            <div>
                                <div
                                    class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/20 text-blue-400"
                                >
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                        ></path>
                                    </svg>
                                </div>
                                <h3 class="mb-2 text-2xl font-bold text-white">M3 Chip Inventory</h3>
                                <p class="text-slate-400">
                                    Priority access to the latest Apple Silicon. From MacBook Air to Ultra-high
                                    performance Mac Studio clusters. Delivered in 24 hours.
                                </p>
                            </div>
                            <div class="mt-8">
                                <div class="max-w-sm rounded-xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                    <div class="mb-2 flex items-center justify-between">
                                        <span class="font-mono text-sm text-slate-300">MacBook Pro 16" M3 Max</span>
                                        <span class="rounded-full bg-green-500/20 px-2 py-1 text-xs text-green-400">
                                            In Stock
                                        </span>
                                    </div>
                                    <div class="h-1.5 w-full rounded-full bg-white/10">
                                        <div class="h-1.5 rounded-full bg-blue-500" style="width: 85%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass glass-hover cursor-pointer rounded-3xl p-8 transition-all">
                        <div
                            class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-teal-500/20 text-teal-400"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                ></path>
                            </svg>
                        </div>
                        <h4 class="mb-2 text-xl font-bold text-white">Instant MDM</h4>
                        <p class="text-sm text-slate-400">
                            Devices arrive pre-enrolled in Jamf or Kandji. Zero-touch deployment for your IT team.
                        </p>
                    </div>

                    <div class="glass glass-hover cursor-pointer rounded-3xl p-8 transition-all">
                        <div
                            class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-purple-500/20 text-purple-400"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                ></path>
                            </svg>
                        </div>
                        <h4 class="mb-2 text-xl font-bold text-white">OpEx Model</h4>
                        <p class="text-sm text-slate-400">
                            Shift from heavy CapEx to predictable monthly OpEx. Keep cash flow for growth.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="bg-linear-to-br from-slate-900 via-slate-800 to-slate-900 py-20" data-animate="fade-in-up">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 text-center">
                        <h2 class="mb-4 text-3xl font-bold text-white md:text-4xl" data-animate="fade-in-up">
                            Infrastructure at Scale
                        </h2>
                        <p
                            class="mx-auto max-w-3xl text-xl text-slate-300"
                            data-animate="fade-in-up"
                            data-animate-delay="200"
                        >
                            Trusted by enterprise teams worldwide to power their hardware infrastructure
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div
                            class="rounded-2xl border border-white/10 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:bg-white/10"
                            data-animate="fade-in-up"
                            data-animate-delay="400"
                        >
                            <div
                                class="mb-2 text-4xl font-bold text-blue-400 md:text-5xl"
                                x-data="{ count: 0 }"
                                x-init="
                                    $el.innerText =
                                        '{{ $currencyService->formatIdr($pricingIdr["stats"]["revenue"]) }}'
                                    setTimeout(() => {
                                        $el.innerText =
                                            '{{ $currencyService->formatIdr($pricingIdr["stats"]["revenue"]) }}'
                                    }, 1000)
                                "
                                x-text="count"
                            >
                                {{ $currencyService->formatIdr($pricingIdr["stats"]["revenue"]) }}
                            </div>
                            <div class="font-medium text-slate-300">Annual Revenue Processed</div>
                        </div>

                        <div
                            class="rounded-2xl border border-white/10 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:bg-white/10"
                            data-animate="fade-in-up"
                            data-animate-delay="600"
                        >
                            <div
                                class="mb-2 text-4xl font-bold text-emerald-400 md:text-5xl"
                                x-data="{ count: 0 }"
                                x-init="
                                    $el.innerText = '12k+'
                                    setTimeout(() => {
                                        $el.innerText = '12k+'
                                    }, 1000)
                                "
                                x-text="count"
                            >
                                12k+
                            </div>
                            <div class="font-medium text-slate-300">Active Devices Managed</div>
                        </div>

                        <div
                            class="rounded-2xl border border-white/10 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:bg-white/10"
                            data-animate="fade-in-up"
                            data-animate-delay="800"
                        >
                            <div
                                class="mb-2 text-4xl font-bold text-purple-400 md:text-5xl"
                                x-data="{ count: 0 }"
                                x-init="
                                    $el.innerText = '99.9%'
                                    setTimeout(() => {
                                        $el.innerText = '99.9%'
                                    }, 1000)
                                "
                                x-text="count"
                            >
                                99.9%
                            </div>
                            <div class="font-medium text-slate-300">Uptime SLA</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-4 py-24" data-animate="fade-in-up">
                <div class="mb-12 flex items-end justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Device Catalog</h2>
                        <p class="mt-2 text-slate-400">Ready for immediate dispatch.</p>
                    </div>
                    <a
                        href="{{ route("devices") }}"
                        class="flex items-center font-medium text-blue-400 hover:text-blue-300"
                    >
                        View all hardware
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"
                            ></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                        $devices = [
                            ["name" => "MacBook Pro", "image" => "/images/devices/macbook-pro-16.png", "color" => "blue", "status" => "In Stock", "price" => $pricingIdr["devices"]["macbook_pro"]],
                            ["name" => "iPhone 15 Pro", "image" => "/images/devices/iphone-15-pro-max.png", "color" => "purple", "status" => "In Stock", "price" => $pricingIdr["devices"]["iphone_15_pro"]],
                            ["name" => "iPad Pro", "image" => "/images/devices/ipad-pro-12.png", "color" => "emerald", "status" => "Limited", "price" => $pricingIdr["devices"]["ipad_pro"]],
                            ["name" => "Studio Display", "image" => "/images/devices/macbook-pro-16.png", "color" => "orange", "status" => "In Stock", "price" => $pricingIdr["devices"]["studio_display"]],
                        ];
                    @endphp

                    @foreach ($devices as $index => $device)
                        <a
                            href="{{ route("devices") }}"
                            class="group glass hover:shadow-{{ $device["color"] }}-500/20 hover:border-{{ $device["color"] }}-500/30 relative rounded-3xl border border-white/10 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl"
                            data-animate="fade-in-up"
                            data-animate-delay="{{ ($index + 1) * 200 }}"
                        >
                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4 z-10">
                                <span
                                    class="bg-{{ $device["color"] }}-500/20 text-{{ $device["color"] }}-400 border-{{ $device["color"] }}-500/30 rounded-full border px-2 py-1 text-xs font-medium"
                                >
                                    {{ $device["status"] }}
                                </span>
                            </div>

                            <!-- Device Image -->
                            <div class="relative mb-6">
                                <div
                                    class="from-{{ $device["color"] }}-500/10 to-{{ $device["color"] }}-900/5 border-{{ $device["color"] }}-500/20 flex aspect-square items-center justify-center overflow-hidden rounded-2xl border bg-gradient-to-br transition-transform duration-500 group-hover:scale-105"
                                >
                                    <img
                                        src="{{ $device["image"] }}"
                                        alt="{{ $device["name"] }}"
                                        class="h-full w-full object-contain p-4 drop-shadow-2xl transition-transform duration-500 group-hover:scale-110"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                    />
                                    <div
                                        class="text-{{ $device["color"] }}-400/60 hidden font-mono text-xs tracking-wider"
                                    >
                                        {{ $device["name"] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Device Info -->
                            <div class="space-y-3">
                                <div>
                                    <h3
                                        class="group-hover:text-{{ $device["color"] }}-300 text-lg font-bold text-white transition-colors"
                                    >
                                        {{ $device["name"] }}
                                    </h3>
                                    <p class="text-sm text-slate-400">Latest Apple Silicon</p>
                                </div>

                                <div class="flex items-center justify-between border-t border-white/5 pt-2">
                                    <div class="text-2xl font-bold text-white">
                                        {{ $currencyService->formatIdr($device["price"]) }}
                                        <span class="text-sm font-normal text-slate-500">/mo</span>
                                    </div>
                                    <button
                                        class="bg-{{ $device["color"] }}-500/20 hover:bg-{{ $device["color"] }}-500/30 text-{{ $device["color"] }}-400 border-{{ $device["color"] }}-500/30 rounded-lg border px-4 py-2 text-sm font-medium transition-colors"
                                    >
                                        Deploy
                                    </button>
                                </div>
                            </div>

                            <!-- Hover Glow Effect -->
                            <div
                                class="bg-{{ $device["color"] }}-500/5 pointer-events-none absolute inset-0 rounded-3xl opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                            ></div>
                        </a>
                    @endforeach
                </div>
            </section>

            <section id="pricing" class="relative overflow-hidden py-24" data-animate="fade-in-up">
                <div
                    class="pointer-events-none absolute top-1/2 left-1/2 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-500/10 blur-3xl"
                ></div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 text-center">
                        <h2 class="text-3xl font-bold text-white sm:text-4xl" data-animate="fade-in-up">
                            Transparent Scaling
                        </h2>
                        <p class="mt-4 text-xl text-slate-400" data-animate="fade-in-up" data-animate-delay="200">
                            Simple pricing that grows with your team.
                        </p>
                    </div>

                    <div class="mx-auto grid max-w-5xl grid-cols-1 gap-8 md:grid-cols-3">
                        <div
                            class="glass flex flex-col rounded-2xl p-8 transition-all duration-300 hover:scale-105"
                            data-animate="fade-in-up"
                            data-animate-delay="400"
                        >
                            <h3 class="text-xl font-semibold text-white">Starter</h3>
                            <div class="my-4">
                                <span class="text-4xl font-bold text-white">
                                    {{ $currencyService->formatIdr($pricingIdr["starter"]) }}
                                </span>
                                <span class="text-slate-500">/device</span>
                            </div>
                            <ul class="mb-8 flex-1 space-y-4 text-sm text-slate-300">
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Latest Models
                                </li>
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    AppleCare+ Included
                                </li>
                            </ul>
                            <a
                                href="#"
                                class="block w-full rounded-lg border border-white/20 py-2.5 text-center text-sm font-medium text-white transition-colors hover:bg-white hover:text-black"
                            >
                                Start Renting
                            </a>
                        </div>

                        <div
                            class="relative flex transform flex-col rounded-2xl border border-blue-500/30 bg-linear-to-b from-blue-600/20 to-blue-900/10 p-8 transition-all duration-300 hover:scale-105 md:-translate-y-4"
                            data-animate="fade-in-up"
                            data-animate-delay="600"
                        >
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 transform">
                                <span
                                    class="rounded-full bg-blue-600 px-3 py-1 text-xs font-bold tracking-wide text-white uppercase"
                                >
                                    Best Value
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-white">Growth</h3>
                            <div class="my-4">
                                <span class="text-4xl font-bold text-white">
                                    {{ $currencyService->formatIdr($pricingIdr["growth"]) }}
                                </span>
                                <span class="text-slate-500">/user bundle</span>
                            </div>
                            <p class="mb-6 text-xs text-blue-200">MacBook + iPhone + iPad included</p>
                            <ul class="mb-8 flex-1 space-y-4 text-sm text-slate-300">
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Everything in Starter
                                </li>
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    MDM Setup
                                </li>
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    24/7 Slack Support
                                </li>
                            </ul>
                            <a
                                href="#"
                                class="block w-full rounded-lg bg-blue-600 py-2.5 text-center text-sm font-medium text-white shadow-lg shadow-blue-500/25 transition-colors hover:bg-blue-500"
                            >
                                Deploy Team
                            </a>
                        </div>

                        <div
                            class="glass flex flex-col rounded-2xl p-8 transition-all duration-300 hover:scale-105"
                            data-animate="fade-in-up"
                            data-animate-delay="800"
                        >
                            <h3 class="text-xl font-semibold text-white">Enterprise</h3>
                            <div class="my-4">
                                <span class="text-4xl font-bold text-white">Custom</span>
                            </div>
                            <ul class="mb-8 flex-1 space-y-4 text-sm text-slate-300">
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Volume Discounts
                                </li>
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    SOC 2 Type II
                                </li>
                                <li class="flex items-center">
                                    <svg class="mr-3 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Dedicated Account Mgr
                                </li>
                            </ul>
                            <a
                                href="#"
                                class="block w-full rounded-lg border border-white/20 py-2.5 text-center text-sm font-medium text-white transition-colors hover:bg-white hover:text-black"
                            >
                                Contact Sales
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="border-t border-white/5 bg-[#050507]" data-animate="fade-in-up">
                <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    <div class="flex flex-col items-center justify-between md:flex-row">
                        <div class="mb-4 flex items-center md:mb-0">
                            <img src="{{ asset("images/logo.svg") }}" alt="Logo" class="mr-4 h-12 w-12" />
                            <p class="text-sm text-slate-500">© {{ date("Y") }} All rights reserved.</p>
                        </div>
                        <div class="flex space-x-6">
                            <a href="#" class="text-slate-500 transition-colors hover:text-white">Privacy</a>
                            <a href="#" class="text-slate-500 transition-colors hover:text-white">Terms</a>
                            <a href="#" class="text-slate-500 transition-colors hover:text-white">Twitter</a>
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
                        counters.forEach((counter) => {
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
                            rootMargin: '0px 0px -50px 0px',
                        };

                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting) {
                                    entry.target.classList.add('animate-in');
                                }
                            });
                        }, observerOptions);

                        document.querySelectorAll('[data-animate]').forEach((el) => {
                            observer.observe(el);
                        });
                    },
                };
            }
        </script>
    </body>
</html>
