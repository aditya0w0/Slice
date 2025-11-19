<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Rent Devices - Catalog</title>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        />

        @vite("resources/css/app.css")
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased">
        @include("partials.header")

        <!-- Main Content langsung, gak pake Hero Banner -->
        <main class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
            <!-- Header Simpel + Filter -->
            <div
                class="mb-8 flex flex-col items-center justify-between gap-4 border-b border-slate-200 pb-6 sm:flex-row"
            >
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Select Device</h1>
                    <p class="mt-1 text-sm text-slate-500">Choose your gear and start renting.</p>
                </div>

                <!-- Search -->
                <div class="mt-3 w-full sm:mt-0 sm:ml-4 sm:w-64">
                    <label for="device-search" class="sr-only">Search devices</label>
                    <input
                        id="device-search"
                        type="search"
                        placeholder="Search devices or models..."
                        class="w-full rounded-full border px-4 py-2 text-sm"
                        autocomplete="off"
                    />
                </div>

                <!-- Filter Buttons -->
                <div class="flex max-w-full items-center space-x-2 overflow-x-auto pb-2 sm:pb-0">
                    <a
                        href="{{ route("devices") }}"
                        class="{{ empty($activeCategory) ? "bg-slate-900 text-white" : "border border-slate-200 bg-white text-slate-600" }} rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap shadow-sm transition"
                    >
                        All Devices
                    </a>
                    @foreach ($categories ?? [] as $cat)
                        @php
                            $isActive = $activeCategory === $cat;
                            $label = $cat;
                        @endphp

                        <a
                            href="{{ route("devices", ["category" => $cat]) }}"
                            class="{{ $isActive ? "bg-slate-900 text-white" : "border border-slate-200 bg-white text-slate-600" }} rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap transition"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grid System -->
            <div id="device-grid" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                @foreach ($baseModels as $base)
                    @php
                        $nameLower = mb_strtolower($base["name"] ?? "");
                        $defaultImage = "/images/product-iphone.svg";
                        $accentColor = "bg-blue-50 text-blue-700 ring-blue-700/10";

                        if (str_starts_with($nameLower, "ipad")) {
                            $defaultImage = "/images/product-ipad.svg";
                            $accentColor = "bg-indigo-50 text-indigo-700 ring-indigo-700/10";
                        } elseif (mb_stripos($base["name"] ?? "", "mac") !== false) {
                            $defaultImage = "/images/product-mac.svg";
                            $accentColor = "bg-slate-50 text-slate-700 ring-slate-700/10";
                        } elseif (mb_stripos($base["name"] ?? "", "watch") !== false) {
                            $defaultImage = "/images/product-watch.svg";
                            $accentColor = "bg-rose-50 text-rose-700 ring-rose-700/10";
                        }

                        $imagePath = $base["image"] ?? $defaultImage;
                    @endphp

                    <!-- Modern Card (Compact) -->
                    <div
                        class="group relative flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition-all hover:-translate-y-1 hover:shadow-lg"
                    >
                        <!-- Image Area -->
                        <div class="relative aspect-4/3 overflow-hidden bg-slate-50">
                            <img
                                src="{{ $imagePath }}"
                                alt="{{ $base["name"] }}"
                                class="absolute inset-0 h-full w-full object-contain object-center p-6 transition-transform duration-300 group-hover:scale-105"
                            />
                            <!-- Stock Indicator (Lebih kecil) -->
                            <div class="absolute top-3 right-3">
                                <span
                                    class="{{ $accentColor }} inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold tracking-wide uppercase ring-1 ring-inset"
                                >
                                    Ready
                                </span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="text-base leading-tight font-bold text-slate-900">
                                @php
                                    $isDeviceCard = $base["is_device"] ?? false;
                                @endphp

                                <a
                                    href="{{ $isDeviceCard ? route("devices.show", ["slug" => $base["slug"]]) : route("devices.model", ["family" => $base["family_slug"]]) }}"
                                >
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $base["name"] }}
                                </a>
                            </h3>

                            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                <div>
                                    <p class="text-xs text-slate-500">Starts from</p>
                                    <p class="text-sm font-bold text-slate-900">
                                        Rp 150K
                                        <span class="font-normal text-slate-500">/day</span>
                                    </p>
                                </div>
                                <!-- Small Icon Button CTA -->
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-colors group-hover:bg-blue-600 group-hover:text-white"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2.5"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <script>
                (function () {
                    let timer = null;
                    const searchInput = document.getElementById('device-search');
                    const grid = document.getElementById('device-grid');
                    const activeCategory = @json($activeCategory ?? null);

                    function formatPrice(n) {
                        if (n === null || n === undefined) return '';
                        // assume monthly ints, show as currency - adjust as needed
                        return 'Rp ' + Number(n).toLocaleString('id-ID') + '/mo';
                    }

                    function renderCards(items) {
                        if (!grid) return;
                        if (!items || items.length === 0) {
                            grid.innerHTML =
                                '<div class="col-span-3 text-center text-slate-500">No devices found.</div>';
                            return;
                        }

                        const html = items
                            .map((item) => {
                                const image = item.image || '/images/product-iphone.svg';
                                const url = '/devices/' + item.slug;
                                return `
                            <div class="group relative flex flex-col overflow-hidden rounded-xl bg-white border border-slate-200 transition-all hover:shadow-lg hover:-translate-y-1">
                                <div class="aspect-4/3 bg-slate-50 relative overflow-hidden">
                                    <img src="${image}" alt="${item.name}" class="absolute inset-0 h-full w-full object-contain object-center p-6" />
                                </div>
                                <div class="flex flex-1 flex-col p-5">
                                    <h3 class="text-base font-bold text-slate-900 leading-tight">
                                        <a href="${url}">${item.name}</a>
                                    </h3>
                                    <div class="mt-4 flex items-center justify-between pt-4 border-t border-slate-100">
                                        <div>
                                            <p class="text-xs text-slate-500">Starts from</p>
                                            <p class="text-sm font-bold text-slate-900">${formatPrice(item.price_monthly)}</p>
                                        </div>
                                        <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                            })
                            .join('\n');

                        grid.innerHTML = html;
                    }

                    function doSearch(q) {
                        const params = new URLSearchParams();
                        if (q) params.set('q', q);
                        if (activeCategory) params.set('category', activeCategory);
                        fetch('/api/devices?' + params.toString())
                            .then((r) => r.json())
                            .then((json) => {
                                renderCards(json.data || []);
                            })
                            .catch(() => {
                                // ignore errors for now
                            });
                    }

                    if (searchInput) {
                        searchInput.addEventListener('input', (e) => {
                            clearTimeout(timer);
                            const q = e.target.value.trim();
                            timer = setTimeout(() => doSearch(q), 350);
                        });
                    }
                })();
            </script>
        </main>
    </body>
</html>
