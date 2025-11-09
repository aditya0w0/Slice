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
                                <div class="text-sm text-gray-500">
                                    SKU:
                                    <span class="text-gray-700">{{ $first->sku ?? "—" }}</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="text-xs text-gray-500">Model</label>
                                <div id="model-buttons" class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($variants as $variant)
                                        <button
                                            type="button"
                                            class="variant-btn inline-flex items-center rounded-full border px-4 py-2 text-sm font-medium whitespace-nowrap"
                                            data-slug="{{ $variant->slug }}"
                                            data-price="{{ $variant->price_formatted }}"
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
                                <div id="capacity-buttons" class="mt-2 grid grid-cols-1 gap-2">
                                    <button
                                        class="capacity-btn w-full rounded-md border px-3 py-2 text-left"
                                        data-cap="256 GB"
                                    >
                                        256 GB
                                    </button>
                                    <button
                                        class="capacity-btn w-full rounded-md border px-3 py-2 text-left"
                                        data-cap="512 GB"
                                    >
                                        512 GB
                                    </button>
                                    <button
                                        class="capacity-btn w-full rounded-md border px-3 py-2 text-left"
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

                                <a
                                    id="rent-btn"
                                    href="/devices/{{ $first->slug }}?months=12"
                                    data-base="/devices/{{ $first->slug }}"
                                    class="inline-flex items-center rounded-full bg-blue-600 px-6 py-3 text-sm font-medium text-white"
                                >
                                    Tambah keranjang
                                </a>
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
                    <a
                        id="sticky-btn"
                        href="/devices/{{ $first->slug }}?months=12"
                        data-base="/devices/{{ $first->slug }}"
                        class="rounded-full bg-blue-600 px-6 py-3 text-sm font-medium text-white"
                    >
                        Tambah keranjang
                    </a>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // thumbnail clicks update main image and selected model
                document.querySelectorAll('.thumbnail').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var img = this.getAttribute('data-image');
                        var slug = this.getAttribute('data-slug');
                        var main = document.getElementById('main-product-image');
                        if (main && img) main.src = img;
                        // update model select
                        var select = document.getElementById('model-select');
                        if (select) select.value = slug;
                        // update rent links
                        var rent = document.getElementById('rent-btn');
                        var sticky = document.getElementById('sticky-btn');
                        if (rent) {
                            rent.href =
                                '/devices/' +
                                slug +
                                '?months=' +
                                (document.getElementById('duration-select')?.value || '12');
                            rent.setAttribute('data-base', '/devices/' + slug);
                        }
                        if (sticky) {
                            sticky.href =
                                '/devices/' +
                                slug +
                                '?months=' +
                                (document.getElementById('duration-select')?.value || '12');
                            sticky.setAttribute('data-base', '/devices/' + slug);
                        }
                    });
                });

                // model select change updates image/price/link
                var modelSelect = document.getElementById('model-select');
                if (modelSelect) {
                    modelSelect.addEventListener('change', function () {
                        var opt = this.options[this.selectedIndex];
                        var img = opt.getAttribute('data-image');
                        var price = opt.getAttribute('data-price');
                        var slug = this.value;
                        if (img) document.getElementById('main-product-image').src = img;
                        if (price) document.getElementById('price').textContent = price;
                        if (document.getElementById('sticky-price'))
                            document.getElementById('sticky-price').textContent = price;
                        var base = '/devices/' + slug;
                        var months = document.getElementById('duration-select')?.value || '12';
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
                }

                // duration changes update rent links
                var duration = document.getElementById('duration-select');
                if (duration) {
                    duration.addEventListener('change', function () {
                        var months = this.value;
                        var base = document.getElementById('rent-btn')?.getAttribute('data-base') || '';
                        var rent = document.getElementById('rent-btn');
                        var sticky = document.getElementById('sticky-btn');
                        if (rent && base) rent.href = base + '?months=' + months;
                        if (sticky && base) sticky.href = base + '?months=' + months;
                    });
                }

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
            });
        </script>
    </body>
</html>
