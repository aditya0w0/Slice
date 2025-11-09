<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Devices - Slice</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap"
            rel="stylesheet"
        />
        @vite("resources/css/app.css")
    </head>
    <body>
        <header class="mx-auto max-w-7xl px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="text-2xl font-extrabold text-gray-900">SLICE</div>
                    <a href="{{ route("cart.index") }}" class="relative inline-flex items-center" id="cart-link">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-gray-700"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4"
                            />
                        </svg>
                        <span
                            id="cart-count"
                            class="absolute -top-2 -right-3 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs text-white"
                        >
                            {{ $cartCount ?? 0 }}
                        </span>
                    </a>
                </div>
                <nav class="flex gap-4">
                    <a href="/" class="text-sm text-gray-600 hover:text-gray-900">Home</a>
                    <a href="/devices" class="text-sm font-semibold text-gray-900">Devices</a>
                    <a href="#contact" class="text-sm text-gray-600 hover:text-gray-900">Contact</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 pb-20">
            <div class="mt-8 flex flex-col gap-8 lg:flex-row">
                <!-- Sidebar / Filters -->
                <aside class="w-full shrink-0 lg:w-72">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900">Filters</h2>

                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-700">Category</h3>
                            <ul class="mt-2 space-y-2 text-sm text-gray-600">
                                <li><a href="#" class="block hover:text-gray-900">iPhone</a></li>
                                <li><a href="#" class="block hover:text-gray-900">iPad</a></li>
                                <li><a href="#" class="block hover:text-gray-900">Mac</a></li>
                                <li><a href="#" class="block hover:text-gray-900">Accessories</a></li>
                            </ul>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-700">Price</h3>
                            <div class="mt-3 flex items-center gap-3">
                                <input type="text" placeholder="$20" class="w-20 rounded-md border px-2 py-1 text-sm" />
                                <span class="text-sm text-gray-500">—</span>
                                <input
                                    type="text"
                                    placeholder="$120"
                                    class="w-20 rounded-md border px-2 py-1 text-sm"
                                />
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Products grid -->
                <section class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900">Devices</h1>
                            <p class="mt-1 text-sm text-gray-600">
                                Browse models by category. Select a category to view only those devices.
                            </p>
                        </div>
                        <div class="text-sm text-gray-500">Showing {{ count($baseModels) }} results</div>
                    </div>

                    {{-- Category tabs: show only one category at a time per user's request --}}
                    @php
                        $currentCategory = request()->query("category", "iPhone");
                        $categories = ["iPhone", "iPad", "Mac", "Accessories"];
                    @endphp

                    <div class="mt-4 flex items-center gap-3">
                        @foreach ($categories as $cat)
                            @php
                                $slug = strtolower($cat);
                            @endphp

                            <a
                                href="{{ route("devices") }}?category={{ $slug }}"
                                class="{{ strtolower($currentCategory) === $slug ? "bg-blue-600 text-white" : "border bg-white" }} rounded-full px-4 py-2 text-sm font-medium"
                            >
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($baseModels as $base)
                            <article class="rounded-2xl border border-gray-100 bg-white p-6 hover:shadow">
                                <div class="flex items-center gap-4">
                                    <img
                                        src="/images/product-iphone.svg"
                                        alt="{{ $base["family_name"] }}"
                                        class="h-20 w-20 object-contain"
                                    />
                                    <div>
                                        <div class="text-lg font-semibold text-gray-900">
                                            {{ $base->display_title ?? ($base["family_name"] ?? $base["name"]) }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Explore {{ $base["family_name"] }} — choose variant and capacity on the
                                            next page
                                        </div>
                                        <div class="mt-4">
                                            @php
                                                // Compute family route param for both model instances
                                                // and array-based rows returned from queries.
                                                $familyParam = null;
                                                if (is_object($base) && isset($base->family_param)) {
                                                    $familyParam = $base->family_param;
                                                } else {
                                                    $slug = $base["family_slug"] ?? ($base["slug"] ?? null);
                                                    if (empty($slug)) {
                                                        $slug = strtolower(preg_replace("/[^a-z0-9\-]+/i", "-", trim($base["family_name"] ?? ($base["name"] ?? ""))));
                                                    }
                                                    $familyParam = $slug;
                                                    $gen = $base["generation"] ?? 0;
                                                    if (! empty($gen) && $gen > 0) {
                                                        if (! preg_match("/-" . preg_quote((string) $gen, "/") . '$/', $familyParam)) {
                                                            $familyParam = $familyParam . "-" . $gen;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            <a
                                                href="{{ route("devices.model", ["family" => $familyParam]) }}"
                                                class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                            >
                                                View models
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            </div>
        </main>
        <script>
            // update rent links when duration changes
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.rent-duration').forEach(function (select) {
                    select.addEventListener('change', function () {
                        var months = this.value;
                        var slug = this.getAttribute('data-device-slug');
                        var link = document.querySelector('.rent-link[data-base-href="/devices/' + slug + '"]');
                        if (link) {
                            link.href = link.getAttribute('data-base-href') + '?months=' + months;
                        }
                    });
                });
            });
        </script>
    </body>
</html>
