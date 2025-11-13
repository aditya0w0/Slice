<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Pengaturan Akun - Slice</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen p-4 md:p-8">
            <div class="mx-auto max-w-3xl">
                <!-- Header -->
                <div class="mb-8">
                    <a href="/dashboard" class="inline-flex items-center text-gray-600 transition hover:text-gray-900">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                        Kembali
                    </a>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Pengaturan</h1>
                </div>

                <!-- Single Card with All Settings - No Spacing (Apple Style) -->
                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <!-- Profile Information -->
                    <a
                        href="{{ route("settings.profile") }}"
                        class="block border-b border-gray-100 p-6 transition hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100">
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
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Informasi Profil</h3>
                                    <p class="text-sm text-gray-500">Perbarui nama, email, dan foto profil Anda.</p>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>

                    <!-- Security & Login -->
                    <a
                        href="{{ route("settings.security") }}"
                        class="block border-b border-gray-100 p-6 transition hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100">
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
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Keamanan & Login</h3>
                                    <p class="text-sm text-gray-500">Ubah password, atur 2FA, dan lihat sesi login.</p>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>

                    <!-- KYC Verification -->
                    <a
                        href="{{ route("kyc.create") }}"
                        class="block border-b border-gray-100 p-6 transition hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="{{ auth()->user()->is_trusted ? "bg-green-100" : "bg-blue-100" }} flex h-11 w-11 items-center justify-center rounded-full"
                                >
                                    <svg
                                        class="{{ auth()->user()->is_trusted ? "text-green-600" : "text-blue-600" }} h-5 w-5"
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
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-semibold text-gray-900">
                                            Verifikasi Identitas (KYC)
                                        </h3>
                                        @if (auth()->user()->is_trusted)
                                            <span
                                                class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700"
                                            >
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span
                                                class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-700"
                                            >
                                                Belum Verifikasi
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        @if (auth()->user()->is_trusted)
                                            Identitas Anda telah diverifikasi.
                                        @else
                                                Verifikasi identitas untuk akses penuh dan limit lebih tinggi.
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>

                    <!-- Notification Preferences -->
                    <a
                        href="{{ route("settings.notifications") }}"
                        class="block border-b border-gray-100 p-6 transition hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100">
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
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Preferensi Notifikasi</h3>
                                    <p class="text-sm text-gray-500">Atur notifikasi email (promo, pengingat, dll).</p>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>

                    <!-- Data & Privacy -->
                    <a
                        href="{{ route("settings.privacy") }}"
                        class="block border-b border-gray-100 p-6 transition hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100">
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
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Data & Privasi</h3>
                                    <p class="text-sm text-gray-500">Download arsip data Anda atau hapus akun Anda.</p>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>

                    <!-- Payment Methods -->
                    <a
                        href="{{ route("settings.payment") }}"
                        class="block border-b border-gray-100 p-6 transition hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100">
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
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Metode Pembayaran</h3>
                                    <p class="text-sm text-gray-500">Kelola kartu dan metode pembayaran Anda.</p>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>

                    <!-- Subscription Plan -->
                    <a
                        href="{{ route("settings.subscription") }}"
                        class="block border-b border-gray-100 p-6 transition hover:bg-gray-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100">
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
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Paket Berlangganan</h3>
                                    <p class="text-sm text-gray-500">Upgrade atau kelola paket AI Anda.</p>
                                </div>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                    </a>

                    <!-- Logout -->
                    <div class="p-6">
                        <form method="POST" action="{{ route("logout") }}">
                            @csrf
                            <button
                                type="submit"
                                class="w-full py-2 text-center font-medium text-red-600 transition hover:text-red-700"
                            >
                                Keluar dari Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
