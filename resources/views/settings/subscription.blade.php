<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Paket Berlangganan - Slice</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen p-4 md:p-8">
            <div class="mx-auto max-w-5xl">
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
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Paket Berlangganan</h1>
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

                <!-- Current Plan Card -->
                <div class="mb-8 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Paket Anda Saat Ini</h3>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="{{ Auth::user()->subscription_plan === "premium" ? "bg-purple-100" : (Auth::user()->subscription_plan === "plus" ? "bg-blue-100" : "bg-gray-100") }} flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl"
                                >
                                    <svg
                                        class="{{ Auth::user()->subscription_plan === "premium" ? "text-purple-600" : (Auth::user()->subscription_plan === "plus" ? "text-blue-600" : "text-gray-600") }} h-8 w-8"
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
                                    <h4 class="text-xl font-bold text-gray-900">
                                        @if (Auth::user()->subscription_plan === "premium")
                                            Slice Premium
                                        @elseif (Auth::user()->subscription_plan === "plus")
                                            Slice Plus
                                        @else
                                            Slice Basic
                                        @endif
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if (Auth::user()->subscription_expires_at)
                                            Berlaku hingga {{ Auth::user()->subscription_expires_at->format("d F Y") }}
                                        @else
                                            Gratis Selamanya
                                        @endif
                                    </p>
                                    <div class="mt-3 flex items-center gap-2">
                                        <span
                                            class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700"
                                        >
                                            Aktif
                                        </span>
                                        @if (Auth::user()->subscription_plan !== "basic")
                                            <span class="text-sm text-gray-500">•</span>
                                            <span class="text-sm text-gray-600">Perpanjangan Otomatis</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-gray-900">
                                    @if (Auth::user()->subscription_plan === "premium")
                                        Rp 99K
                                    @elseif (Auth::user()->subscription_plan === "plus")
                                        Rp 49K
                                    @else
                                        Gratis
                                    @endif
                                </p>
                                @if (Auth::user()->subscription_plan !== "basic")
                                    <p class="mt-1 text-sm text-gray-500">per bulan</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-100 pt-6">
                            <h5 class="mb-3 font-semibold text-gray-900">Benefit Anda:</h5>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                @if (Auth::user()->subscription_plan === "basic")
                                    <div class="flex items-center gap-2">
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
                                                d="M5 13l4 4L19 7"
                                            ></path>
                                        </svg>
                                        <span class="text-sm text-gray-700">Cicilan hingga 3 bulan</span>
                                    </div>
                                    <div class="flex items-center gap-2">
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
                                                d="M5 13l4 4L19 7"
                                            ></path>
                                        </svg>
                                        <span class="text-sm text-gray-700">Akses katalog produk</span>
                                    </div>
                                @elseif (Auth::user()->subscription_plan === "plus")
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-blue-600"
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
                                        <span class="text-sm text-gray-700">Cicilan hingga 12 bulan</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-blue-600"
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
                                        <span class="text-sm text-gray-700">Gratis ongkir unlimited</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-blue-600"
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
                                        <span class="text-sm text-gray-700">Early access produk baru</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-blue-600"
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
                                        <span class="text-sm text-gray-700">Cashback hingga 5%</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-purple-600"
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
                                        <span class="text-sm text-gray-700">Cicilan hingga 24 bulan</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-purple-600"
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
                                        <span class="text-sm text-gray-700">Priority customer support</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-purple-600"
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
                                        <span class="text-sm text-gray-700">Extended warranty</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg
                                            class="h-5 w-5 text-purple-600"
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
                                        <span class="text-sm text-gray-700">Cashback hingga 10%</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button
                                class="flex-1 rounded-xl bg-gray-100 py-3 font-semibold text-gray-700 transition hover:bg-gray-200 active:scale-[0.98]"
                            >
                                Kelola Langganan
                            </button>
                            <button
                                class="flex-1 rounded-xl bg-blue-500 py-3 font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                            >
                                Upgrade Paket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Available Plans -->
                <div class="mb-4">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900">Paket Tersedia</h2>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Basic Plan -->
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                        <div class="border-b border-gray-100 p-6">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
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
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Slice Basic</h3>
                            <div class="mt-3">
                                <span class="text-3xl font-bold text-gray-900">Gratis</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <ul class="space-y-3">
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-gray-400"
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
                                    <span class="text-sm text-gray-600">Cicilan hingga 3 bulan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-gray-400"
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
                                    <span class="text-sm text-gray-600">Akses katalog produk</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-gray-400"
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
                                    <span class="text-sm text-gray-600">Customer support standar</span>
                                </li>
                            </ul>

                            <button
                                class="mt-6 w-full rounded-xl bg-gray-100 py-3 font-semibold text-gray-700 transition hover:bg-gray-200 active:scale-[0.98]"
                            >
                                Paket Aktif
                            </button>
                        </div>
                    </div>

                    <!-- Plus Plan (Current) -->
                    <div class="relative overflow-hidden rounded-3xl border-2 border-blue-500 bg-white shadow-sm">
                        <div
                            class="absolute top-0 right-0 rounded-tr-2xl rounded-bl-xl bg-blue-500 px-4 py-1 text-xs font-bold text-white"
                        >
                            POPULER
                        </div>

                        <div class="border-b border-gray-100 p-6">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
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
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Slice Plus</h3>
                            <div class="mt-3">
                                <span class="text-3xl font-bold text-gray-900">Rp 49K</span>
                                <span class="text-gray-500">/bulan</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <ul class="space-y-3">
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
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
                                    <span class="text-sm text-gray-600">Cicilan hingga 12 bulan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
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
                                    <span class="text-sm text-gray-600">Gratis ongkir unlimited</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
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
                                    <span class="text-sm text-gray-600">Early access produk baru</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
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
                                    <span class="text-sm text-gray-600">Cashback hingga 5%</span>
                                </li>
                            </ul>

                            <button
                                class="mt-6 w-full rounded-xl bg-blue-500 py-3 font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                            >
                                Paket Saat Ini
                            </button>
                        </div>
                    </div>

                    <!-- Premium Plan -->
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                        <div class="border-b border-gray-100 p-6">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">
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
                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Slice Premium</h3>
                            <div class="mt-3">
                                <span class="text-3xl font-bold text-gray-900">Rp 99K</span>
                                <span class="text-gray-500">/bulan</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <ul class="space-y-3">
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-purple-600"
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
                                    <span class="text-sm text-gray-600">Cicilan hingga 24 bulan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-purple-600"
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
                                    <span class="text-sm text-gray-600">Priority customer support</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-purple-600"
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
                                    <span class="text-sm text-gray-600">Extended warranty</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-purple-600"
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
                                    <span class="text-sm text-gray-600">Cashback hingga 10%</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg
                                        class="mt-0.5 h-5 w-5 shrink-0 text-purple-600"
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
                                    <span class="text-sm text-gray-600">Exclusive deals & events</span>
                                </li>
                            </ul>

                            <button
                                class="mt-6 w-full rounded-xl bg-purple-500 py-3 font-semibold text-white transition hover:bg-purple-600 active:scale-[0.98]"
                            >
                                Upgrade
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Billing Info Card -->
                <div class="mt-8 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Tagihan</h3>
                    </div>

                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-gray-600">Tanggal Tagihan Berikutnya</span>
                            <span class="font-semibold text-gray-900">15 Februari 2025</span>
                        </div>
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-semibold text-gray-900">Visa •••• 4242</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-gray-900">Rp 49.000</span>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <button
                            class="w-full rounded-xl border-2 border-red-200 py-3 font-semibold text-red-600 transition hover:bg-red-50 active:scale-[0.98]"
                        >
                            Batalkan Langganan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
