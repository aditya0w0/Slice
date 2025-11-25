<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $device->name }} - Slice Enterprise</title>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />

        @vite("resources/css/app.css")

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .aurora-bg {
                background:
                    radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.08), transparent 25%),
                    radial-gradient(circle at 85% 30%, rgba(16, 185, 129, 0.08), transparent 25%);
                background-color: #0b0c10;
            }
            /* Custom Select Styling for Dark Mode */
            select {
                -webkit-appearance: none;
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
            }
        </style>
    </head>
    <body class="bg-[#0B0C10] text-slate-300 antialiased">
        <nav class="fixed start-0 top-0 z-50 w-full border-b border-white/5 bg-[#0B0C10]/80 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between p-4">
                <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset("images/logo.svg") }}" alt="Logo" class="h-12 w-12" />
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/devices" class="text-sm font-medium text-slate-400 transition-colors hover:text-white">
                        Back to Catalog
                    </a>
                </div>
            </div>
        </nav>

        <main class="aurora-bg min-h-screen pt-32 pb-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <nav class="mb-8 flex text-sm text-slate-500" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-2">
                        <li><a href="/" class="hover:text-blue-400">Home</a></li>
                        <li><span class="text-slate-600">/</span></li>
                        <li><a href="/devices" class="hover:text-blue-400">Catalog</a></li>
                        <li><span class="text-slate-600">/</span></li>
                        <li class="text-slate-300">{{ $device->name }}</li>
                    </ol>
                </nav>

                <div class="items-start lg:grid lg:grid-cols-2 lg:gap-16">
                    <div class="group relative">
                        <div
                            class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-blue-600 to-teal-600 opacity-10 blur transition duration-1000 group-hover:opacity-20 group-hover:duration-200"
                        ></div>

                        <div
                            class="glass relative flex aspect-[4/3] items-center justify-center overflow-hidden rounded-3xl p-8 lg:p-12"
                        >
                            <img
                                src="{{ $device->image ?? "/images/product-iphone.svg" }}"
                                alt="{{ $device->name }}"
                                class="h-full w-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-105"
                            />
                        </div>

                        <div class="mt-6 grid grid-cols-3 gap-4">
                            <div class="glass rounded-xl p-4 text-center">
                                <div class="mb-1 text-xs font-semibold tracking-wider text-blue-400 uppercase">
                                    Stock
                                </div>
                                <div class="font-medium text-white">Available</div>
                            </div>
                            <div class="glass rounded-xl p-4 text-center">
                                <div class="mb-1 text-xs font-semibold tracking-wider text-blue-400 uppercase">
                                    Delivery
                                </div>
                                <div class="font-medium text-white">24 Hours</div>
                            </div>
                            <div class="glass rounded-xl p-4 text-center">
                                <div class="mb-1 text-xs font-semibold tracking-wider text-blue-400 uppercase">
                                    Support
                                </div>
                                <div class="font-medium text-white">24/7 SLA</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex h-full flex-col lg:mt-0">
                        <div class="mb-8">
                            <h1 class="mb-4 text-4xl font-bold tracking-tight text-white lg:text-5xl">
                                {{ $device->name }}
                            </h1>
                            <p class="border-l-2 border-blue-500/30 pl-4 text-lg leading-relaxed text-slate-400">
                                {{ $device->description }}
                            </p>
                        </div>

                        <div class="glass mt-auto rounded-2xl border-blue-500/10 p-6 lg:p-8">
                            <div class="mb-8 flex items-baseline justify-between border-b border-white/5 pb-6">
                                <div>
                                    <span class="text-sm font-semibold tracking-wide text-slate-400 uppercase">
                                        Monthly Subscription
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-3xl font-bold tracking-tight text-white">
                                        {{ $device->price_formatted }}
                                    </span>
                                    <span class="text-slate-500">/mo</span>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-300">
                                        Contract Duration
                                    </label>
                                    <div class="relative">
                                        <select
                                            id="show-rent-duration"
                                            class="block w-full rounded-xl border border-white/10 bg-[#0B0C10] p-4 text-sm text-white shadow-sm transition-colors hover:border-blue-500/50 focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="1">Monthly Rolling (+20%)</option>
                                            <option value="3">3 Months Quarter</option>
                                            <option value="6">6 Months</option>
                                            <option value="12" selected>12 Months Annual (Best Value)</option>
                                            <option value="24">24 Months Enterprise</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 pt-4">
                                    <a
                                        id="show-rent-now"
                                        href="#"
                                        data-base-href="/devices/{{ $device->slug }}"
                                        class="w-full items-center justify-center rounded-xl bg-white px-8 py-4 text-center text-base font-semibold text-black shadow-[0_0_20px_-5px_rgba(255,255,255,0.3)] transition-all hover:bg-slate-200 hover:shadow-[0_0_30px_-5px_rgba(255,255,255,0.4)]"
                                    >
                                        Proceed to Checkout
                                    </a>
                                    <div class="mt-2 flex items-center justify-center gap-2 text-xs text-slate-500">
                                        <svg class="h-3 w-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Includes AppleCare+ & Priority Support
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sel = document.getElementById('show-rent-duration');
                const btn = document.getElementById('show-rent-now');

                if (sel && btn) {
                    function update() {
                        const baseUrl = btn.getAttribute('data-base-href');
                        // Ensure we handle URL params correctly if base already has them
                        const separator = baseUrl.includes('?') ? '&' : '?';
                        btn.href = `${baseUrl}${separator}months=${sel.value}`;
                    }
                    sel.addEventListener('change', update);
                    update();
                }
            });
        </script>
    </body>
</html>
