<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $base }} — Models</title>
        @vite("resources/css/app.css")
    </head>
    <body>
        <header class="mx-auto max-w-7xl px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="text-2xl font-extrabold text-gray-900">SLICE</div>
                    <!-- cart icon with count -->
                        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center" id="cart-link">
                        <svg id="cart-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg>
                        <span id="cart-count" class="absolute -top-2 -right-3 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs text-white">{{ $cartCount ?? 0 }}</span>
                    </a>
                </div>
                <nav class="flex gap-4">
                    <a href="/" class="text-sm text-gray-600 hover:text-gray-900">Home</a>
                    <a href="/devices" class="text-sm font-semibold text-gray-900">Devices</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 pb-20">
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- Left: Gallery -->
                <div class="lg:col-span-7">
                    <div class="rounded-lg bg-white p-6">
                        @php
                            // variants may be an array or a collection; resolve first image safely
                            $firstImage = "/images/product-iphone.svg";
                            if (is_array($variants) && count($variants)) {
                                $firstImage = $variants[0]->image ?? $firstImage;
                            } elseif (is_object($variants) && method_exists($variants, "first")) {
                                $first = $variants->first();
                                if ($first) {
                                    $firstImage = $first->image ?? $firstImage;
                                }
                            }
                        @endphp

                        <img
                            id="main-image"
                            src="{{ $firstImage }}"
                            alt="{{ $base }}"
                            class="h-[520px] w-full object-contain"
                        />
                        <div class="mt-6 flex gap-4">
                            @foreach ($variants as $v)
                                <button class="thumb rounded border bg-white p-2" data-image="{{ $v->image }}">
                                    <img
                                        src="{{ $v->image }}"
                                        alt="{{ $v->name }}"
                                        class="h-20 w-20 object-contain"
                                    />
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                        <!-- Inline login modal (AJAX) -->
                        <div id="inline-login-modal" class="fixed inset-0 z-60 hidden items-center justify-center p-6">
                            <div class="absolute inset-0 bg-black/50"></div>
                            <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold">Sign in</h3>
                                    <button id="inline-login-close" class="text-gray-500">✕</button>
                                </div>
                                <form id="inline-login-form" class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input name="email" type="email" required class="mt-1 block w-full rounded-md border px-3 py-2" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Password</label>
                                        <input name="password" type="password" required class="mt-1 block w-full rounded-md border px-3 py-2" />
                                    </div>
                                    <div class="flex items-center justify-end gap-3">
                                        <button type="button" id="inline-login-cancel" class="rounded-md border px-4 py-2">Cancel</button>
                                        <button type="submit" id="inline-login-submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Sign in</button>
                                    </div>
                                    <div id="inline-login-error" class="text-sm text-red-600 mt-2 hidden"></div>
                                </form>
                            </div>
                        </div>

            <!-- Right: Options and purchase area -->
            <aside class="lg:col-span-5">
                    <div class="relative rounded-lg bg-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-extrabold text-gray-900">{{ $base }}</h1>
                                <div class="mt-1 text-sm text-gray-500">
                                    Choose variant, capacity and rental duration
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
<label class="block text-sm font-medium text-gray-700">Model</label>
                            <div id="variant-buttons" class="mt-2 flex items-center gap-2 overflow-x-auto">
                                @foreach ($variants as $v)
                                    <button type="button" class="variant-btn inline-flex items-center rounded-full border px-4 py-2 text-sm font-medium whitespace-nowrap" data-slug="{{ $v->slug }}" data-price="{{ $v->price_monthly }}" data-image="{{ $v->image }}">
                                        {{ $v->variant }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Capacity</label>
                            <div class="mt-2 space-y-2">
                                <button class="capacity-btn w-full rounded-md border px-3 py-2 text-left">
                                    256 GB
                                </button>
                                <button class="capacity-btn w-full rounded-md border px-3 py-2 text-left">
                                    512 GB
                                </button>
                                <button class="capacity-btn w-full rounded-md border px-3 py-2 text-left">1 TB</button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Duration (months)</label>
                            <select id="months" class="mt-2 block w-40 rounded-md border px-3 py-2">
                                <option value="1">1</option>
                                <option value="3">3</option>
                                <option value="6">6</option>
                                <option value="12" selected>12</option>
                            </select>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-500">Monthly</div>
                                <div id="price-monthly" class="text-xl font-semibold text-gray-900">Rp 0</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Total</div>
                                <div id="price-total" class="text-xl font-semibold text-gray-900">Rp 0</div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-3">
                            <!-- Hidden rent form for authenticated users (placed in DOM but not wrapping the button) -->
                            @auth
                                <form id="rent-form" method="POST" action="{{ url('/rent') }}" class="hidden">
                                    @csrf
                                    <input type="hidden" name="variant_slug" id="rent-variant" value="{{ $variants[0]->slug ?? '' }}">
                                    <input type="hidden" name="months" id="rent-months" value="12">
                                    <input type="hidden" name="capacity" id="rent-capacity" value="256 GB">
                                </form>
                            @endauth

                            <!-- Rent button visible to all users. Guests will see the modal prompting sign-in. -->
                            <button
                                type="button"
                                id="add-to-cart"
                                class="ml-auto inline-flex items-center rounded-full bg-blue-600 px-6 py-3 text-sm font-medium text-white"
                            >
                                Tambah keranjang
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        <!-- Rent confirmation modal -->
        <div id="rent-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-6">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative z-10 w-full max-w-2xl rounded-lg bg-white p-6">
                <div class="flex items-start justify-between">
                    <h2 class="text-lg font-semibold">Confirm rental</h2>
                    <button id="rent-modal-close" class="text-gray-500">✕</button>
                </div>
                <div class="mt-4" id="rent-modal-body">
                    @auth
                        <p class="text-sm text-gray-700">You're about to rent <strong id="modal-variant-name"></strong>.</p>
                        <div class="mt-2 space-y-1">
                            <div><strong>Duration:</strong> <span id="modal-months"></span> months</div>
                            <div><strong>Capacity:</strong> <span id="modal-capacity"></span></div>
                            <div><strong>Monthly:</strong> <span id="modal-price-monthly"></span></div>
                            <div><strong>Total:</strong> <span id="modal-price-total"></span></div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button id="rent-cancel" class="rounded-md border px-4 py-2">Cancel</button>
                            <button id="rent-confirm" class="rounded-md bg-blue-600 px-4 py-2 text-white">Confirm & Rent</button>
                        </div>
                    @endauth

                    @guest
                        <p class="text-sm text-gray-700">You're adding this item to your cart as a guest. You'll be asked to sign in at checkout to complete the order.</p>
                        <div class="mt-2 space-y-1">
                            <div><strong>Duration:</strong> <span id="modal-months"></span> months</div>
                            <div><strong>Capacity:</strong> <span id="modal-capacity"></span></div>
                            <div><strong>Monthly:</strong> <span id="modal-price-monthly"></span></div>
                            <div><strong>Total:</strong> <span id="modal-price-total"></span></div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button id="rent-modal-close-guest" class="rounded-md border px-4 py-2">Cancel</button>
                            <button id="inline-login-open" class="rounded-md border px-4 py-2">Sign in</button>
                            <button id="rent-confirm" class="rounded-md bg-blue-600 px-4 py-2 text-white">Add to cart</button>
                        </div>
                    @endguest
                </div>
            </div>
        </div>

        <script>
            // small CSS injection for badge animation
            (function(){
                const s = document.createElement('style');
                s.innerHTML = `
                .scale-up { transform: scale(1.4); transition: transform 180ms ease; }
                `;
                document.head.appendChild(s);
            })();
            // small helper to format as IDR
                function formatIDR(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits:0 }).format(value);
                }

                document.addEventListener('DOMContentLoaded', function () {
                    // create a JS variants array from server-side data for fast lookups
                    const variants = @json($variants);

                    // Build quick lookup maps from the variants array for price and display name
                    // Define these early so functions like updatePrices can safely reference them.
                    const mapping = {};
                    const mappingName = {};
                    if (Array.isArray(variants)) {
                        variants.forEach(function(v){
                            try {
                                const slug = v.slug || '';
                                mapping[slug] = Number(v.price_monthly || 0);
                                mappingName[slug] = v.name || slug;
                            } catch (e) {
                                // defensive: skip malformed entries
                            }
                        });
                    }
                    // variant buttons (delegated)
                    const variantButtonsContainer = document.getElementById('variant-buttons');
                    let selectedVariantSlug = variants.length ? variants[0].slug : '';
                    const monthsSelect = document.getElementById('months');
                    const priceMonthlyEl = document.getElementById('price-monthly');
                    const priceTotalEl = document.getElementById('price-total');
                    const mainImage = document.getElementById('main-image');
                    const badge = document.getElementById('cart-count');

                    // initialize badge from server or localStorage fallback
                    const serverCount = Number('{{ $cartCount ?? 0 }}') || 0;
                    const storedCount = Number(localStorage.getItem('cartCount') || 0);
                    const initialCount = serverCount || storedCount;
                    if (badge) {
                        badge.textContent = initialCount;
                    }

                    function capacityMultiplier(capacity) {
                        if (!capacity) return 1;
                        if (capacity.indexOf('512') !== -1) return 1.10;
                        if (capacity.indexOf('1 TB') !== -1 || capacity.indexOf('1TB') !== -1) return 1.20;
                        return 1.0;
                    }

                    // default selected capacity will be first capacity button
                    let selectedCapacity = '256 GB';
                    const capBtns = Array.from(document.querySelectorAll('.capacity-btn'));
                    if (capBtns.length) {
                        // mark first as active if none selected
                        capBtns.forEach(function(b){ b.classList.remove('ring-2','ring-blue-500'); });
                        capBtns[0].classList.add('ring-2','ring-blue-500');
                        selectedCapacity = capBtns[0].textContent.trim();
                    }

                    function updatePrices() {
                        const baseMonthly = Number(mapping[selectedVariantSlug] || 0);
                        const months = Number(monthsSelect.value || 1);
                        const multiplier = capacityMultiplier(selectedCapacity);
                        const monthly = Math.round(baseMonthly * multiplier);
                        const total = monthly * months;
                        priceMonthlyEl.textContent = formatIDR(monthly);
                        priceTotalEl.textContent = formatIDR(total);
                    }

                    // initialize active variant button and image
                    (function initVariants() {
                        const btns = Array.from(document.querySelectorAll('.variant-btn'));
                        btns.forEach(b => b.classList.remove('ring-2','ring-blue-500'));
                        const initialBtn = btns.find(b => b.getAttribute('data-slug') === selectedVariantSlug) || btns[0];
                        if (initialBtn) initialBtn.classList.add('ring-2','ring-blue-500');
                        const v = variants.find(v => v.slug === selectedVariantSlug) || variants[0] || null;
                        if (v && v.image) mainImage.src = v.image;
                        updatePrices();
                    })();

                    // event delegation for variant buttons
                    if (variantButtonsContainer) {
                        variantButtonsContainer.addEventListener('click', function (e) {
                            const btn = e.target.closest('.variant-btn');
                            if (!btn) return;
                            e.preventDefault();
                            // toggle active classes
                            const btns = Array.from(variantButtonsContainer.querySelectorAll('.variant-btn'));
                            btns.forEach(b => b.classList.remove('ring-2','ring-blue-500'));
                            btn.classList.add('ring-2','ring-blue-500');
                            selectedVariantSlug = btn.getAttribute('data-slug');
                            const variantImage = btn.getAttribute('data-image');
                            const v = variants.find(v => v.slug === selectedVariantSlug) || null;
                            if (variantImage) mainImage.src = variantImage;
                            else if (v && v.image) mainImage.src = v.image;
                            updatePrices();
                            syncRentInputs();
                        });
                    }

                    monthsSelect.addEventListener('change', updatePrices);

                    // wire thumbnails via delegation to reduce listeners
                    const thumbsContainer = document.querySelector('.mt-6.flex.gap-4') || null;
                    if (thumbsContainer) {
                        thumbsContainer.addEventListener('click', function(e) {
                            const btn = e.target.closest('.thumb');
                            if (!btn) return;
                            const img = btn.getAttribute('data-image');
                            if (img) mainImage.src = img;
                        });
                    }

                    // mapping created earlier from `variants` for price/name lookups

                    // capacity selection (delegated)
                    const capContainer = document.querySelector('.mt-2.space-y-2');
                    if (capContainer) {
                        capContainer.addEventListener('click', function(e) {
                            const btn = e.target.closest('.capacity-btn');
                            if (!btn) return;
                            e.preventDefault();
                            const all = Array.from(capContainer.querySelectorAll('.capacity-btn'));
                            all.forEach(b => b.classList.remove('ring-2','ring-blue-500'));
                            btn.classList.add('ring-2','ring-blue-500');
                            selectedCapacity = btn.textContent.trim();
                            const rentCap = document.getElementById('rent-capacity');
                            if (rentCap) rentCap.value = selectedCapacity;
                            updatePrices();
                        });
                    }

                    // wire rent form hidden inputs (if form exists)
                    const rentVariant = document.getElementById('rent-variant');
                    const rentMonths = document.getElementById('rent-months');
                    const rentCapacity = document.getElementById('rent-capacity');

                    function syncRentInputs() {
                                        if (rentVariant) rentVariant.value = selectedVariantSlug;
                        if (rentMonths) rentMonths.value = monthsSelect.value;
                        if (rentCapacity) rentCapacity.value = selectedCapacity;
                    }

                    // sync on init and on changes
                    syncRentInputs();
                    monthsSelect.addEventListener('change', syncRentInputs);

                    // modal handling
                    const rentModal = document.getElementById('rent-modal');
                    const rentBtn = document.getElementById('add-to-cart');
                    const rentClose = document.getElementById('rent-modal-close');
                    const rentCancel = document.getElementById('rent-cancel');
                    const rentConfirm = document.getElementById('rent-confirm');

                    function openModal() {
                        const selText = mappingName[selectedVariantSlug] || selectedVariantSlug;
                        const baseMonthly = Number(mapping[selectedVariantSlug] || 0);
                        const months = Number(monthsSelect.value || 1);
                        const monthly = Math.round(baseMonthly * capacityMultiplier(selectedCapacity));

                        // Defensive updates: modal fields are only present for authenticated users
                        const modalVariant = document.getElementById('modal-variant-name');
                        const modalMonths = document.getElementById('modal-months');
                        const modalCapacity = document.getElementById('modal-capacity');
                        const modalPriceMonthly = document.getElementById('modal-price-monthly');
                        const modalPriceTotal = document.getElementById('modal-price-total');

                        if (modalVariant) modalVariant.textContent = selText;
                        if (modalMonths) modalMonths.textContent = months;
                        if (modalCapacity) modalCapacity.textContent = selectedCapacity;
                        if (modalPriceMonthly) modalPriceMonthly.textContent = formatIDR(monthly);
                        if (modalPriceTotal) modalPriceTotal.textContent = formatIDR(monthly * months);

                        if (rentModal) {
                            rentModal.classList.remove('hidden');
                            rentModal.classList.add('flex');
                        }
                    }

                    function closeModal() {
                        rentModal.classList.add('hidden');
                        rentModal.classList.remove('flex');
                    }

                    if (rentBtn) {
                        rentBtn.addEventListener('click', function(e){
                            e.preventDefault();
                            openModal();
                        });
                    }

                    if (rentClose) rentClose.addEventListener('click', closeModal);
                    if (rentCancel) rentCancel.addEventListener('click', closeModal);

                    // guest close
                    const rentModalCloseGuest = document.getElementById('rent-modal-close-guest');
                    if (rentModalCloseGuest) rentModalCloseGuest.addEventListener('click', closeModal);

                    // helper: show toast messages
                    function showToast(message, isError) {
                        const t = document.createElement('div');
                        t.textContent = message;
                        t.className = 'fixed bottom-6 right-6 z-60 rounded-md px-4 py-2 text-sm text-white';
                        t.style.background = isError ? 'rgba(220,38,38,0.9)' : 'rgba(34,197,94,0.94)';
                        document.body.appendChild(t);
                        setTimeout(() => { t.style.transition = 'opacity 300ms'; t.style.opacity = '0'; }, 2500);
                        setTimeout(() => t.remove(), 3000);
                    }

                    // auto-open modal if URL contains openModal=1 (useful after login redirect)
                    try {
                        const params = new URLSearchParams(window.location.search);
                        if (params.has('openModal')) {
                            openModal();
                            // remove openModal param so refresh doesn't keep opening
                            params.delete('openModal');
                            const base = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                            window.history.replaceState({}, document.title, base);
                        }
                    } catch(e) {
                        // ignore URL parsing issues
                    }

                    // Inline login modal handlers
                    const inlineLoginModal = document.getElementById('inline-login-modal');
                    const inlineLoginOpen = document.getElementById('inline-login-open');
                    const inlineLoginClose = document.getElementById('inline-login-close');
                    const inlineLoginCancel = document.getElementById('inline-login-cancel');
                    const inlineLoginForm = document.getElementById('inline-login-form');
                    const inlineLoginSubmit = document.getElementById('inline-login-submit');
                    const inlineLoginError = document.getElementById('inline-login-error');

                    function openInlineLogin() {
                        if (inlineLoginModal) inlineLoginModal.classList.remove('hidden');
                        if (inlineLoginModal) inlineLoginModal.classList.add('flex');
                    }
                    function closeInlineLogin() {
                        if (inlineLoginModal) inlineLoginModal.classList.add('hidden');
                        if (inlineLoginModal) inlineLoginModal.classList.remove('flex');
                        if (inlineLoginError) { inlineLoginError.textContent = ''; inlineLoginError.classList.add('hidden'); }
                    }

                    if (inlineLoginOpen) inlineLoginOpen.addEventListener('click', function(e){ e.preventDefault(); openInlineLogin(); });
                    if (inlineLoginClose) inlineLoginClose.addEventListener('click', closeInlineLogin);
                    if (inlineLoginCancel) inlineLoginCancel.addEventListener('click', closeInlineLogin);

                    if (inlineLoginForm) {
                        inlineLoginForm.addEventListener('submit', async function (ev) {
                            ev.preventDefault();
                            if (!inlineLoginSubmit) return;
                            inlineLoginSubmit.disabled = true;
                            inlineLoginSubmit.textContent = 'Signing in...';

                            const formData = new FormData(inlineLoginForm);
                            // use URLSearchParams so Laravel's typical login handler accepts it
                            const body = new URLSearchParams();
                            for (const pair of formData.entries()) body.append(pair[0], pair[1]);

                            try {
                                const res = await fetch('/login', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value,
                                        'Accept': 'application/json, text/plain, */*'
                                    },
                                    body,
                                    credentials: 'same-origin'
                                });

                                // If the login endpoint redirected (res.redirected) or returned 200, assume success and reload with modal open
                                if (res.ok) {
                                    // go back to same page and open rent modal
                                    const redirectUrl = window.location.pathname + (window.location.search ? window.location.search + '&openModal=1' : '?openModal=1');
                                    window.location.href = redirectUrl;
                                } else if (res.status === 422) {
                                    // validation errors
                                    const data = await res.json();
                                    const msg = (data && data.message) ? data.message : 'Invalid credentials';
                                    if (inlineLoginError) { inlineLoginError.textContent = msg; inlineLoginError.classList.remove('hidden'); }
                                } else {
                                    const text = await res.text();
                                    if (inlineLoginError) { inlineLoginError.textContent = 'Login failed'; inlineLoginError.classList.remove('hidden'); }
                                }
                            } catch (err) {
                                console.error(err);
                                if (inlineLoginError) { inlineLoginError.textContent = 'Network error'; inlineLoginError.classList.remove('hidden'); }
                            } finally {
                                inlineLoginSubmit.disabled = false;
                                inlineLoginSubmit.textContent = 'Sign in';
                            }
                        });
                    }

                    if (rentConfirm) {
                        rentConfirm.addEventListener('click', function(){
                            syncRentInputs();
                            const payload = {
                                variant_slug: (rentVariant && rentVariant.value) ? rentVariant.value : selectedVariantSlug,
                                months: (rentMonths && rentMonths.value) ? Number(rentMonths.value) : Number(monthsSelect.value),
                                capacity: (rentCapacity && rentCapacity.value) ? rentCapacity.value : selectedCapacity,
                                quantity: 1
                            };

                            // disable while processing
                            rentConfirm.disabled = true;
                            const prevText = rentConfirm.textContent;
                            rentConfirm.textContent = 'Processing...';

                            (async function(){
                                try {
                                    const res = await fetch('{{ route('cart.add') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json, text/plain, */*',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
                                        },
                                        body: JSON.stringify(payload),
                                        credentials: 'same-origin'
                                    });
                                    const data = await res.json();
                                    if (data && data.count !== undefined) {
                                        if (badge) {
                                            badge.textContent = data.count;
                                            localStorage.setItem('cartCount', data.count);
                                            badge.classList.add('scale-up');
                                            setTimeout(()=> badge.classList.remove('scale-up'), 400);
                                        }
                                        showToast('Added to cart');
                                        closeModal();
                                    } else {
                                        showToast('Added to cart', false);
                                        closeModal();
                                    }
                                } catch (err) {
                                    console.error(err);
                                    showToast('Could not add to cart. Please try again.', true);
                                } finally {
                                    rentConfirm.disabled = false;
                                    rentConfirm.textContent = prevText;
                                }
                            })();
                        });
                    }
                });
        </script>
    </body>
</html>
