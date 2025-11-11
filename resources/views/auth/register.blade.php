<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Create account - Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased">
        <div class="flex min-h-screen">
            <!-- Left Side - Form -->
            <div class="flex flex-1 items-center justify-center px-4 sm:px-6 lg:px-20 xl:px-24">
                <div class="w-full max-w-md">
                    <div class="mb-8">
                        <a href="/" class="text-2xl font-bold text-gray-900">SLICE</a>
                    </div>

                    <div class="mb-8">
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Create your account</h1>
                        <p class="text-gray-600">Quickly create an account to start renting Apple devices</p>
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

                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Full name</label>
                            <input
                                id="name"
                                name="name"
                                required
                                type="text"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="Your name"
                                value="{{ old("name") }}"
                            />
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                                Email address
                            </label>
                            <input
                                id="email"
                                name="email"
                                required
                                type="email"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="you@example.com"
                                value="{{ old("email") }}"
                            />
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password</label>
                            <input
                                id="password"
                                name="password"
                                required
                                type="password"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="Create a password"
                            />
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
                                Confirm password
                            </label>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                type="password"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="Repeat your password"
                            />
                        </div>

                        <div>
                            <button type="submit" class="w-full rounded-lg bg-black py-3 font-semibold text-white">
                                Create account
                            </button>
                        </div>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route("login") }}" class="font-medium text-blue-600 hover:text-blue-700">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="relative hidden w-0 flex-1 lg:block">
                <img
                    src="https://images.unsplash.com/photo-1518779578993-ec3579fee39f?w=1200&q=80"
                    alt="Signup Background"
                    class="absolute inset-0 h-full w-full object-cover"
                />
                <div class="absolute inset-0 bg-gradient-to-tr from-gray-900/5 to-white opacity-80"></div>
            </div>
        </div>
    </body>
</html>
