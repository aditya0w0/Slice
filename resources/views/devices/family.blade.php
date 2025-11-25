<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $family }} - Slice Enterprise</title>

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
            .glass-panel {
                background: #121217;
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .aurora-bg {
                background:
                    radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.08), transparent 25%),
                    radial-gradient(circle at 85% 30%, rgba(16, 185, 129, 0.08), transparent 25%);
                background-color: #0b0c10;
            }
            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #0b0c10;
            }
            ::-webkit-scrollbar-thumb {
                background: #334155;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #475569;
            }

            /* Dark Mode Select Arrow */
            select {
                -webkit-appearance: none;
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.75rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
            }
        </style>
    </head>
    <body class="bg-[#0B0C10] text-slate-300 antialiased">
        <nav class="fixed start-0 top-0 z-50 w-full border-b border-white/5 bg-[#0B0C10]/80 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between p-4">
                <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-teal-500 shadow-lg shadow-blue-500/20"
                    >
                        <img src="{{ asset("images/logo.svg") }}" alt="Logo" class="h-12 w-12" />
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/devices" class="text-sm font-medium text-slate-400 transition-colors hover:text-white">
                        Back to Catalog
                    </a>
                </div>
            </div>
        </nav>

        <main class="aurora-bg min-h-screen pt-32 pb-32 lg:pb-20">
            <div class="mx-auto max-w-7xl px-6">
                @php
                    // Logic remains untouched
                    $first = null;
                    if (is_iterable($variants)) {
                        if (is_object($variants) && method_exists($variants, "first")) {
                            $first = $variants->first();
                        } elseif (is_array($variants) && count($variants)) {
                            $first = $variants[0];
                        }
                    }
                    $defaultImage = "/images/product-iphone.svg";
                    if (str_starts_with(mb_strtolower($family), "ipad")) {
                        $defaultImage = "/images/product-ipad.svg";
                    } elseif (mb_stripos($family, "mac") !== false) {
                        $defaultImage = "/images/product-mac.svg";
                    } elseif (mb_stripos($family, "watch") !== false) {
                        $defaultImage = "/images/product-watch.svg";
                    } elseif (mb_stripos($family, "homepod") !== false) {
                        $defaultImage = "/images/product-home-accessory.svg";
                    } elseif (mb_stripos($family, "airpods") !== false) {
                        $defaultImage = "/images/product-audio.svg";
                    } elseif (mb_stripos($family, "vision") !== false) {
                        $defaultImage = "/images/product-vision-pro.svg";
                    } elseif (mb_stripos($family, "accessory") !== false) {
                        $defaultImage = "/images/product-accessory.svg";
                    }

                    $firstImageCandidate = $first?->image ?? "";
                    if (! empty($firstImageCandidate) && file_exists(public_path(ltrim($firstImageCandidate, "/")))) {
                        $firstImage = $firstImageCandidate;
                    } else {
                        $firstImage = $defaultImage;
                    }
                    $firstName = $first?->name ?? $family;
                @endphp

                <nav class="mb-8 flex text-sm text-slate-500">
                    <ol class="inline-flex items-center space-x-2">
                        <li><a href="/" class="hover:text-blue-400">Home</a></li>
                        <li><span class="text-slate-600">/</span></li>
                        <li><a href="/devices" class="hover:text-blue-400">Catalog</a></li>
                        <li><span class="text-slate-600">/</span></li>
                        <li class="text-slate-300">{{ $family }}</li>
                    </ol>
                </nav>

                <div class="mt-8 grid gap-12 lg:grid-cols-12 lg:items-start">
                    <div class="order-2 lg:order-1 lg:col-span-7">
                        <div class="sticky top-24">
                            <div class="glass group relative overflow-hidden rounded-3xl p-8 lg:p-12">
                                <div
                                    class="absolute top-1/2 left-1/2 h-3/4 w-3/4 -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-500/10 blur-3xl transition-all duration-700 group-hover:bg-blue-500/20"
                                ></div>

                                <div class="relative z-10 mb-8 flex min-h-[400px] items-center justify-center">
                                    <img
                                        id="main-product-image"
                                        src="{{ $firstImage }}"
                                        alt="{{ $firstName }}"
                                        class="max-h-[500px] w-full object-contain drop-shadow-2xl transition-transform duration-500"
                                    />
                                </div>

                                <div class="relative z-20 flex justify-center gap-4 overflow-x-auto pb-2">
                                    @foreach ($variants as $variant)
                                        @php
                                            $imgCandidate = $variant->image ?? "";
                                            $imgPath = ! empty($imgCandidate) && file_exists(public_path(ltrim($imgCandidate, "/"))) ? $imgCandidate : $defaultImage;
                                        @endphp

                                        <button
                                            type="button"
                                            class="thumbnail shrink-0 rounded-xl border border-white/5 bg-[#1A1B23] p-3 transition-all hover:border-blue-500/50 active:scale-95"
                                            data-image="{{ $imgPath }}"
                                            data-slug="{{ $variant->slug }}"
                                        >
                                            <img
                                                src="{{ $imgPath }}"
                                                alt="{{ $variant->name }}"
                                                class="h-16 w-16 object-contain"
                                            />
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-3 gap-4">
                                <div
                                    class="glass rounded-xl border-t-2 border-t-transparent p-4 text-center transition-colors hover:border-t-blue-500"
                                >
                                    <div class="mb-1 text-xs font-bold tracking-wider text-blue-400 uppercase">
                                        Performance
                                    </div>
                                    <div class="text-sm text-slate-300">M3 / A17 Chip</div>
                                </div>
                                <div
                                    class="glass rounded-xl border-t-2 border-t-transparent p-4 text-center transition-colors hover:border-t-green-500"
                                >
                                    <div class="mb-1 text-xs font-bold tracking-wider text-green-400 uppercase">
                                        Condition
                                    </div>
                                    <div class="text-sm text-slate-300">Brand New</div>
                                </div>
                                <div
                                    class="glass rounded-xl border-t-2 border-t-transparent p-4 text-center transition-colors hover:border-t-purple-500"
                                >
                                    <div class="mb-1 text-xs font-bold tracking-wider text-purple-400 uppercase">
                                        Warranty
                                    </div>
                                    <div class="text-sm text-slate-300">AppleCare+</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="order-1 lg:order-2 lg:col-span-5">
                        <div class="mb-6">
                            <h1 class="text-4xl font-bold tracking-tight text-white">{{ $family }}</h1>
                            <p class="mt-3 leading-relaxed text-slate-400">
                                Select your configuration. All devices arrive fully provisioned and ready for enterprise
                                deployment.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div class="glass-panel rounded-2xl p-6 lg:p-8">
                                <div class="mb-8 flex items-end justify-between border-b border-white/5 pb-6">
                                    <div>
                                        <div class="mb-1 text-xs font-bold tracking-wider text-blue-400 uppercase">
                                            Monthly Subscription
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 animate-pulse rounded-full bg-green-500"></div>
                                            <span class="text-xs text-green-400">In Stock, Ships Today</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div id="price" class="text-3xl font-bold text-white">
                                            {{ $first->price_formatted ?? "" }}
                                        </div>
                                        <div class="text-xs text-slate-500">/device/month</div>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label
                                        class="mb-3 block text-xs font-semibold tracking-wider text-slate-400 uppercase"
                                    >
                                        @if ($hasColors)
                                            Color
                                        @else
                                            Model Variant
                                        @endif
                                    </label>
                                    <div id="model-buttons" class="flex flex-wrap gap-2">
                                        @foreach ($variants as $variant)
                                            <button
                                                type="button"
                                                class="variant-btn group relative inline-flex items-center rounded-lg border px-4 py-2.5 text-sm font-medium transition-all"
                                                data-slug="{{ $variant->slug }}"
                                                data-price="{{ $variant->price_formatted }}"
                                                data-sku="{{ $variant->sku ?? "—" }}"
                                                data-image="{{ $variant->image ?? $defaultImage }}"
                                                @if($loop->first) aria-pressed="true" @endif
                                            >
                                                {{ $variant->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($hasRAM && $ramOptions->isNotEmpty())
                                    <div class="mb-6">
                                        <label
                                            class="mb-3 block text-xs font-semibold tracking-wider text-slate-400 uppercase"
                                        >
                                            <svg
                                                class="mr-1 inline h-4 w-4 text-purple-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"
                                                />
                                            </svg>
                                            Memory (RAM) – Enterprise Performance
                                        </label>
                                        <div id="ram-buttons" class="grid grid-cols-4 gap-2">
                                            @foreach ($ramOptions as $ram)
                                                <button
                                                    type="button"
                                                    class="ram-btn group rounded-lg border border-white/10 bg-[#1A1B23] px-3 py-2 text-center text-sm font-medium text-slate-300 transition-all hover:border-purple-500/50 hover:bg-[#252630]"
                                                    data-ram="{{ $ram }}"
                                                    @if($loop->first) aria-pressed="true" @endif
                                                >
                                                    <div class="font-bold">{{ $ram }}</div>
                                                    <div class="text-xs text-slate-500 group-hover:text-purple-400">
                                                        {{ $loop->first ? "Base" : "Upgrade" }}
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($hasStorage && $storageOptions->isNotEmpty())
                                    <div class="mb-6">
                                        <label
                                            class="mb-3 block text-xs font-semibold tracking-wider text-slate-400 uppercase"
                                        >
                                            Storage
                                        </label>
                                        <div id="capacity-buttons" class="grid grid-cols-3 gap-2">
                                            @foreach ($storageOptions as $storage)
                                                <button
                                                    type="button"
                                                    class="capacity-btn rounded-lg border border-white/10 bg-[#1A1B23] px-3 py-2 text-center text-sm font-medium text-slate-300 transition-colors hover:border-white/30 hover:bg-[#252630]"
                                                    data-cap="{{ $storage }}"
                                                    @if($loop->first) aria-pressed="true" @endif
                                                >
                                                    {{ $storage }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-6">
                                    <label
                                        class="mb-3 block text-xs font-semibold tracking-wider text-slate-400 uppercase"
                                    >
                                        Contract Term
                                    </label>
                                    <div class="relative">
                                        <select
                                            id="duration-select"
                                            class="block w-full rounded-xl border border-white/10 bg-[#0B0C10] p-3 text-sm text-white shadow-sm transition-colors hover:border-blue-500/50 focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="1">Monthly Rolling (+20%)</option>
                                            <option value="3">3 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="12" selected>12 Months (Recommended)</option>
                                            <option value="24">24 Months</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-4 border-t border-white/5 pt-6">
                                    <div class="flex items-center rounded-lg border border-white/10 bg-[#0B0C10]">
                                        <button class="px-3 py-2 text-slate-400 hover:text-white" id="qty-decrease">
                                            −
                                        </button>
                                        <input
                                            id="qty"
                                            class="w-10 border-none bg-transparent p-0 text-center text-sm text-white focus:ring-0"
                                            value="1"
                                            readonly
                                        />
                                        <button class="px-3 py-2 text-slate-400 hover:text-white" id="qty-increase">
                                            +
                                        </button>
                                    </div>

                                    <button
                                        id="rent-now-btn"
                                        type="button"
                                        class="inline-flex flex-1 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition-all hover:scale-[1.02] hover:shadow-blue-500/40"
                                    >
                                        <span>Deploy Now</span>
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="glass rounded-xl p-6">
                                <h3 class="mb-2 text-sm font-bold text-white">Technical Specifications</h3>
                                <p class="text-sm leading-relaxed text-slate-400">
                                    {{ $first->description ?? "Enterprise-ready device configured for high-performance workflows." }}
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>

                <div
                    id="sticky-bar"
                    class="fixed right-0 bottom-0 left-0 z-40 border-t border-white/10 bg-[#0B0C10]/95 backdrop-blur-xl lg:hidden"
                >
                    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                        <div>
                            <div class="text-xs tracking-wider text-slate-500 uppercase">Total / Mo</div>
                            <div id="sticky-price" class="text-lg font-bold text-white">
                                {{ $first->price_formatted ?? "" }}
                            </div>
                        </div>
                        <button
                            id="sticky-rent-now-btn"
                            type="button"
                            class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-500/20"
                        >
                            Deploy
                        </button>
                    </div>
                </div>
            </div>

            <script>
                const variants = @json($variants);
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // --- Styling Constants ---
                    const activeClasses = [
                        'border-blue-500',
                        'bg-blue-500/20',
                        'text-white',
                        'shadow-[0_0_15px_-3px_rgba(59,130,246,0.3)]',
                    ];
                    const inactiveClasses = [
                        'border-white/10',
                        'bg-[#1A1B23]',
                        'text-slate-400',
                        'hover:border-white/30',
                    ];

                    // --- Helper: Toggle Classes ---
                    function setButtonState(btn, isActive) {
                        if (isActive) {
                            btn.classList.remove(...inactiveClasses);
                            btn.classList.add(...activeClasses);
                            btn.setAttribute('aria-pressed', 'true');
                        } else {
                            btn.classList.remove(...activeClasses);
                            btn.classList.add(...inactiveClasses);
                            btn.setAttribute('aria-pressed', 'false');
                        }
                    }

                    // --- Initialize Buttons ---
                    document.querySelectorAll('.variant-btn').forEach((btn) => {
                        // Check initial state based on HTML or reset all to inactive
                        if (btn.hasAttribute('aria-pressed') && btn.getAttribute('aria-pressed') === 'true') {
                            setButtonState(btn, true);
                        } else {
                            setButtonState(btn, false);
                        }

                        btn.addEventListener('click', function () {
                            // Reset all variants
                            document.querySelectorAll('.variant-btn').forEach((b) => setButtonState(b, false));
                            // Activate clicked
                            setButtonState(this, true);

                            // Data Update
                            const slug = this.getAttribute('data-slug');
                            const price = this.getAttribute('data-price');
                            const img = this.getAttribute('data-image');

                            // Update Globals
                            currentSlug = slug;

                            // DOM Updates
                            const mainImg = document.getElementById('main-product-image');
                            const priceEls = [
                                document.getElementById('price'),
                                document.getElementById('sticky-price'),
                            ];

                            if (mainImg && img) {
                                mainImg.style.opacity = '0';
                                setTimeout(() => {
                                    mainImg.src = img;
                                    mainImg.style.opacity = '1';
                                }, 200);
                            }

                            if (price) {
                                priceEls.forEach((el) => {
                                    if (el) el.textContent = price;
                                });
                            }
                        });
                    });

                    // --- Capacity Buttons ---
                    const capBtns = document.querySelectorAll('.capacity-btn');
                    if (capBtns.length > 0) setButtonState(capBtns[0], true); // Default first

                    capBtns.forEach((btn) => {
                        btn.addEventListener('click', function () {
                            capBtns.forEach((b) => setButtonState(b, false));
                            setButtonState(this, true);
                        });
                    });

                    // --- RAM Buttons (Mac Enterprise) ---
                    const ramBtns = document.querySelectorAll('.ram-btn');
                    if (ramBtns.length > 0) setButtonState(ramBtns[0], true); // Default first

                    ramBtns.forEach((btn) => {
                        btn.addEventListener('click', function () {
                            ramBtns.forEach((b) => setButtonState(b, false));
                            setButtonState(this, true);
                        });
                    });

                    // --- Quantity Logic ---
                    const qtyInput = document.getElementById('qty');
                    document.getElementById('qty-decrease')?.addEventListener('click', () => {
                        let val = parseInt(qtyInput.value || '1');
                        if (val > 1) qtyInput.value = val - 1;
                    });
                    document.getElementById('qty-increase')?.addEventListener('click', () => {
                        qtyInput.value = parseInt(qtyInput.value || '1') + 1;
                    });

                    // --- Rent Logic ---
                    var currentSlug = '{{ $first->slug ?? "" }}';
                    var isAuthenticated = {{ auth()->check() ? "true" : "false" }};

                    function rentNow() {
                        const selectedVariant = document.querySelector('.variant-btn[aria-pressed="true"]');
                        const variantSlug = selectedVariant ? selectedVariant.getAttribute('data-slug') : currentSlug;
                        const months = document.getElementById('duration-select')?.value || 12;
                        const quantity = document.getElementById('qty')?.value || 1;

                        // Find selected capacity (storage for iPhone/iPad)
                        let capacity = '256 GB'; // default
                        document.querySelectorAll('.capacity-btn').forEach((btn) => {
                            if (btn.classList.contains('border-blue-500')) {
                                capacity = btn.getAttribute('data-cap');
                            }
                        });

                        // Find selected RAM (for Mac devices)
                        let ram = '';
                        document.querySelectorAll('.ram-btn').forEach((btn) => {
                            if (btn.classList.contains('border-blue-500')) {
                                ram = btn.getAttribute('data-ram');
                            }
                        });

                        let url = `/checkout?device=${encodeURIComponent(variantSlug)}&months=${months}&quantity=${quantity}&capacity=${encodeURIComponent(capacity)}`;

                        // Add RAM parameter if selected
                        if (ram) {
                            url += `&ram=${encodeURIComponent(ram)}`;
                        }

                        if (!isAuthenticated) {
                            showLoginModal(url);
                        } else {
                            window.location.href = url;
                        }
                    }

                    function showLoginModal(returnUrl) {
                        const modal = document.createElement('div');
                        modal.className =
                            'fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm animate-fade-in';
                        modal.innerHTML = `
                        <div class="relative mx-4 w-full max-w-md rounded-2xl bg-[#121217] border border-white/10 p-8 shadow-2xl shadow-blue-900/20 transform scale-100 transition-all">
                            <button onclick="this.closest('.fixed').remove()" class="absolute right-4 top-4 text-slate-500 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <div class="text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-500/10 mb-6">
                                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">Enterprise Access Required</h3>
                                <p class="text-slate-400 mb-8">Please sign in to configure your fleet deployment.</p>
                                <div class="flex flex-col gap-3">
                                    <a href="/login?return_url=${encodeURIComponent(returnUrl)}" class="w-full rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-500 transition-all shadow-lg shadow-blue-600/20">
                                        Sign In
                                    </a>
                                    <a href="/register" class="w-full rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-300 hover:bg-white/5 transition-all">
                                        Create Account
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                        document.body.appendChild(modal);
                        modal.addEventListener('click', (e) => {
                            if (e.target === modal) modal.remove();
                        });
                    }

                    document.getElementById('rent-now-btn')?.addEventListener('click', rentNow);
                    document.getElementById('sticky-rent-now-btn')?.addEventListener('click', rentNow);
                });
            </script>
        </main>
    </body>
</html>
