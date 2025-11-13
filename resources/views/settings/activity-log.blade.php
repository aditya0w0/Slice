<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Log Aktivitas - Slice</title>
        @vite(["resources/css/app.css"])
    </head>
    <body class="bg-gray-50">
        <div class="mx-auto max-w-4xl p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center gap-4">
                <a
                    href="{{ route("settings.security") }}"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 transition hover:bg-gray-200"
                >
                    <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        ></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas</h1>
                    <p class="mt-1 text-sm text-gray-500">Riwayat aktivitas akun Anda</p>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-6 flex gap-2 rounded-3xl bg-white p-2 shadow-sm">
                <button
                    class="flex-1 rounded-xl bg-blue-500 py-3 font-semibold text-white transition active:scale-[0.98]"
                >
                    Semua
                </button>
                <button
                    class="flex-1 rounded-xl py-3 font-semibold text-gray-600 transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Login
                </button>
                <button
                    class="flex-1 rounded-xl py-3 font-semibold text-gray-600 transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Keamanan
                </button>
                <button
                    class="flex-1 rounded-xl py-3 font-semibold text-gray-600 transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Transaksi
                </button>
            </div>

            <!-- Today -->
            <div class="mb-6">
                <h2 class="mb-3 px-2 text-sm font-bold tracking-wide text-gray-500 uppercase">Hari Ini</h2>

                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <!-- Activity 1 - Current Login -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100">
                                <svg
                                    class="h-6 w-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-gray-900">Login Berhasil</p>
                                    <span
                                        class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700"
                                    >
                                        Sesi Aktif
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">12 Nov 2025, 10:45 WIB</p>
                                <div class="mt-3 flex items-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                        <span>Windows • Chrome 120</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            ></path>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            ></path>
                                        </svg>
                                        <span>Jakarta, Indonesia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity 2 - Profile Update -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                                <svg
                                    class="h-6 w-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Profil Diperbarui</p>
                                <p class="mt-1 text-sm text-gray-500">12 Nov 2025, 09:30 WIB</p>
                                <p class="mt-2 text-sm text-gray-600">Anda mengubah nomor telepon dan alamat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity 3 - Password Change -->
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-100">
                                <svg
                                    class="h-6 w-6 text-yellow-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Password Diubah</p>
                                <p class="mt-1 text-sm text-gray-500">12 Nov 2025, 08:15 WIB</p>
                                <p class="mt-2 text-sm text-gray-600">Password akun berhasil diperbarui</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yesterday -->
            <div class="mb-6">
                <h2 class="mb-3 px-2 text-sm font-bold tracking-wide text-gray-500 uppercase">Kemarin</h2>

                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <!-- Activity 4 - Payment -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100">
                                <svg
                                    class="h-6 w-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Pembayaran Berhasil</p>
                                <p class="mt-1 text-sm text-gray-500">11 Nov 2025, 16:20 WIB</p>
                                <p class="mt-2 text-sm text-gray-600">iPad Air M2 - Cicilan ke-3 • Rp 2.500.000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity 5 - 2FA Enabled -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-100">
                                <svg
                                    class="h-6 w-6 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">2FA Diaktifkan</p>
                                <p class="mt-1 text-sm text-gray-500">11 Nov 2025, 14:10 WIB</p>
                                <p class="mt-2 text-sm text-gray-600">Autentikasi dua faktor berhasil diaktifkan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity 6 - Login -->
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100">
                                <svg
                                    class="h-6 w-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Login Berhasil</p>
                                <p class="mt-1 text-sm text-gray-500">11 Nov 2025, 08:30 WIB</p>
                                <div class="mt-3 flex items-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                        <span>iPhone 15 Pro • Safari 17</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            ></path>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            ></path>
                                        </svg>
                                        <span>Bandung, Indonesia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Week -->
            <div class="mb-6">
                <h2 class="mb-3 px-2 text-sm font-bold tracking-wide text-gray-500 uppercase">Minggu Lalu</h2>

                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <!-- Activity 7 - Failed Login -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Percobaan Login Gagal</p>
                                <p class="mt-1 text-sm text-gray-500">8 Nov 2025, 03:15 WIB</p>
                                <div class="mt-3 flex items-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                        <span>Unknown Device</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            ></path>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            ></path>
                                        </svg>
                                        <span>Singapore</span>
                                    </div>
                                </div>
                                <div class="mt-3 rounded-xl bg-red-50 p-3">
                                    <p class="text-sm font-medium text-red-700">
                                        ⚠️ Login mencurigakan terdeteksi. Apakah ini Anda?
                                    </p>
                                    <button class="mt-2 text-sm font-semibold text-red-600 hover:text-red-700">
                                        Laporkan Aktivitas Mencurigakan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity 8 - Order -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                                <svg
                                    class="h-6 w-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Pesanan Dibuat</p>
                                <p class="mt-1 text-sm text-gray-500">5 Nov 2025, 13:45 WIB</p>
                                <p class="mt-2 text-sm text-gray-600">
                                    AirPods Pro 2 ditambahkan ke keranjang dan dibayar
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity 9 - Logout All -->
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-100">
                                <svg
                                    class="h-6 w-6 text-orange-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    ></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Keluar dari Semua Perangkat</p>
                                <p class="mt-1 text-sm text-gray-500">4 Nov 2025, 22:00 WIB</p>
                                <p class="mt-2 text-sm text-gray-600">
                                    Anda keluar dari semua sesi aktif di perangkat lain
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Load More -->
            <div class="text-center">
                <button
                    class="rounded-xl bg-white px-6 py-3 font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Muat Lebih Banyak
                </button>
            </div>
        </div>
    </body>
</html>
