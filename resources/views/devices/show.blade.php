<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $device->name }} - Slice Enterprise</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite("resources/css/app.css")

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .aurora-bg {
            background:
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(16, 185, 129, 0.08), transparent 25%);
            background-color: #0B0C10;
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

    <nav class="fixed w-full z-50 top-0 start-0 border-b border-white/5 bg-[#0B0C10]/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-teal-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="self-center text-xl font-bold whitespace-nowrap text-white tracking-tight">Slice<span class="text-blue-500">.</span></span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="/devices" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Back to Catalog</a>
            </div>
        </div>
    </nav>

    <main class="aurora-bg min-h-screen pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <nav class="flex mb-8 text-sm text-slate-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li><a href="/" class="hover:text-blue-400">Home</a></li>
                    <li><span class="text-slate-600">/</span></li>
                    <li><a href="/devices" class="hover:text-blue-400">Catalog</a></li>
                    <li><span class="text-slate-600">/</span></li>
                    <li class="text-slate-300">{{ $device->name }}</li>
                </ol>
            </nav>

            <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-start">

                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-teal-600 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-1000 group-hover:duration-200"></div>

                    <div class="relative rounded-3xl glass aspect-[4/3] flex items-center justify-center p-8 lg:p-12 overflow-hidden">
                        <img
                            src="{{ $device->image ?? '/images/product-iphone.svg' }}"
                            alt="{{ $device->name }}"
                            class="w-full h-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-105"
                        />
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="glass rounded-xl p-4 text-center">
                            <div class="text-blue-400 text-xs font-semibold uppercase tracking-wider mb-1">Stock</div>
                            <div class="text-white font-medium">Available</div>
                        </div>
                        <div class="glass rounded-xl p-4 text-center">
                            <div class="text-blue-400 text-xs font-semibold uppercase tracking-wider mb-1">Delivery</div>
                            <div class="text-white font-medium">24 Hours</div>
                        </div>
                        <div class="glass rounded-xl p-4 text-center">
                            <div class="text-blue-400 text-xs font-semibold uppercase tracking-wider mb-1">Support</div>
                            <div class="text-white font-medium">24/7 SLA</div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 lg:mt-0 flex flex-col h-full">

                    <div class="mb-8">
                        <h1 class="text-4xl lg:text-5xl font-bold text-white tracking-tight mb-4">{{ $device->name }}</h1>
                        <p class="text-lg text-slate-400 leading-relaxed border-l-2 border-blue-500/30 pl-4">
                            {{ $device->description }}
                        </p>
                    </div>

                    <div class="glass rounded-2xl p-6 lg:p-8 mt-auto border-blue-500/10">
                        <div class="flex items-baseline justify-between mb-8 border-b border-white/5 pb-6">
                            <div>
                                <span class="text-sm text-slate-400 uppercase tracking-wide font-semibold">Monthly Subscription</span>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-white tracking-tight">{{ $device->price_formatted }}</span>
                                <span class="text-slate-500">/mo</span>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Contract Duration</label>
                                <div class="relative">
                                    <select id="show-rent-duration" class="w-full bg-[#0B0C10] border border-white/10 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-4 shadow-sm hover:border-blue-500/50 transition-colors">
                                        <option value="1">Monthly Rolling (+20%)</option>
                                        <option value="3">3 Months Quarter</option>
                                        <option value="6">6 Months</option>
                                        <option value="12" selected>12 Months Annual (Best Value)</option>
                                        <option value="24">24 Months Enterprise</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-4 flex flex-col gap-3">
                                <a
                                    id="show-rent-now"
                                    href="#"
                                    data-base-href="/devices/{{ $device->slug }}"
                                    class="w-full justify-center text-center items-center py-4 px-8 text-base font-semibold text-black rounded-xl bg-white hover:bg-slate-200 transition-all shadow-[0_0_20px_-5px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_-5px_rgba(255,255,255,0.4)]"
                                >
                                    Proceed to Checkout
                                </a>
                                <div class="flex items-center justify-center gap-2 text-xs text-slate-500 mt-2">
                                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
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
