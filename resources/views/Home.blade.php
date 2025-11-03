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
                    @if (isset($navBar["logo"]))
                        {!! $navBar["logo"] !!}
                    @endif
                </div>

                {{-- Links --}}
                <ul class="flex items-center gap-1">
                    @foreach ($navBar as $name => $link)
                        @if ($name !== "logo")
                            <li>
                                <a
                                    href="{{ $link }}"
                                    class="rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-900"
                                >
                                    {{ $name }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                {{-- Buttons --}}
                <div class="flex items-center gap-2">
                    <button
                        class="rounded-full bg-blue-600 px-4 py-1.5 text-sm font-medium text-white shadow-sm transition-colors duration-150 hover:bg-blue-700"
                    >
                        Request Demo
                    </button>
                </div>
            </nav>
        </header>

        <main>
            {{-- Hero Section --}}
            <section
                class="relative flex min-h-screen flex-col items-center justify-center gap-y-3 overflow-hidden text-center"
                style="
                    background-image: url('/images/hero_background.svg');
                    background-size: cover;
                    background-position: center;
                "
            >
                {{-- Title --}}
                <div class="relative z-10 flex flex-col gap-y-4">
                    <h1 class="font-inter text-8xl font-extrabold text-white text-shadow-2xs text-shadow-blue-400">
                        SLICE
                    </h1>
                    <p class="text-white/90">
                        Access the latest iPhones with a flexible monthly plan.
                        <br />
                        It's the smarter way to stay connected and up-to-date.
                    </p>
                </div>
                <div class="relative z-10 mt-4">
                    <button
                        class="rounded-xl bg-blue-400 px-12 py-2 text-white transition-all hover:bg-blue-500 hover:shadow-lg hover:shadow-blue-300/50"
                    >
                        Get Started
                    </button>
                </div>

                {{-- Animated Wave Fog Decoration --}}
                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-64 overflow-hidden">
                    <svg
                        class="absolute bottom-0 h-full"
                        style="width: 110%; left: -5%"
                        viewBox="0 0 1440 320"
                        preserveAspectRatio="none"
                    >
                        <path
                            fill="#ffffff"
                            fill-opacity="0.25"
                            d="M0,160L48,160C96,160,192,160,288,170.7C384,181,480,203,576,208C672,213,768,203,864,192C960,181,1056,171,1152,149.3C1248,128,1344,96,1392,80L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                            style="animation: wave 20s ease-in-out infinite"
                        ></path>
                        <path
                            fill="#ffffff"
                            fill-opacity="0.3"
                            d="M0,128L48,138.7C96,149,192,171,288,170.7C384,171,480,149,576,138.7C672,128,768,128,864,133.3C960,139,1056,149,1152,149.3C1248,149,1344,139,1392,133.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                            style="animation: wave 16s ease-in-out infinite reverse"
                        ></path>
                        <path
                            fill="#ffffff"
                            fill-opacity="0.35"
                            d="M0,96L48,101.3C96,107,192,117,288,112C384,107,480,85,576,74.7C672,64,768,64,864,74.7C960,85,1056,107,1152,106.7C1248,107,1344,85,1392,74.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                            style="animation: wave 24s ease-in-out infinite"
                        ></path>
                    </svg>
                </div>

                <style>
                    @keyframes wave {
                        0%,
                        100% {
                            transform: translateX(0%);
                        }
                        50% {
                            transform: translateX(5%);
                        }
                    }
                </style>
            </section>
            {{-- Product Section --}}
            <section>
                <div>
                    aaa
                </div>
            </section>
        </main>
    </body>
</html>
