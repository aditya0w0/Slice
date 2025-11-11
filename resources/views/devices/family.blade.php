<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $family }} models - Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body>
        @include("partials.header")

        <main class="mx-auto max-w-7xl px-6 pb-32 lg:pb-20">
            @php
                // pick a default selected variant (first)
                $first = null;
                if (is_iterable($variants)) {
                    if (is_object($variants) && method_exists($variants, "first")) {
                        $first = $variants->first();
                    } elseif (is_array($variants) && count($variants)) {
                        $first = $variants[0];
                    }
                }
                // choose a sensible default image based on the family (ipad/mac/watch/iphone)
                $defaultImage = "/images/product-iphone.svg";
                if (str_starts_with(mb_strtolower($family), "ipad")) {
                    $defaultImage = "/images/product-ipad.svg";
                } elseif (mb_stripos($family, "mac") !== false) {
                    $defaultImage = "/images/product-mac.svg";
                } elseif (mb_stripos($family, "watch") !== false || mb_stripos($family, "accessory") !== false) {
                    $defaultImage = "/images/product-watch.svg";
                }

                // resolve first image and fallback to family default if file missing
                $firstImageCandidate = $first?->image ?? "";
                if (! empty($firstImageCandidate) && file_exists(public_path(ltrim($firstImageCandidate, "/")))) {
                    $firstImage = $firstImageCandidate;
                } else {
                    $firstImage = $defaultImage;
                }
                $firstName = $first?->name ?? $family;
                $firstPrice = $first?->price_formatted ?? "";
                $firstSku = $first?->sku ?? "—";
                $firstDescription = $first?->description ?? "";
                $firstSlug = $first?->slug ?? "";
            @endphp

            <div class="mt-8 grid gap-8 lg:grid-cols-12 lg:items-start">
                <!-- Left: large gallery -->
                <div class="order-2 lg:order-1 lg:col-span-7">
                    <div class="rounded-xl border border-gray-100 bg-white p-6">
                        <div class="mb-6 flex min-h-[220px] items-center">
                            <img
                                id="main-product-image"
                                src="{{ $firstImage }}"
                                alt="{{ $firstName }}"
                                class="mx-auto max-h-[720px] w-full object-contain"
                            />
                        </div>
                        <div class="mt-4 grid grid-cols-4 gap-4 lg:grid-cols-5">
                            @foreach ($variants as $variant)
                                @php
                                    $imgCandidate = $variant->image ?? "";
                                    if (! empty($imgCandidate) && file_exists(public_path(ltrim($imgCandidate, "/")))) {
                                        $imgPath = $imgCandidate;
                                    } else {
                                        $imgPath = $defaultImage;
                                    }
                                @endphp

                                <button
                                    type="button"
                                    class="thumbnail shrink-0 rounded-lg border border-gray-200 bg-white p-2"
                                    data-image="{{ $imgPath }}"
                                    data-slug="{{ $variant->slug }}"
                                >
                                    <img
                                        src="{{ $imgPath }}"
                                        alt="{{ $variant->name }}"
                                        class="h-24 w-24 object-contain"
                                    />
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: product details & controls -->
                <aside class="order-1 lg:order-2 lg:col-span-5">
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $family }}</h1>
                    <p class="mt-2 text-sm text-gray-600">Choose a variant to see more details and rent options.</p>

                    <div class="mt-6 space-y-6">
                        <div class="sticky top-20 rounded-xl border border-gray-100 bg-white p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm text-gray-500">Price</div>
                                    <div id="price" class="text-2xl font-bold text-gray-900">
                                        {{ $first->price_formatted ?? "" }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="text-xs text-gray-500">Model</label>
                                <div id="model-buttons" class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($variants as $variant)
                                        <button
                                            type="button"
                                            class="variant-btn inline-flex items-center rounded-full border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:bg-blue-50 transition-colors px-4 py-2 text-sm font-medium whitespace-nowrap"
                                            data-slug="{{ $variant->slug }}"
                                            data-price="{{ $variant->price_formatted }}"
                                            data-sku="{{ $variant->sku ?? '—' }}"
                                            data-image="{{ $variant->image ?? "/images/product-iphone.svg" }}"
                                            @if($loop->first) aria-pressed="true" @endif
                                        >
                                            {{ $variant->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="text-xs text-gray-500">Capacity</label>
                                <div id="capacity-buttons" class="mt-2 grid grid-cols-3 gap-2">
                                    <button
                                        type="button"
                                        class="capacity-btn rounded-md border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:bg-blue-50 transition-colors px-3 py-2 text-sm font-medium"
                                        data-cap="256 GB"
                                    >
                                        256 GB
                                    </button>
                                    <button
                                        type="button"
                                        class="capacity-btn rounded-md border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:bg-blue-50 transition-colors px-3 py-2 text-sm font-medium"
                                        data-cap="512 GB"
                                    >
                                        512 GB
                                    </button>
                                    <button
                                        type="button"
                                        class="capacity-btn rounded-md border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:bg-blue-50 transition-colors px-3 py-2 text-sm font-medium"
                                        data-cap="1 TB"
                                    >
                                        1 TB
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="text-xs text-gray-500">Duration</label>
                                <select id="duration-select" class="mt-2 w-full rounded-md border px-3 py-2 text-sm">
                                    <option value="1">1 month</option>
                                    <option value="3">3 months</option>
                                    <option value="6">6 months</option>
                                    <option value="12" selected>12 months</option>
                                    <option value="24">24 months</option>
                                </select>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <label class="text-sm text-gray-700">Quantity</label>
                                    <div class="inline-flex items-center rounded-md border bg-white">
                                        <button class="px-3 py-1 text-gray-600" id="qty-decrease">−</button>
                                        <input id="qty" class="w-12 text-center text-sm" value="1" />
                                        <button class="px-3 py-1 text-gray-600" id="qty-increase">+</button>
                                    </div>
                                </div>

                                <button
                                    id="rent-now-btn"
                                    type="button"
                                    class="inline-flex items-center rounded-full bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                                >
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span>Rent Now</span>
                                </button>
                            </div>
                        </div>

                        <div class="prose max-w-none rounded-xl border border-gray-100 bg-white p-6">
                            <h3 class="text-base font-semibold">Details</h3>
                            <p class="text-sm text-gray-700">{{ $first->description ?? "" }}</p>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- expose variants to client JS (tests expect `const variants =`) -->
            <script>
                const variants = @json($variants);
            </script>

            <!-- Sticky bottom bar -->
            <div id="sticky-bar" class="fixed right-0 bottom-0 left-0 z-40 border-t bg-white lg:hidden">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                    <div>
                        <div class="text-sm text-gray-500">Total</div>
                        <div id="sticky-price" class="text-lg font-bold text-gray-900">
                            {{ $first->price_formatted ?? "" }}
                        </div>
                    </div>
                    <button
                        id="sticky-rent-now-btn"
                        type="button"
                        class="rounded-full bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                    >
                        Rent Now
                    </button>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Variant button clicks
                document.querySelectorAll('.variant-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        // Remove active state from all variant buttons
                        document.querySelectorAll('.variant-btn').forEach(function (b) {
                            b.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-700');
                            b.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
                            b.setAttribute('aria-pressed', 'false');
                        });
                        
                        // Add active state to clicked button
                        this.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                        this.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
                        this.setAttribute('aria-pressed', 'true');
                        
                        var slug = this.getAttribute('data-slug');
                        var price = this.getAttribute('data-price');
                        var img = this.getAttribute('data-image');
                        var sku = this.getAttribute('data-sku');
                        
                        // Update current slug for cart
                        currentSlug = slug;
                        
                        // Update main image
                        if (img) {
                            var main = document.getElementById('main-product-image');
                            if (main) main.src = img;
                        }
                        
                        // Update price
                        if (price) {
                            var priceEl = document.getElementById('price');
                            var stickyPrice = document.getElementById('sticky-price');
                            if (priceEl) priceEl.textContent = price;
                            if (stickyPrice) stickyPrice.textContent = price;
                        }
                        
                        // Update SKU
                        if (sku) {
                            var skuEl = document.querySelector('[data-sku-display]');
                            if (skuEl) skuEl.textContent = sku;
                        }
                        
                        // Update rent links
                        var months = document.getElementById('duration-select')?.value || '12';
                        var base = '/devices/' + slug;
                        var rent = document.getElementById('rent-btn');
                        var sticky = document.getElementById('sticky-btn');
                        if (rent) {
                            rent.href = base + '?months=' + months;
                            rent.setAttribute('data-base', base);
                        }
                        if (sticky) {
                            sticky.href = base + '?months=' + months;
                            sticky.setAttribute('data-base', base);
                        }
                    });
                });

                // Set initial active state for first variant button
                var firstVariant = document.querySelector('.variant-btn[aria-pressed="true"]');
                if (firstVariant) {
                    firstVariant.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
                    firstVariant.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                } else {
                    var anyFirst = document.querySelector('.variant-btn');
                    if (anyFirst) {
                        anyFirst.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
                        anyFirst.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                    }
                }

                // Capacity button clicks
                document.querySelectorAll('.capacity-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        // Remove active state from all capacity buttons
                        document.querySelectorAll('.capacity-btn').forEach(function (b) {
                            b.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-700');
                            b.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
                        });
                        
                        // Add active state to clicked button
                        this.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                        this.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
                    });
                });

                // Set first capacity button as active by default
                var firstCapacity = document.querySelector('.capacity-btn');
                if (firstCapacity) {
                    firstCapacity.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-700');
                    firstCapacity.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
                }

                // thumbnail clicks update main image and selected model
                document.querySelectorAll('.thumbnail').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var img = this.getAttribute('data-image');
                        var slug = this.getAttribute('data-slug');
                        var main = document.getElementById('main-product-image');
                        if (main && img) main.src = img;
                        
                        // Find and activate the corresponding variant button
                        document.querySelectorAll('.variant-btn').forEach(function (vBtn) {
                            if (vBtn.getAttribute('data-slug') === slug) {
                                vBtn.click();
                            }
                        });
                    });
                });

                // duration changes
                var duration = document.getElementById('duration-select');

                // qty controls
                var qty = document.getElementById('qty');
                document.getElementById('qty-decrease')?.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (qty) qty.value = Math.max(1, parseInt(qty.value || '1') - 1);
                });
                document.getElementById('qty-increase')?.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (qty) qty.value = parseInt(qty.value || '1') + 1;
                });

                // Rent Now functionality
                var currentSlug = '{{ $first->slug ?? "" }}';
                var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
                
                function rentNow() {
                    var selectedVariant = document.querySelector('.variant-btn[aria-pressed="true"]');
                    var variantSlug = selectedVariant ? selectedVariant.getAttribute('data-slug') : currentSlug;
                    var months = document.getElementById('duration-select')?.value || 12;
                    var quantity = parseInt(document.getElementById('qty')?.value || 1);
                    var selectedCapacity = document.querySelector('.capacity-btn.border-blue-600');
                    var capacity = selectedCapacity ? selectedCapacity.getAttribute('data-cap') : '';
                    
                    // Build checkout page URL with parameters
                    var url = '/checkout?device=' + encodeURIComponent(variantSlug) + 
                              '&months=' + months + 
                              '&quantity=' + quantity;
                    
                    if (capacity) {
                        url += '&capacity=' + encodeURIComponent(capacity);
                    }
                    
                    if (!isAuthenticated) {
                        // Show login modal
                        showLoginModal(url);
                    } else {
                        // Redirect to checkout page
                        window.location.href = url;
                    }
                }
                
                function showLoginModal(returnUrl) {
                    // Create modal overlay
                    var modal = document.createElement('div');
                    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/50';
                    modal.innerHTML = `
                        <div class="relative mx-4 max-w-md rounded-3xl bg-white p-8 shadow-2xl">
                            <button onclick="this.closest('.fixed').remove()" class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <div class="text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-xl font-bold text-gray-900">Please Login to Continue</h3>
                                <p class="mt-2 text-gray-600">You need to be logged in to rent devices</p>
                                <div class="mt-6 flex gap-3">
                                    <a href="/login?return_url=${encodeURIComponent(returnUrl)}" class="flex-1 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 font-semibold text-white hover:shadow-lg transition-all">
                                        Login
                                    </a>
                                    <a href="/register" class="flex-1 rounded-xl border border-gray-300 px-6 py-3 font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                                        Register
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                    
                    // Close on overlay click
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            modal.remove();
                        }
                    });
                }
                
                // Attach click handlers
                document.getElementById('rent-now-btn')?.addEventListener('click', rentNow);
                document.getElementById('sticky-rent-now-btn')?.addEventListener('click', rentNow);
            });
        </script>
    </body>
</html>
