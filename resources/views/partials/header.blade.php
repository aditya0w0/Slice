<header class="mx-auto max-w-7xl px-6 py-3">
    {{-- header partial rendered --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @php
        $isDashboard = request()->routeIs('dashboard');
        $textColor = $isDashboard ? 'text-white' : 'text-gray-900';
        $textColorHover = $isDashboard ? 'text-gray-300' : 'text-gray-700';
        $bgHover = $isDashboard ? 'hover:bg-slate-700' : 'hover:bg-gray-100';
        $inputBg = $isDashboard ? 'bg-slate-800 border-slate-700 text-white placeholder-slate-400' : 'bg-white border-gray-200';
    @endphp
    <div class="flex items-center justify-between">
        <nav class="flex-1">
            @if (Auth::check())
                @php
                    $user = Auth::user();
                    $hash = $user && $user->email ? md5(strtolower(trim($user->email))) : "";
                    $avatar = $hash ? "https://www.gravatar.com/avatar/{$hash}?s=48&d=identicon" : "/images/default-avatar.svg";
                @endphp

                <div class="flex items-center justify-between">
                    <!-- Left: compact logo -->
                    <div class="flex items-center">
                        <a href="{{ route("dashboard") }}" class="text-lg font-semibold {{ $textColor }}">SLICE</a>
                    </div>

                    <!-- Center: subtle search (smaller, Apple-like) -->
                    <div class="mx-4 flex flex-1 justify-center">
                        <form action="{{ route("devices") }}" method="GET" class="w-full max-w-xl">
                            <div class="relative w-full max-w-md">
                                <input
                                    name="q"
                                    type="search"
                                    placeholder="Search products"
                                    class="w-full rounded-md border {{ $inputBg }} px-3 py-2 text-sm focus:outline-none"
                                />
                                <button
                                    type="submit"
                                    class="absolute top-1/2 right-2 inline-flex h-8 w-8 -translate-y-1/2 items-center justify-center {{ $isDashboard ? 'text-slate-400' : 'text-gray-500' }}"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                        <form action="{{ route("devices") }}" method="GET" class="w-full max-w-xl">
                            <div class="relative w-full max-w-md">
                                <input
                                    name="q"
                                    type="search"
                                    placeholder="Search products"
                                    class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm focus:outline-none"
                                />
                                <button
                                    type="submit"
                                    class="absolute top-1/2 right-2 inline-flex h-8 w-8 -translate-y-1/2 items-center justify-center text-gray-500"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right: avatar with trusted badge (minimal) -->
                    <div class="flex items-center gap-3">
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                @click.away="open = false"
                                class="inline-flex items-center gap-2 rounded-full px-2 py-1 text-sm {{ $textColorHover }} transition-colors {{ $bgHover }}"
                            >
                                <span class="max-w-32 truncate">{{ $user->name }}</span>

                                <!-- Avatar with optional trusted border -->
                                <div class="relative">
                                    @if ($user->isTrustedUser())
                                        <!-- Trusted badge animated ring -->
                                        <div
                                            class="absolute -inset-1 rounded-full bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 opacity-75 blur"
                                        ></div>
                                    @endif

                                    <div class="relative">
                                        <img
                                            src="{{ $avatar }}"
                                            alt="avatar"
                                            class="{{ $user->isTrustedUser() ? "ring-2 ring-blue-600" : "" }} h-8 w-8 rounded-full object-cover"
                                        />

                                        @if ($user->isTrustedUser())
                                            <!-- Verified checkmark badge -->
                                            <div
                                                class="absolute -right-0.5 -bottom-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-blue-600 ring-2 ring-white"
                                            >
                                                <svg
                                                    class="h-2.5 w-2.5 text-white"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <svg
                                    class="h-3 w-3 text-gray-500 transition-transform"
                                    :class="{ 'rotate-180': open }"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div
                                x-show="open"
                                x-transition:enter="transition duration-100 ease-out"
                                x-transition:enter-start="scale-95 opacity-0"
                                x-transition:enter-end="scale-100 opacity-100"
                                x-transition:leave="transition duration-75 ease-in"
                                x-transition:leave-start="scale-100 opacity-100"
                                x-transition:leave-end="scale-95 opacity-0"
                                class="ring-opacity-5 absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black focus:outline-none"
                                style="display: none"
                            >
                                <div class="py-1">
                                    <div class="border-b border-gray-100 px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            @if ($user->isTrustedUser())
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 px-1.5 py-0.5 text-xs font-semibold text-white"
                                                >
                                                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    Trusted
                                                </span>
                                            @endif
                                        </div>
                                        <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>

                                    <a
                                        href="{{ route("dashboard") }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                    >
                                        <svg
                                            class="mr-3 h-4 w-4 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                            />
                                        </svg>
                                        Dashboard
                                    </a>

                                    @if ($user->is_admin)
                                        <a
                                            href="{{ route("admin.dashboard") }}"
                                            class="flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50"
                                        >
                                            <svg
                                                class="mr-3 h-4 w-4 text-blue-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                />
                                            </svg>
                                            Admin Panel
                                        </a>
                                    @endif

                                    <a
                                        href="{{ route("cart.index") }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                    >
                                        <svg
                                            class="mr-3 h-4 w-4 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                        My Cart
                                    </a>
                                    <a
                                        href="{{ route("support") }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                                    >
                                        <svg
                                            class="mr-3 h-4 w-4 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                                            />
                                        </svg>
                                        Support
                                    </a>

                                    <div class="border-t border-gray-100"></div>

                                    <form method="POST" action="{{ route("logout") }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-50"
                                        >
                                            <svg
                                                class="mr-3 h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                                />
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="/" class="text-lg font-semibold text-gray-900">SLICE</a>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="/" class="text-sm text-gray-600 hover:text-gray-900">Home</a>
                        <a href="/devices" class="text-sm font-medium text-gray-900">Devices</a>
                        <a href="#contact" class="text-sm text-gray-600 hover:text-gray-900">Contact</a>
                        <a
                            href="{{ route("login") }}"
                            class="ml-2 rounded-full bg-blue-600 px-3 py-1 text-sm text-white"
                        >
                            Login
                        </a>
                    </div>
                </div>
            @endif
        </nav>
    </div>
</header>
