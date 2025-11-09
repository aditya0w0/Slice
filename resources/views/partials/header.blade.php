<header class="mx-auto max-w-7xl px-6 py-3">
    {{-- header partial rendered --}}
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
                        <a href="{{ route("dashboard") }}" class="text-lg font-semibold text-gray-900">SLICE</a>
                    </div>

                    <!-- Center: subtle search (smaller, Apple-like) -->
                    <div class="mx-4 flex flex-1 justify-center">
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

                    <!-- Right: icons + avatar (minimal) -->
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route("cart.index") }}"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full text-gray-700"
                            aria-label="Cart"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4"
                                />
                            </svg>
                        </a>

                        <a
                            href="#"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full text-gray-700"
                            aria-label="Favorites"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6.01 4.01 4 6.5 4c1.74 0 3.41.81 4.5 2.09C12.09 4.81 13.76 4 15.5 4 17.99 4 20 6.01 20 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                />
                            </svg>
                        </a>

                        <a
                            href="{{ route("dashboard") }}"
                            class="inline-flex items-center gap-2 rounded-full px-2 py-1 text-sm text-gray-700"
                        >
                            <span class="max-w-32 truncate">{{ $user->name }}</span>
                            <img src="{{ $avatar }}" alt="avatar" class="h-7 w-7 rounded-full object-cover" />
                        </a>
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
