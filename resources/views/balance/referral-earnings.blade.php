<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Penghasilan Referral - Slice</title>
        @vite(["resources/css/app.css"])
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen px-4 py-8">
            <div class="mx-auto max-w-4xl">
                <!-- Header -->
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a
                            href="{{ route("balance") }}"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm transition-colors hover:bg-gray-50"
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
                        <h1 class="text-3xl font-bold text-gray-900">Penghasilan Referral</h1>
                    </div>
                </div>

                <!-- Total Earnings Card -->
                <div class="mb-8 rounded-3xl bg-gradient-to-r from-blue-500 to-blue-700 p-8 text-white shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="mb-2 text-blue-100">Total Penghasilan Referral</p>
                            <p class="text-5xl font-bold">Rp {{ number_format($totalEarnings, 0, ",", ".") }}</p>
                            <p class="mt-2 text-sm text-blue-100">
                                Dari {{ count($referrals) }} orang yang Anda referensikan
                            </p>
                        </div>
                        <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-white/10">
                            <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- How It Works -->
                <div class="mb-8 rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Cara Kerja Program Referral</h2>
                    <div class="space-y-3 text-gray-600">
                        <div class="flex gap-3">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100"
                            >
                                <span class="text-sm font-bold text-blue-600">1</span>
                            </div>
                            <p>Bagikan kode referral Anda kepada teman dan keluarga</p>
                        </div>
                        <div class="flex gap-3">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100"
                            >
                                <span class="text-sm font-bold text-blue-600">2</span>
                            </div>
                            <p>Mereka mendaftar menggunakan kode referral Anda</p>
                        </div>
                        <div class="flex gap-3">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100"
                            >
                                <span class="text-sm font-bold text-blue-600">3</span>
                            </div>
                            <p>
                                <strong>Anda mendapatkan komisi 1%</strong>
                                dari setiap rental yang mereka lakukan
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <div
                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100"
                            >
                                <span class="text-sm font-bold text-blue-600">4</span>
                            </div>
                            <p>Komisi otomatis masuk ke saldo Anda setelah rental selesai</p>
                        </div>
                    </div>
                </div>

                <!-- Referrals List -->
                <div class="mb-8 rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-xl font-bold text-gray-900">Daftar Referral Anda</h2>

                    @forelse ($referrals as $referral)
                        <div class="flex items-center justify-between border-b border-gray-100 py-4 last:border-0">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 font-bold text-white"
                                >
                                    {{ strtoupper(substr($referral->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $referral->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        Bergabung {{ $referral->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $referral->total_rentals }} rental</p>
                                <p class="text-sm font-medium text-blue-600">
                                    Rp {{ number_format($referral->earned_commission ?? 0, 0, ",", ".") }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <div
                                class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100"
                            >
                                <svg
                                    class="h-10 w-10 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    ></path>
                                </svg>
                            </div>
                            <p class="mb-2 text-gray-500">Belum ada referral</p>
                            <p class="text-sm text-gray-400">
                                Bagikan kode referral Anda untuk mulai mendapatkan komisi
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Recent Commissions -->
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-xl font-bold text-gray-900">Riwayat Komisi</h2>

                    @forelse ($commissionTransactions as $commission)
                        <div class="flex items-center justify-between border-b border-gray-100 py-4 last:border-0">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                    <svg
                                        class="h-5 w-5 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $commission->description }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $commission->created_at->format("d M Y, H:i") }}
                                    </p>
                                </div>
                            </div>
                            <p class="font-bold text-green-600">
                                +Rp {{ number_format($commission->amount, 0, ",", ".") }}
                            </p>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <div
                                class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100"
                            >
                                <svg
                                    class="h-10 w-10 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <p class="mb-2 text-gray-500">Belum ada komisi</p>
                            <p class="text-sm text-gray-400">
                                Komisi akan muncul setelah referral Anda melakukan rental
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </body>
</html>
