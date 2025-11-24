<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Catalog - Slice Enterprise</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite("resources/css/app.css")

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .hide-scrollbar::-webkit-scrollbar { display: none; }
            .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
            .glass-sticky { background: rgba(11, 12, 16, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
            .aurora-bg {
                background:
                    radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.08), transparent 25%),
                    radial-gradient(circle at 85% 30%, rgba(16, 185, 129, 0.08), transparent 25%);
                background-color: #0B0C10;
            }
        </style>
    </head>
    <body class="bg-[#0B0C10] text-slate-300 antialiased selection:bg-blue-500/30 selection:text-blue-100">
        @include("partials.header")

        <main class="aurora-bg min-h-screen pb-20">
            {{-- Minimal Filter / Category Nav (Sticky) --}}
            <div class="sticky top-0 z-40 glass-sticky">
                <div class="hide-scrollbar mx-auto flex max-w-7xl items-center justify-center overflow-x-auto px-6 py-4">
                    <div class="flex items-center space-x-2">
                        <a
                            href="{{ route("devices") }}"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ empty($activeCategory) ? "bg-white text-black shadow-lg shadow-white/10" : "text-slate-400 hover:text-white hover:bg-white/5" }}"
                        >
                            All Models
                        </a>
                        @foreach ($categories ?? [] as $cat)
                            @php
                                $isActive = $activeCategory === $cat;
                            @endphp

                            <a
                                href="{{ route("devices", ["category" => $cat]) }}"
                                class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all duration-300 {{ $isActive ? "bg-white text-black shadow-lg shadow-white/10" : "text-slate-400 hover:text-white hover:bg-white/5" }}"
                            >
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6 lg:px-8">
                <div id="device-grid" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-8">
                    @foreach ($baseModels as $base)
                        @php
                            $nameLower = mb_strtolower($base["name"] ?? "");
                            $defaultImage = "/images/product-iphone.svg";

                            // Image resolution logic
                            if (str_starts_with($nameLower, "ipad")) {
                                $defaultImage = "/images/product-ipad.svg";
                            } elseif (mb_stripos($base["name"] ?? "", "mac") !== false) {
                                $defaultImage = "/images/product-macbook.svg";
                            } elseif (mb_stripos($base["name"] ?? "", "watch") !== false) {
                                $defaultImage = "/images/product-applewatch.svg";
                            } elseif (mb_stripos($base["name"] ?? "", "tv") !== false) {
                                $defaultImage = "/images/product-appletv.svg";
                            } elseif (mb_stripos($base["name"] ?? "", "homepod") !== false) {
                                $defaultImage = "/images/product-homepod.svg";
                            }

                            $imagePath = $base["image"] ?? $defaultImage;
                            $isDeviceCard = $base["is_device"] ?? false;
                            $route = $isDeviceCard ? route("devices.show", ["slug" => $base["slug"]]) : route("devices.model", ["family" => $base["family_slug"]]);
                        @endphp

                        <a
                            href="{{ $route }}"
                            class="group relative flex flex-col items-center rounded-3xl bg-[#121217] border border-white/5 p-8 transition-all duration-500 hover:border-blue-500/30 hover:shadow-[0_0_30px_-5px_rgba(59,130,246,0.15)] overflow-hidden"
                        >
                            <div class="absolute inset-0 bg-gradient-to-b from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            <div class="relative z-10 mb-8 flex flex-col items-center text-center w-full">
                                <h2 class="text-2xl font-bold text-white tracking-tight group-hover:text-blue-100 transition-colors">{{ $base["name"] }}</h2>

                                @if (isset($base["price_monthly"]) && $base["price_monthly"] > 0)
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-blue-500/20 bg-blue-500/10 backdrop-blur-sm">
                                        <span class="text-xs font-semibold text-blue-400">From</span>
                                        <span class="text-sm font-bold text-white">${{ number_format($base["price_monthly"], 0) }}<span class="text-blue-400/70 text-xs font-normal">/mo</span></span>
                                    </div>
                                @else
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-white/10 bg-white/5">
                                        <span class="text-sm font-medium text-slate-400">Configure</span>
                                    </div>
                                @endif
                            </div>

                            <div class="relative z-10 aspect-square w-full max-w-[280px] flex items-center justify-center">
                                <div class="absolute inset-0 bg-blue-500/10 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700 scale-75"></div>

                                <img
                                    src="{{ $imagePath }}"
                                    alt="{{ $base["name"] }}"
                                    class="h-full w-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-105 group-hover:-translate-y-2"
                                />
                            </div>

                            <div class="relative z-10 mt-8 w-full flex items-center justify-between border-t border-white/5 pt-4 opacity-60 group-hover:opacity-100 transition-all duration-300">
                                <div class="flex items-center gap-2 text-xs font-medium text-slate-500 group-hover:text-blue-300">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    In Stock
                                </div>
                                <span class="text-sm font-semibold text-white group-hover:text-blue-400 flex items-center transition-colors">
                                    Deploy
                                    <svg class="ml-1 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </main>
    </body>
</html>
