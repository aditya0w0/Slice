<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $device->name }} - Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body>
        <main class="mx-auto max-w-4xl px-6 py-12">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
                <div class="lg:w-1/2">
                    <img
                        src="{{ $device->image ?? "/images/product-iphone.svg" }}"
                        alt="{{ $device->name }}"
                        class="w-full object-contain"
                    />
                </div>
                <div class="lg:w-1/2">
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $device->name }}</h1>
                    <p class="mt-2 text-gray-600">{{ $device->description }}</p>

                    <div class="mt-6">
                        <div class="text-sm text-gray-500">Price</div>
                        <div class="mt-1 text-2xl font-bold">{{ $device->price_formatted }}</div>
                    </div>

                    <div class="mt-4">
                        <label class="text-sm text-gray-600">Rent duration</label>
                        <select id="show-rent-duration" class="ml-2 rounded-md border px-2 py-1 text-sm">
                            <option value="1">1 month</option>
                            <option value="3">3 months</option>
                            <option value="6">6 months</option>
                            <option value="12" selected>12 months</option>
                            <option value="24">24 months</option>
                        </select>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a
                            id="show-rent-now"
                            href="#"
                            class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                            data-base-href="/devices/{{ $device->slug }}"
                        >
                            Rent now
                        </a>
                        <a
                            href="/devices"
                            class="inline-flex items-center rounded-full border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700"
                        >
                            Back
                        </a>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var sel = document.getElementById('show-rent-duration');
                            var btn = document.getElementById('show-rent-now');
                            if (sel && btn) {
                                function update() {
                                    btn.href = btn.getAttribute('data-base-href') + '?months=' + sel.value;
                                }
                                sel.addEventListener('change', update);
                                update();
                            }
                        });
                    </script>
                </div>
            </div>
        </main>
    </body>
</html>
