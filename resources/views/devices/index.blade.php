<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Devices - Slice</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap"
            rel="stylesheet"
        />
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20">
        @include("partials.header")

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5"></div>
            <div class="relative mx-auto max-w-7xl px-6 py-16 text-center">
                <div
                    class="inline-block rounded-full bg-gradient-to-r from-blue-600/10 to-purple-600/10 px-6 py-2 backdrop-blur-sm"
                >
                    <span
                        class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-sm font-semibold text-transparent"
                    >
                        Premium Device Rentals
                    </span>
                </div>
                <h1 class="mt-6 text-5xl font-black tracking-tight text-gray-900 lg:text-6xl">
                    Rent the Latest Devices
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-600">
                    Experience cutting-edge technology without the commitment. Flexible rental plans for every need.
                </p>
            </div>
        </div>

        <main class="mx-auto max-w-7xl px-6 pb-20">
            <!-- Subheading -->
            <div class="my-10 text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Our Device Families
                </h2>
                <p class="mt-3 text-lg text-gray-600">
                    Browse individual models and model years within each product family.
                </p>
            </div>

            <!-- Devices Grid -->
            <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($baseModels as $base)
                    @php
                        // use family-aware default image if no image is set
                        $defaultImage = "/images/product-iphone.svg";
                        if (str_starts_with(mb_strtolower($base["name"] ?? ""), "ipad")) {
                            $defaultImage = "/images/product-ipad.svg";
                        } elseif (mb_stripos($base["name"] ?? "", "mac") !== false) {
                            $defaultImage = "/images/product-mac.svg";
                        } elseif (mb_stripos($base["name"] ?? "", "watch") !== false || mb_stripos($base["name"] ?? "", "accessory") !== false) {
                            $defaultImage = "/images/product-watch.svg";
                        }
                        $imagePath = $base["image"] ?? $defaultImage;

                        // Determine gradient based on device family
                        $gradientClass = "from-blue-600 to-purple-600";
                        if (str_starts_with(mb_strtolower($base["name"] ?? ""), "ipad")) {
                            $gradientClass = "from-indigo-600 to-blue-600";
                        } elseif (mb_stripos($base["name"] ?? "", "mac") !== false) {
                            $gradientClass = "from-gray-800 to-gray-600";
                        } elseif (mb_stripos($base["name"] ?? "", "watch") !== false) {
                            $gradientClass = "from-rose-600 to-pink-600";
                        }
                    @endphp

                    <article
                        class="group relative overflow-hidden rounded-3xl bg-white shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl"
                    >
                        <!-- Gradient overlay on hover -->
                        <div
                            class="{{ $gradientClass }} absolute inset-0 bg-gradient-to-br opacity-0 transition-opacity duration-300 group-hover:opacity-5"
                        ></div>

                        <!-- Content -->
                        <div class="relative p-8">
                            <!-- Device Image -->
                            <div
                                class="flex h-48 items-center justify-center rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100/50 p-8 transition-transform duration-300 group-hover:scale-105"
                            >
                                <img
                                    src="{{ $imagePath }}"
                                    alt="{{ $base["name"] }}"
                                    class="h-full w-full object-contain drop-shadow-lg"
                                />
                            </div>

                            <!-- Device Info -->
                            <div class="mt-6">
                                <h3 class="text-2xl font-bold text-gray-900">
                                    {{ $base["name"] }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-600">Available models: Base, Pro, Pro Max</p>

                                <!-- Features -->
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"
                                    >
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Flexible Plans
                                    </span>
                                    <span
                                        class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700"
                                    >
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        Verified
                                    </span>
                                </div>

                                <!-- CTA Button -->
                                <div class="mt-6">
                                    <a
                                        href="{{ route("devices.model", ["family" => $base["family_slug"]]) }}"
                                        class="{{ $gradientClass }} flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/40"
                                    >
                                        <span>Explore Models</span>
                                        <svg
                                            class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5l7 7-7 7"
                                            />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Bottom Info Section -->
            <div class="mt-16 grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 text-center shadow-lg">
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-purple-600"
                    >
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900">Flexible Duration</h3>
                    <p class="mt-2 text-sm text-gray-600">Rent for days, weeks, or months</p>
                </div>
                <div class="rounded-2xl bg-white p-6 text-center shadow-lg">
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-purple-600"
                    >
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900">Fully Insured</h3>
                    <p class="mt-2 text-sm text-gray-600">All devices are covered and protected</p>
                </div>
                <div class="rounded-2xl bg-white p-6 text-center shadow-lg">
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-purple-600"
                    >
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900">Latest Models</h3>
                    <p class="mt-2 text-sm text-gray-600">Always up-to-date devices available</p>
                </div>
            </div>
        </main>
    </body>
</html>
