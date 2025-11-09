<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Sign Up - Apple Ecosystem Rent</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased">
        <div class="flex min-h-screen">
            <!-- Left Side - Image -->
            <div class="relative hidden w-0 flex-1 lg:block">
                <img
                    src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=1200&q=80"
                    alt="Signup Background"
                    class="absolute inset-0 h-full w-full object-cover"
                />
                <div class="absolute inset-0 bg-blue-600 opacity-10"></div>
            </div>

            <!-- Right Side - Form -->
            <div class="flex flex-1 items-center justify-center px-4 sm:px-6 lg:px-20 xl:px-24">
                <div class="w-full max-w-md">
                    <div class="mb-8">
                        <a href="/" class="text-2xl font-bold text-gray-900">Apple Rent</a>
                    </div>

                    <div class="mb-8">
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Create an account</h1>
                        <p class="text-gray-600">Start renting premium Apple devices today</p>
                    </div>

                    <form action="{{ route("register.post") }}" method="POST" class="space-y-6" novalidate>
                        @csrf

                        @if (session("status"))
                            <div class="rounded-md bg-green-50 p-3 text-sm text-green-700">
                                {{ session("status") }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="rounded-md bg-red-50 p-3 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="mb-2 block text-sm font-medium text-gray-700">
                                    First name
                                </label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                    placeholder="John"
                                    value="{{ old("first_name") }}"
                                />
                            </div>
                            <div>
                                <label for="last_name" class="mb-2 block text-sm font-medium text-gray-700">
                                    Last name
                                </label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                    placeholder="Doe"
                                    value="{{ old("last_name") }}"
                                />
                            </div>
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                                Email address
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="you@example.com"
                                value="{{ old("email") }}"
                            />
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="Create a strong password"
                            />
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
                                Confirm password
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="Confirm your password"
                            />
                        </div>

                        <div class="flex items-start">
                            <input
                                type="checkbox"
                                id="terms"
                                name="terms"
                                required
                                class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                I agree to the
                                <a href="#" class="text-blue-600 hover:text-blue-700">Terms of Service</a>
                                and
                                <a href="#" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700"
                        >
                            Create account
                        </button>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-gray-50 px-2 text-gray-500">Or continue with</span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <button
                                class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-3 transition hover:bg-gray-50"
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
                                <span class="ml-2 text-sm font-medium text-gray-700">Google</span>
                            </button>
                            <button
                                class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-3 transition hover:bg-gray-50"
                            >
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701"
                                    />
                                </svg>
                                <span class="ml-2 text-sm font-medium text-gray-700">Apple</span>
                            </button>
                        </div>
                    </div>

                    <p class="mt-8 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="/login" class="font-medium text-blue-600 hover:text-blue-700">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
