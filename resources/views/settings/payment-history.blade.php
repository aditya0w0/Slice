<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Riwayat Pembayaran - Slice</title>
        @vite(["resources/css/app.css"])
    </head>
    <body class="bg-gray-50">
        <div class="mx-auto max-w-4xl p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center gap-4">
                <a
                    href="{{ route("settings.payment") }}"
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
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Pembayaran</h1>
                    <p class="mt-1 text-sm text-gray-500">Semua transaksi pembayaran Anda</p>
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
                    Berhasil
                </button>
                <button
                    class="flex-1 rounded-xl py-3 font-semibold text-gray-600 transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Pending
                </button>
                <button
                    class="flex-1 rounded-xl py-3 font-semibold text-gray-600 transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Gagal
                </button>
            </div>

            <!-- This Month -->
            <div class="mb-6">
                <h2 class="mb-3 px-2 text-sm font-bold tracking-wide text-gray-500 uppercase">November 2025</h2>

                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <!-- Transaction 1 -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100"
                                >
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
                                <div>
                                    <p class="font-semibold text-gray-900">iPad Air M2 - Cicilan ke-3</p>
                                    <p class="mt-1 text-sm text-gray-500">8 Nov 2025, 14:23</p>
                                    <p class="text-sm text-gray-500">Visa •••• 4242</p>
                                    <span
                                        class="mt-2 inline-block rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700"
                                    >
                                        Berhasil
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rp 2.500.000</p>
                                <button class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction 2 -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100"
                                >
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
                                <div>
                                    <p class="font-semibold text-gray-900">AirPods Pro 2 - Lunas</p>
                                    <p class="mt-1 text-sm text-gray-500">5 Nov 2025, 09:15</p>
                                    <p class="text-sm text-gray-500">Mastercard •••• 8888</p>
                                    <span
                                        class="mt-2 inline-block rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700"
                                    >
                                        Berhasil
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rp 3.999.000</p>
                                <button class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction 3 - Pending -->
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-100"
                                >
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
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">iPhone 15 Pro - Cicilan ke-1</p>
                                    <p class="mt-1 text-sm text-gray-500">2 Nov 2025, 16:45</p>
                                    <p class="text-sm text-gray-500">Visa •••• 4242</p>
                                    <span
                                        class="mt-2 inline-block rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700"
                                    >
                                        Menunggu Konfirmasi
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rp 3.500.000</p>
                                <button class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Month -->
            <div class="mb-6">
                <h2 class="mb-3 px-2 text-sm font-bold tracking-wide text-gray-500 uppercase">Oktober 2025</h2>

                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <!-- Transaction 4 -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100"
                                >
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
                                <div>
                                    <p class="font-semibold text-gray-900">MacBook Air M3 - DP</p>
                                    <p class="mt-1 text-sm text-gray-500">28 Okt 2025, 11:30</p>
                                    <p class="text-sm text-gray-500">Mastercard •••• 8888</p>
                                    <span
                                        class="mt-2 inline-block rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700"
                                    >
                                        Berhasil
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rp 5.000.000</p>
                                <button class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction 5 - Failed -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100">
                                    <svg
                                        class="h-6 w-6 text-red-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Apple Watch Series 9 - DP</p>
                                    <p class="mt-1 text-sm text-gray-500">15 Okt 2025, 18:20</p>
                                    <p class="text-sm text-gray-500">Visa •••• 4242</p>
                                    <span
                                        class="mt-2 inline-block rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700"
                                    >
                                        Gagal - Saldo Tidak Cukup
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">Rp 2.000.000</p>
                                <button class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Coba Lagi
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction 6 -->
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100"
                                >
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
                                <div>
                                    <p class="font-semibold text-gray-900">iPad Air M2 - Cicilan ke-2</p>
                                    <p class="mt-1 text-sm text-gray-500">8 Okt 2025, 14:23</p>
                                    <p class="text-sm text-gray-500">Visa •••• 4242</p>
                                    <span
                                        class="mt-2 inline-block rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700"
                                    >
                                        Berhasil
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rp 2.500.000</p>
                                <button class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Detail
                                </button>
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
