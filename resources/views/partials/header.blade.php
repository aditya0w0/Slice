<header
    class="{{ request()->routeIs("devices") || request()->routeIs("devices.model") ? "relative" : "fixed start-0 top-0 z-50 w-full border-b border-white/5 bg-[#0B0C10]/80 backdrop-blur-xl transition-all duration-300" }}"
>
    {{-- AlpineJS required for dropdowns --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-12 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route("dashboard") }}" class="group flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-blue-600 to-teal-500 shadow-lg shadow-blue-500/20 transition-all duration-300 group-hover:shadow-blue-500/40"
                    >
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">
                        Slice
                        <span class="text-blue-500">.</span>
                    </span>
                </a>

                @if (! Auth::check())
                    @unless (request()->routeIs("devices") || request()->routeIs("devices.model"))
                        <nav class="hidden items-center gap-6 md:flex">
                            <a
                                href="{{ route("devices") }}"
                                class="text-sm font-medium text-slate-400 transition-colors hover:text-white"
                            >
                                Catalog
                            </a>
                            <a
                                href="#pricing"
                                class="text-sm font-medium text-slate-400 transition-colors hover:text-white"
                            >
                                Pricing
                            </a>
                            <a
                                href="#enterprise"
                                class="text-sm font-medium text-slate-400 transition-colors hover:text-white"
                            >
                                Enterprise
                            </a>
                        </nav>
                    @endunless
                @endif
            </div>

            @if (Auth::check())
                @php
                    $user = Auth::user();
                    // Use uploaded profile photo if available, otherwise fallback to Gravatar
                    if ($user->profile_photo) {
                        $avatar = Storage::url($user->profile_photo);
                    } else {
                        $hash = $user && $user->email ? md5(strtolower(trim($user->email))) : "";
                        $avatar = $hash ? "https://www.gravatar.com/avatar/{$hash}?s=48&d=identicon" : "/images/default-avatar.svg";
                    }
                @endphp

                <div class="hidden flex-1 justify-center px-8 md:flex">
                    @unless (request()->routeIs("admin.*"))
                        <form action="{{ route("devices") }}" method="GET" class="w-full max-w-md">
                            <div class="group relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg
                                        class="h-4 w-4 text-slate-500 transition-colors duration-300 group-focus-within:text-blue-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="q"
                                    class="block w-full rounded-xl border border-white/5 bg-white/5 py-2 pr-3 pl-10 text-sm text-white placeholder-slate-500 transition-all duration-300 hover:bg-white/10 focus:border-blue-500/50 focus:bg-white/10 focus:ring-0"
                                    placeholder="Search ecosystem..."
                                />
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <kbd
                                        class="hidden items-center rounded border border-white/10 px-2 font-sans text-xs font-medium text-slate-500 sm:inline-flex"
                                    >
                                        ⌘K
                                    </kbd>
                                </div>
                            </div>
                        </form>
                    @endunless
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @click.away="open = false"
                            class="flex items-center gap-3 rounded-full border border-transparent p-1 pr-3 transition-colors hover:bg-white/5 focus:outline-none"
                        >
                            <div class="relative">
                                <img
                                    class="{{ $user->isTrustedUser() ? "shadow-[0_0_12px_rgba(59,130,246,0.6)] ring-blue-500" : "" }} h-8 w-8 rounded-full object-cover ring-2 ring-white/10"
                                    src="{{ $avatar }}"
                                    alt="{{ $user->name }}"
                                />
                                @if ($user->isTrustedUser())
                                    <div
                                        class="absolute -right-0.5 -bottom-0.5 flex h-3 w-3 items-center justify-center rounded-full bg-blue-500 ring-2 ring-[#0B0C10]"
                                    >
                                        <svg class="h-2 w-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <span
                                class="hidden text-sm font-medium text-slate-300 transition-colors group-hover:text-white sm:block"
                            >
                                {{ $user->name }}
                            </span>
                            <svg
                                class="h-4 w-4 text-slate-500 transition-transform duration-200"
                                :class="{'rotate-180': open}"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-transition:enter="transition duration-100 ease-out"
                            x-transition:enter-start="-translate-y-2 scale-95 transform opacity-0"
                            x-transition:enter-end="translate-y-0 scale-100 transform opacity-100"
                            x-transition:leave="transition duration-75 ease-in"
                            x-transition:leave-start="translate-y-0 scale-100 transform opacity-100"
                            x-transition:leave-end="-translate-y-2 scale-95 transform opacity-0"
                            class="ring-opacity-5 absolute right-0 z-50 mt-2 w-60 origin-top-right rounded-xl border border-white/10 bg-[#121217] shadow-2xl ring-1 ring-black backdrop-blur-xl focus:outline-none"
                            style="display: none"
                        >
                            <div class="space-y-1 p-2">
                                <div class="mb-2 border-b border-white/5 px-3 py-2">
                                    <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                                </div>

                                <a
                                    href="{{ route("dashboard") }}"
                                    class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-slate-300 transition-colors hover:bg-white/5 hover:text-white"
                                >
                                    <svg
                                        class="mr-3 h-4 w-4 text-slate-500 transition-colors group-hover:text-blue-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                        />
                                    </svg>
                                    Overview
                                </a>

                                <a
                                    href="{{ route("notifications.index") }}"
                                    class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-slate-300 transition-colors hover:bg-white/5 hover:text-white"
                                >
                                    <div class="relative mr-3">
                                        <svg
                                            class="h-4 w-4 text-slate-500 transition-colors group-hover:text-blue-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"
                                            />
                                        </svg>
                                        @if (isset($unreadCount) && $unreadCount > 0)
                                            <span
                                                class="absolute -top-1 -right-1 h-2 w-2 rounded-full bg-red-500"
                                            ></span>
                                        @endif
                                    </div>
                                    Notifications
                                </a>

                                <a
                                    href="{{ route("cart.index") }}"
                                    class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-slate-300 transition-colors hover:bg-white/5 hover:text-white"
                                >
                                    <div class="relative mr-3">
                                        <svg
                                            class="h-4 w-4 text-slate-500 transition-colors group-hover:text-blue-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                            />
                                        </svg>
                                    </div>
                                    My Cart
                                </a>

                                @if ($user->is_admin)
                                    <a
                                        href="{{ route("admin.dashboard") }}"
                                        class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-purple-300 transition-colors hover:bg-purple-500/10 hover:text-purple-200"
                                    >
                                        <svg
                                            class="mr-3 h-4 w-4 text-purple-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />
                                        </svg>
                                        Admin Console
                                    </a>
                                    <a
                                        href="{{ route("admin.notifications.index") }}"
                                        class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-purple-300 transition-colors hover:bg-purple-500/10 hover:text-purple-200"
                                    >
                                        <svg
                                            class="mr-3 h-4 w-4 text-purple-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z"
                                            />
                                        </svg>
                                        Notifications
                                    </a>
                                @endif

                                <div class="my-1 border-t border-white/5"></div>

                                <form method="POST" action="{{ route("logout") }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-red-400 transition-colors hover:bg-red-500/10 hover:text-red-300"
                                    >
                                        <svg
                                            class="mr-3 h-4 w-4 opacity-50 transition-opacity group-hover:opacity-100"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
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
            @else
                <div class="flex items-center gap-4">
                    <a
                        href="{{ route("login") }}"
                        class="text-sm font-medium text-slate-300 transition-colors hover:text-white"
                    >
                        Log In
                    </a>
                    <a
                        href="{{ route("register") }}"
                        class="rounded-lg bg-white px-4 py-2 text-sm font-bold text-black shadow-lg shadow-white/10 transition-colors hover:bg-slate-200"
                    >
                        Get Started
                    </a>
                </div>
            @endif
        </div>
    </div>
</header>
