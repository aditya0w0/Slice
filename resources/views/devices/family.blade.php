<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $family }} models - Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body>
        <header class="mx-auto max-w-7xl px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-extrabold text-gray-900">SLICE</div>
                <nav class="flex gap-4">
                    <a href="/" class="text-sm text-gray-600 hover:text-gray-900">Home</a>
                    <a href="/devices" class="text-sm font-semibold text-gray-900">Devices</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 pb-20">
            <div class="mt-8">
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $family }}</h1>
                <p class="mt-2 text-sm text-gray-600">Pick a variant and choose your rental length.</p>

                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($variants as $variant)
                        <article class="rounded-2xl border border-gray-100 bg-white p-6 hover:shadow">
                            <div class="flex flex-col items-start gap-4">
                                <img
                                    src="{{ $variant->image ?? "/images/product-iphone.svg" }}"
                                    alt="{{ $variant->name }}"
                                    class="h-28 w-full object-contain"
                                />
                                <div class="w-full">
                                    <div class="text-lg font-semibold text-gray-900">{{ $variant->name }}</div>
                                    <p class="mt-1 text-sm text-gray-500">{{ $variant->description }}</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <div>
                                            <div class="text-sm text-gray-500">Price</div>
                                            <div class="text-xl font-bold text-gray-900">
                                                {{ $variant->price_formatted }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <label class="text-xs text-gray-500">Duration</label>
                                            <select
                                                class="variant-duration mt-1 rounded-md border px-2 py-1 text-sm"
                                                data-slug="{{ $variant->slug }}"
                                            >
                                                <option value="1">1 month</option>
                                                <option value="3">3 months</option>
                                                <option value="6">6 months</option>
                                                <option value="12" selected>12 months</option>
                                                <option value="24">24 months</option>
                                            </select>
                                            <a
                                                href="/devices/{{ $variant->slug }}?months=12"
                                                data-base="/devices/{{ $variant->slug }}"
                                                class="rent-now mt-3 inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                            >
                                                Rent
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.variant-duration').forEach(function (sel) {
                    sel.addEventListener('change', function () {
                        var months = this.value;
                        var slug = this.getAttribute('data-slug');
                        var btn = document.querySelector('a.rent-now[data-base="/devices/' + slug + '"]');
                        if (btn) btn.href = btn.getAttribute('data-base') + '?months=' + months;
                    });
                });
            });
        </script>
    </body>
</html>
