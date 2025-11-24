<header class="{{ request()->routeIs('devices') || request()->routeIs('devices.model') ? 'relative' : 'fixed w-full z-50 top-0 start-0 border-b border-white/5 bg-[#0B0C10]/80 backdrop-blur-xl transition-all duration-300' }}">
    {{-- AlpineJS required for dropdowns --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-12 items-center justify-between">

            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-linear-to-br from-blue-600 to-teal-500 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">Slice<span class="text-blue-500">.</span></span>
                </a>

                @if (!Auth::check())
                    @unless(request()->routeIs('devices') || request()->routeIs('devices.model'))
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="{{ route('devices') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Catalog</a>
                        <a href="#pricing" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Pricing</a>
                        <a href="#enterprise" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Enterprise</a>
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

                <div class="hidden md:flex flex-1 justify-center px-8">
                    @unless(request()->routeIs('admin.*'))
                        <form action="{{ route('devices') }}" method="GET" class="w-full max-w-md">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-500 group-focus-within:text-blue-500 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="q"
                                    class="block w-full rounded-xl border border-white/5 bg-white/5 py-2 pl-10 pr-3 text-sm text-white placeholder-slate-500 focus:border-blue-500/50 focus:bg-white/10 focus:ring-0 transition-all duration-300 hover:bg-white/10"
                                    placeholder="Search ecosystem..."
                                >
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <kbd class="hidden sm:inline-flex items-center rounded border border-white/10 px-2 font-sans text-xs font-medium text-slate-500">⌘K</kbd>
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
                            class="flex items-center gap-3 rounded-full border border-transparent p-1 pr-3 hover:bg-white/5 transition-colors focus:outline-none"
                        >
                            <div class="relative">
                                <img
                                    class="h-8 w-8 rounded-full object-cover ring-2 ring-white/10 {{ $user->isTrustedUser() ? 'ring-blue-500 shadow-[0_0_12px_rgba(59,130,246,0.6)]' : '' }}"
                                    src="{{ $avatar }}"
                                    alt="{{ $user->name }}"
                                >
                                @if($user->isTrustedUser())
                                    <div class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-blue-500 ring-2 ring-[#0B0C10] flex items-center justify-center">
                                        <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                @endif
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-slate-300 group-hover:text-white transition-colors">{{ $user->name }}</span>
                            <svg class="h-4 w-4 text-slate-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                            class="absolute right-0 mt-2 w-60 origin-top-right rounded-xl border border-white/10 bg-[#121217] shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50 backdrop-blur-xl"
                            style="display: none;"
                        >
                            <div class="p-2 space-y-1">
                                <div class="px-3 py-2 border-b border-white/5 mb-2">
                                    <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                </div>

                                <a href="{{ route('dashboard') }}" class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <svg class="mr-3 h-4 w-4 text-slate-500 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Overview
                                </a>

                                <a href="{{ route('notifications.index') }}" class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <div class="relative mr-3">
                                        <svg class="h-4 w-4 text-slate-500 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z" />
                                        </svg>
                                        @if(isset($unreadCount) && $unreadCount > 0)
                                            <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                                        @endif
                                    </div>
                                    Notifications
                                </a>

                                <a href="{{ route('cart.index') }}" class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <div class="relative mr-3">
                                        <svg class="h-4 w-4 text-slate-500 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    My Cart
                                </a>

                                @if ($user->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-purple-300 hover:bg-purple-500/10 hover:text-purple-200 transition-colors">
                                    <svg class="mr-3 h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Admin Console
                                </a>
                                <a href="{{ route('admin.notifications.index') }}" class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-purple-300 hover:bg-purple-500/10 hover:text-purple-200 transition-colors">
                                    <svg class="mr-3 h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.868 12.683A17.925 17.925 0 0112 21c7.962 0 12-1.21 12-2.683m-12 2.683a17.925 17.925 0 01-7.132-8.317M12 21c4.411 0 8-4.03 8-9s-3.589-9-8-9-8 4.03-8 9a9.06 9.06 0 001.832 5.683L4 21l4.868-8.317z" />
                                    </svg>
                                    Notifications
                                </a>
                                @endif

                                <div class="border-t border-white/5 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="group flex w-full items-center rounded-lg px-3 py-2 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                                        <svg class="mr-3 h-4 w-4 opacity-50 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-bold text-black hover:bg-slate-200 transition-colors shadow-lg shadow-white/10">
                        Get Started
                    </a>
                </div>
            @endif

        </div>
    </div>
</header>
