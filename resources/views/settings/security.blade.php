<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Keamanan & Login - Slice</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen p-4 md:p-8">
            <div class="mx-auto max-w-3xl">
                <!-- Header -->
                <div class="mb-8">
                    <a href="/settings" class="inline-flex items-center text-gray-600 transition hover:text-gray-900">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                        Pengaturan
                    </a>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Keamanan & Login</h1>
                </div>

                <!-- Success Message -->
                @if (session("success"))
                    <div class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                        <p class="font-medium text-green-800">{{ session("success") }}</p>
                    </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                        <div class="flex items-start gap-3">
                            <svg
                                class="mt-0.5 h-5 w-5 text-red-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="font-medium text-red-800">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Change Password Card -->
                <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Ubah Password</h3>
                        <p class="mt-1 text-sm text-gray-500">Pastikan menggunakan password yang kuat</p>
                    </div>

                    <form method="POST" action="{{ route("settings.password.update") }}">
                        @csrf
                        @method("PUT")

                        <!-- Current Password -->
                        <div class="border-b border-gray-100 p-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Password Saat Ini</label>
                            <input
                                type="password"
                                name="current_password"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Masukkan password saat ini"
                                required
                            />
                        </div>

                        <!-- New Password -->
                        <div class="border-b border-gray-100 p-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Password Baru</label>
                            <input
                                type="password"
                                name="new_password"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Minimal 8 karakter"
                                required
                            />
                        </div>

                        <!-- Confirm Password -->
                        <div class="border-b border-gray-100 p-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input
                                type="password"
                                name="new_password_confirmation"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Ketik ulang password baru"
                                required
                            />
                        </div>

                        <!-- Save Button -->
                        <div class="p-6">
                            <button
                                type="submit"
                                class="w-full rounded-xl bg-blue-500 py-3 font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                            >
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Two-Factor Authentication Card -->
                <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Autentikasi Dua Faktor</h3>
                                <p class="mt-1 text-sm text-gray-500">Lindungi akun dengan verifikasi tambahan</p>
                            </div>
                            <form method="POST" action="{{ route("settings.2fa.toggle") }}" id="2fa-form">
                                @csrf
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="enabled"
                                        {{ Auth::user()->two_factor_enabled ? "checked" : "" }}
                                        onchange="document.getElementById('2fa-form').submit()"
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </form>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                <svg
                                    class="h-5 w-5 text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">
                                    Kami akan mengirim kode verifikasi ke email Anda setiap kali ada login dari
                                    perangkat baru.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login History Card -->
                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Riwayat Login</h3>
                                <p class="mt-1 text-sm text-gray-500">Perangkat yang pernah mengakses akun Anda</p>
                            </div>
                            <a
                                href="{{ route("settings.activity.log") }}"
                                class="text-sm font-medium text-blue-600 transition hover:text-blue-700"
                            >
                                Lihat Semua
                            </a>
                        </div>
                    </div>

                    <!-- Login Session Item -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gray-100"
                                >
                                    <svg
                                        class="h-6 w-6 text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Windows • Chrome</p>
                                    <p class="mt-1 text-sm text-gray-500">Jakarta, Indonesia</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700"
                                        >
                                            <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            Perangkat Ini
                                        </span>
                                        <span class="text-xs text-gray-500">5 menit yang lalu</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Another Session -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gray-100"
                                >
                                    <svg
                                        class="h-6 w-6 text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">iPhone 15 • Safari</p>
                                    <p class="mt-1 text-sm text-gray-500">Jakarta, Indonesia</p>
                                    <p class="mt-2 text-xs text-gray-500">2 hari yang lalu</p>
                                </div>
                            </div>
                            <button class="text-sm font-medium text-red-600 transition hover:text-red-700">
                                Keluar
                            </button>
                        </div>
                    </div>

                    <!-- Logout All Button -->
                    <div class="p-6">
                        <form
                            method="POST"
                            action="{{ route("settings.logout.all") }}"
                            onsubmit="return confirm('Yakin ingin keluar dari semua perangkat?')"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="w-full rounded-xl border-2 border-red-200 py-3 font-semibold text-red-600 transition hover:bg-red-50 active:scale-[0.98]"
                            >
                                Keluar dari Semua Perangkat
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
