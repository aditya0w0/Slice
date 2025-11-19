<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Home - Apple Ecosystem Rent</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap"
            rel="stylesheet"
        />
        @vite("resources/css/app.css")
    </head>
    <body>
        <header class="fixed top-3 right-0 left-0 z-50 mx-auto max-w-4xl">
            <nav
                class="flex items-center justify-between rounded-full border border-gray-200/50 bg-white/90 px-5 py-2 shadow-sm backdrop-blur-xl"
            >
                <div class="shrink-0">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                        />
                    </svg>
                </div>

                {{-- Links --}}
                <ul class="flex items-center gap-1">
                    <li>
                        <a
                            href="/"
                            class="rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-900"
                        >
                            Home
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route("devices") }}"
                            class="rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-900"
                        >
                            Devices
                        </a>
                    </li>
                </ul>

                {{-- Links end --}}

                {{-- Buttons: keep homepage header unchanged — always show Login link (do not display user name here) --}}
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route("login") }}"
                        class="rounded-full bg-blue-600 px-4 py-1.5 text-sm font-medium text-white shadow-sm transition-colors duration-150 hover:bg-blue-700"
                    >
                        Login
                    </a>
                </div>
            </nav>
        </header>

        <main>
            {{-- Hero Section --}}
            <section
                class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-gray-900 via-[#0a0a0a] to-black px-4 py-20 text-center text-white"
            >
                {{-- Title and Description --}}
                <div class="animate-fade-in-up relative z-10 flex flex-col items-center gap-y-8">
                    <h1
                        class="max-w-6xl text-7xl leading-none font-extrabold tracking-tight sm:text-8xl md:text-9xl lg:text-[10rem]"
                    >
                        <span class="block bg-gradient-to-b from-white to-gray-400 bg-clip-text text-transparent">
                            The Future of
                        </span>
                        <span
                            class="block bg-gradient-to-r from-blue-400 via-purple-500 to-indigo-500 bg-clip-text text-transparent"
                        >
                            Apple Ecosystem.
                        </span>
                    </h1>
                    <p class="max-w-3xl text-xl leading-normal text-gray-400 sm:text-2xl">
                        Access the latest iPhones, iPads, and MacBooks with flexible monthly plans. The smarter way to
                        stay connected and up-to-date without the commitment of full ownership.
                    </p>
                </div>
                {{-- Call to Action --}}
                <div class="animate-fade-in-up relative z-10 mt-16 delay-200">
                    <a
                        href="{{ route("devices") }}"
                        class="group relative inline-flex items-center justify-center overflow-hidden rounded-full bg-white px-12 py-4 text-xl font-semibold text-gray-900 shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] transition-all duration-300 hover:scale-105 hover:shadow-[0_0_60px_-15px_rgba(255,255,255,0.5)] focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-black focus:outline-none"
                    >
                        <span class="relative z-10">Explore Devices</span>
                        <div
                            class="absolute inset-0 -z-10 bg-gradient-to-r from-blue-100 via-white to-blue-100 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                        ></div>
                    </a>
                </div>

                {{-- Background Effects --}}
                <div
                    class="absolute top-1/2 left-1/2 -z-0 h-[1000px] w-[1000px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-500/10 blur-[120px]"
                ></div>
                <div
                    class="absolute bottom-0 left-0 -z-0 h-[500px] w-[500px] rounded-full bg-purple-500/10 blur-[100px]"
                ></div>
            </section>
            {{-- Product Section --}}
            <section class="mx-auto max-w-7xl px-6 py-20">
                <h3 class="mb-12 text-center text-lg font-semibold text-gray-900">How it works</h3>

                <div class="flex flex-col gap-8 lg:gap-12">
                    {{-- Top Row: Heading + Description --}}
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:gap-12">
                        <div class="flex-1">
                            <h2 class="text-4xl leading-tight font-bold text-gray-900 lg:text-5xl">
                                Keys to freedom,
                                <br />
                                wheels to adventure.
                            </h2>
                        </div>
                        <p class="flex-1 text-balance text-gray-600 lg:pt-14">
                            Discover the freedom to travel on your terms with our reliable and affordable car rental
                            services. Whether you are exploring the city, we offer a wide range of vehicles to suit your
                            needs.
                        </p>
                    </div>

                    {{-- Bottom Row: Feature List + Image --}}
                    <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                        <ul class="flex shrink-0 flex-col gap-4">
                            <li class="flex items-center gap-4">
                                <span class="h-12 w-1 shrink-0 rounded-full bg-gray-300"></span>
                                <p class="font-medium text-gray-700">Flexible Booking Options.</p>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="h-12 w-1 shrink-0 rounded-full bg-orange-500"></span>
                                <p class="text-lg font-semibold text-gray-900">Luxury and Comfort.</p>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="h-12 w-1 shrink-0 rounded-full bg-gray-300"></span>
                                <p class="font-medium text-gray-700">24/7 Roadside Assistance.</p>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="h-12 w-1 shrink-0 rounded-full bg-gray-300"></span>
                                <p class="font-medium text-gray-700">Affordable Pricing.</p>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="h-12 w-1 shrink-0 rounded-full bg-gray-300"></span>
                                <p class="font-medium text-gray-700">Loyalty Rewards Program.</p>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="h-12 w-1 shrink-0 rounded-full bg-gray-300"></span>
                                <p class="font-medium text-gray-700">One-Way Rentals.</p>
                            </li>
                        </ul>

                        <img
                            src="/images/section.png"
                            alt="Luxury Car - Front View"
                            class="h-auto w-full object-contain lg:ml-16 lg:w-auto lg:max-w-4xl"
                        />
                    </div>
                </div>
            </section>

            {{-- Our Vision --}}
            <section class="mx-auto max-w-7xl bg-white px-6 py-20">
                <div class="mx-auto max-w-4xl text-center">
                    <h3 class="text-lg font-semibold text-blue-600">Our Vision</h3>
                    <h2 class="mt-3 text-3xl font-bold text-gray-900 sm:text-4xl">
                        Make connected devices affordable, flexible, and sustainable.
                    </h2>
                    <p class="mt-4 text-gray-600">
                        We believe everyone should access the latest technology without long-term commitments. Our plans
                        are built to be simple, transparent and kinder to the planet.
                    </p>
                </div>

                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="rounded-lg bg-blue-50 p-3">
                                <!-- Simple globe SVG -->
                                <svg
                                    class="h-6 w-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">Accessible</h4>
                                <p class="mt-1 text-sm text-gray-600">
                                    Flexible monthly plans that remove upfront costs and long commitments.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="rounded-lg bg-green-50 p-3">
                                <!-- Refresh / circular arrow -->
                                <svg
                                    class="h-6 w-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M20 12a8 8 0 10-7.446 7.962M4 4v6h6"
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">Upgradable</h4>
                                <p class="mt-1 text-sm text-gray-600">
                                    Swap devices or upgrade anytime — stay with the latest tech as your needs evolve.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="rounded-lg bg-yellow-50 p-3">
                                <!-- Leaf / eco -->
                                <svg
                                    class="h-6 w-6 text-yellow-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M3 12s4-9 9-9 9 9 9 9-4 9-9 9S3 12 3 12z"
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">Sustainable</h4>
                                <p class="mt-1 text-sm text-gray-600">
                                    Reduce electronic waste through device reuse and efficient lifecycle management.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-center">
                    <a
                        href="#contact"
                        class="inline-flex items-center rounded-full bg-blue-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
                    >
                        Join the movement
                    </a>
                </div>
            </section>

            {{-- Product Showcase — Full-bleed Grid (Apple homepage style) --}}
            <section class="w-full px-0 py-0">
                @php
                    use Illuminate\Support\Str;
                @endphp

                <div class="w-full bg-white">
                    <div class="py-6 text-center">
                        <h3 class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Featured</h3>
                        <h2 class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Devices made for the way you live
                        </h2>
                    </div>

                    <!-- full-bleed 2x2 style mosaic, extended with extra tiles (keeps tile behaviour identical) -->
                    <div class="grid grid-cols-1 gap-0 sm:grid-cols-2">
                        <!-- Tile A -->
                        <div
                            class="flex flex-col items-center justify-center border-b border-gray-100 bg-gray-50 p-12 text-center sm:border-r sm:border-b-0 md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">iPhone</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Get the latest iPhone on a monthly subscription — no long commitments.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices.model", ["family" => "iphone"]) }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-iphone.svg"
                                    alt="iPhone"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>

                        <!-- Tile B -->
                        <div
                            class="flex flex-col items-center justify-center border-b border-gray-100 bg-white p-12 text-center sm:border-b-0 md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">MacBook</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Lightweight laptops available on subscription for creators and teams.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices.model", ["family" => Str::slug("MacBook Air")]) }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-macbook.svg"
                                    alt="MacBook"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>

                        <!-- Tile C -->
                        <div
                            class="flex flex-col items-center justify-center border-t border-gray-100 bg-gray-50 p-12 text-center sm:border-t-0 sm:border-r md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">iPad</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Flexible iPad plans for students and professionals — subscribe and swap as needed.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices") }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-ipad.svg"
                                    alt="iPad"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>

                        <!-- Tile D -->
                        <div
                            class="flex flex-col items-center justify-center border-t border-gray-100 bg-white p-12 text-center sm:border-t-0 md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">AirPods</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Premium wireless audio available on subscription — enjoy noise cancellation and
                                    more.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices") }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-airpods.svg"
                                    alt="AirPods"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>

                        <!-- Tile E -->
                        <div
                            class="flex flex-col items-center justify-center border-t border-gray-100 bg-gray-50 p-12 text-center sm:border-t-0 sm:border-r md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">Mac mini</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Compact desktop performance available for teams and creators.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices.model", ["family" => Str::slug("Mac mini")]) }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-macmini.svg"
                                    alt="Mac mini"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>

                        <!-- Tile F -->
                        <div
                            class="flex flex-col items-center justify-center border-t border-gray-100 bg-white p-12 text-center sm:border-t-0 md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">Apple Watch</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Stay connected on the go with our Apple Watch plans.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices.model", ["family" => Str::slug("Apple Watch")]) }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-applewatch.svg"
                                    alt="Apple Watch"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>

                        <!-- Tile G -->
                        <div
                            class="flex flex-col items-center justify-center border-t border-gray-100 bg-gray-50 p-12 text-center sm:border-t-0 sm:border-r md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">Apple TV</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Stream your favorite shows with our entertainment packages.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices.model", ["family" => Str::slug("Apple TV")]) }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-appletv.svg"
                                    alt="Apple TV"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>

                        <!-- Tile H -->
                        <div
                            class="flex flex-col items-center justify-center border-t border-gray-100 bg-white p-12 text-center sm:border-t-0 md:p-20"
                        >
                            <div class="max-w-2xl">
                                <h3 class="text-2xl font-semibold text-gray-900">HomePod</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Smart speakers for the home — small footprint, big sound.
                                </p>
                                <div class="mt-4">
                                    <a
                                        href="{{ route("devices.model", ["family" => Str::slug("HomePod")]) }}"
                                        class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                    >
                                        Explore
                                    </a>
                                </div>
                            </div>
                            <div class="mt-8 w-full">
                                <img
                                    src="/images/product-homepod.svg"
                                    alt="HomePod"
                                    class="mx-auto w-full max-w-none object-contain"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Reviews / Testimonials --}}
            <section class="mx-auto max-w-7xl bg-gray-50 px-6 py-16">
                <h3 class="mb-8 text-center text-lg font-semibold text-gray-900">What people say</h3>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <blockquote class="rounded-2xl bg-white p-6 shadow-sm">
                        <p class="text-gray-700">
                            “Switching to Slice made upgrading my phone effortless — and the monthly billing is so
                            clear.”
                        </p>
                        <footer class="mt-4 flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-medium text-blue-600"
                            >
                                AM
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Alex Morgan</div>
                                <div class="text-xs text-gray-500">Product Designer</div>
                            </div>
                        </footer>
                    </blockquote>

                    <blockquote class="rounded-2xl bg-white p-6 shadow-sm">
                        <p class="text-gray-700">
                            “Great for our small team — fewer headaches with device lifecycles and replacements.”
                        </p>
                        <footer class="mt-4 flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-medium text-indigo-600"
                            >
                                BK
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Bella Kim</div>
                                <div class="text-xs text-gray-500">Operations Lead</div>
                            </div>
                        </footer>
                    </blockquote>

                    <blockquote class="rounded-2xl bg-white p-6 shadow-sm">
                        <p class="text-gray-700">
                            “Upgrading whenever I want is a game-changer. Customer support was friendly and fast.”
                        </p>
                        <footer class="mt-4 flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-medium text-emerald-600"
                            >
                                TR
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Taylor Reed</div>
                                <div class="text-xs text-gray-500">Freelancer</div>
                            </div>
                        </footer>
                    </blockquote>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="mx-auto w-full border-t bg-white px-6 py-10">
                <div class="mx-auto max-w-7xl">
                    <div class="flex flex-col items-start justify-between gap-6 lg:flex-row">
                        <div class="flex items-center gap-4">
                            <div class="text-2xl font-extrabold text-gray-900">SLICE</div>
                            <p class="text-sm text-gray-500">Flexible device plans</p>
                        </div>

                        <nav class="flex gap-4">
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Pricing</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Support</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Terms</a>
                        </nav>
                    </div>

                    <div class="mt-8 flex flex-col items-center justify-between gap-4 sm:flex-row">
                        <p class="text-sm text-gray-500">© {{ date("Y") }} Slice. All rights reserved.</p>
                        <div class="flex gap-3">
                            <a href="#" class="rounded-md p-2 text-gray-600 hover:bg-gray-100">
                                <span class="sr-only">Twitter</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path
                                        d="M8.29 20c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.36 8.36 0 0022 5.92a8.19 8.19 0 01-2.36.65 4.11 4.11 0 001.8-2.27 8.22 8.22 0 01-2.6.99 4.1 4.1 0 00-7 3.74A11.64 11.64 0 013 4.88a4.1 4.1 0 001.27 5.47A4.07 4.07 0 012.8 10v.05a4.1 4.1 0 003.29 4.02 4.1 4.1 0 01-1.85.07 4.1 4.1 0 003.83 2.85A8.24 8.24 0 012 18.57a11.62 11.62 0 006.29 1.84"
                                    ></path>
                                </svg>
                            </a>
                            <a href="#" class="rounded-md p-2 text-gray-600 hover:bg-gray-100">
                                <span class="sr-only">Instagram</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </body>
</html>
