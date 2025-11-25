<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Create Account - Slice Enterprise</title>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />

        @vite("resources/css/app.css")

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass-panel {
                background: rgba(18, 18, 23, 0.8);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .input-dark {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: white;
            }
            .input-dark:focus {
                border-color: #3b82f6;
                background: rgba(255, 255, 255, 0.05);
                outline: none;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
            }
        </style>
    </head>
    <body class="h-full bg-[#0B0C10] text-slate-300 antialiased selection:bg-blue-500/30 selection:text-blue-100">
        <div class="flex min-h-full">
            <div
                class="relative z-10 flex w-full flex-1 flex-col justify-center bg-[#0B0C10] px-4 py-12 sm:px-6 lg:w-[480px] lg:flex-none lg:px-20 xl:w-[560px] xl:px-24"
            >
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div class="mb-10">
                        <a href="/" class="group flex items-center gap-3">
                            <img src="{{ asset("images/logo.svg") }}" alt="Logo" class="h-12 w-12" />
                            <span class="text-2xl font-bold tracking-tight text-white">
                                Slice
                                <span class="text-blue-500">.</span>
                            </span>
                        </a>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-white">Create your account</h2>
                        <p class="mt-2 text-sm text-slate-400">Start deploying your Apple infrastructure in minutes.</p>
                    </div>

                    <div class="mt-10">
                        <form action="{{ route("register.post") }}" method="POST" class="space-y-6" novalidate>
                            @csrf

                            @if (session("status"))
                                <div class="rounded-lg border border-green-500/20 bg-green-500/10 p-4">
                                    <p class="text-sm font-medium text-green-400">{{ session("status") }}</p>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="rounded-lg border border-red-500/20 bg-red-500/10 p-4">
                                    <p class="text-sm font-medium text-red-400">{{ $errors->first() }}</p>
                                </div>
                            @endif

                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-300">Full name</label>
                                <div class="mt-2">
                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        required
                                        class="input-dark block w-full rounded-xl px-4 py-3 text-sm placeholder-slate-500"
                                        placeholder="John Doe"
                                        value="{{ old("name") }}"
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-300">Work email</label>
                                <div class="mt-2">
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        required
                                        class="input-dark block w-full rounded-xl px-4 py-3 text-sm placeholder-slate-500"
                                        placeholder="name@company.com"
                                        value="{{ old("email") }}"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-300">
                                        Password
                                    </label>
                                    <div class="mt-2">
                                        <input
                                            id="password"
                                            name="password"
                                            type="password"
                                            required
                                            class="input-dark block w-full rounded-xl px-4 py-3 text-sm placeholder-slate-500"
                                            placeholder="••••••••"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300">
                                        Confirm
                                    </label>
                                    <div class="mt-2">
                                        <input
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            required
                                            class="input-dark block w-full rounded-xl px-4 py-3 text-sm placeholder-slate-500"
                                            placeholder="••••••••"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="flex w-full justify-center rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition-all hover:scale-[1.02] hover:shadow-blue-500/40"
                                >
                                    Create Account
                                </button>
                            </div>
                        </form>

                        <div class="mt-8">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-white/10"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="bg-[#0B0C10] px-2 text-slate-500">Or join with</span>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-2 gap-4">
                                <a
                                    href="#"
                                    class="flex w-full items-center justify-center gap-3 rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm font-medium text-white transition-colors hover:bg-white/10"
                                >
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                            fill="#4285F4"
                                        />
                                        <path
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                            fill="#34A853"
                                        />
                                        <path
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                            fill="#FBBC05"
                                        />
                                        <path
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                            fill="#EA4335"
                                        />
                                    </svg>
                                    <span class="sr-only">Google</span>
                                </a>

                                <a
                                    href="#"
                                    class="flex w-full items-center justify-center gap-3 rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm font-medium text-white transition-colors hover:bg-white/10"
                                >
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701"
                                        />
                                    </svg>
                                    <span class="sr-only">Apple</span>
                                </a>
                            </div>
                        </div>

                        <p class="mt-8 text-center text-sm text-slate-500">
                            Already have an account?
                            <a href="{{ route("login") }}" class="font-semibold text-blue-500 hover:text-blue-400">
                                Sign in
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative hidden w-0 flex-1 lg:block">
                <div class="absolute inset-0 overflow-hidden bg-[#0B0C10]">
                    <div
                        class="absolute top-0 right-0 h-[800px] w-[800px] translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-600/20 blur-[120px]"
                    ></div>
                    <div
                        class="absolute bottom-0 left-0 h-[600px] w-[600px] -translate-x-1/4 translate-y-1/2 rounded-full bg-teal-500/10 blur-[100px]"
                    ></div>

                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="relative w-[80%] max-w-2xl">
                            <div
                                class="glass-panel relative z-10 transform rounded-2xl p-8 shadow-2xl shadow-blue-900/40 transition-transform duration-1000 hover:scale-[1.02]"
                            >
                                <div class="mb-8 flex items-center justify-between border-b border-white/10 pb-6">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/20 text-blue-400"
                                        >
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-white">Registration Complete</div>
                                            <div class="text-xs text-slate-500">Ready to deploy</div>
                                        </div>
                                    </div>
                                    <div
                                        class="rounded-full border border-green-500/20 bg-green-500/10 px-3 py-1 text-xs font-bold text-green-400"
                                    >
                                        Active
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-white/5">
                                        <div
                                            class="h-full w-3/4 animate-pulse bg-gradient-to-r from-blue-500 to-teal-400"
                                        ></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-slate-500">
                                        <span>Provisioning Workspace...</span>
                                        <span>75%</span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="absolute -top-10 -right-10 h-32 w-32 animate-pulse rounded-full bg-purple-500/20 blur-2xl"
                            ></div>
                            <div
                                class="absolute -bottom-10 -left-10 h-32 w-32 animate-pulse rounded-full bg-blue-500/20 blur-2xl delay-700"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
