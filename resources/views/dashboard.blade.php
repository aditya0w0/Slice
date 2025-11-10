<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Dashboard — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50 text-gray-900">
        @include("partials.header")

        <main class="mx-auto max-w-7xl px-6 pb-20">
            <div class="mb-6">
                <h1 class="text-3xl font-extrabold text-gray-900">Selamat Datang, {{ $user->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">Siap untuk berkarya hari ini?</p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Left column: large hero card + explore carousel -->
                <div class="space-y-6 lg:col-span-2">
                    @php
                        $activeOrder = $orders->first();
                    @endphp

                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-8 shadow-sm">
                        <div class="flex items-start justify-between gap-6">
                            <div>
                                <div class="text-xs font-semibold text-blue-700 uppercase">Sedang disewa</div>
                                <div class="mt-2 text-2xl font-bold text-gray-900">
                                    {{ $activeOrder->variant_slug ?? "—" }}
                                </div>
                                <div class="mt-2 text-sm text-gray-700">
                                    @if ($activeOrder)
                                        Akan kembali dalam
                                        <strong>3 hari lagi</strong>
                                        <div class="text-xs text-gray-500">
                                            (Jatuh tempo: {{ $activeOrder->created_at->addDays(7)->format("j F Y") }})
                                        </div>
                                    @else
                                            Tidak ada perangkat yang sedang disewa.
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-4">
                                <a
                                    href="/orders/{{ $activeOrder->id ?? "#" }}"
                                    class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white"
                                >
                                    Lihat Detail
                                </a>
                                <div
                                    class="flex h-20 w-20 items-center justify-center rounded bg-white text-sm font-medium text-gray-700"
                                >
                                    {{ $activeOrder ? "iPad Pro" : "" }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Jelajahi Perangkat Lain</h3>
                            <a href="/devices" class="text-sm text-blue-600">Lihat Semua →</a>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <article class="rounded-xl border border-gray-100 p-6">
                                <div class="text-4xl font-extrabold text-gray-700">iPhone 17</div>
                                <div class="mt-2 text-sm text-gray-600">
                                    iPhone 17 Pro
                                    <br />
                                    Mulai Rp 150.000/hari
                                </div>
                            </article>

                            <article class="rounded-xl border border-gray-100 p-6">
                                <div class="text-4xl font-extrabold text-gray-700">MacBook</div>
                                <div class="mt-2 text-sm text-gray-600">
                                    MacBook Pro 14" (M5)
                                    <br />
                                    Mulai Rp 300.000/hari
                                </div>
                            </article>
                        </div>
                    </div>
                </div>

                <!-- Right column: small summary cards -->
                <aside class="space-y-6">
                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-700">Riwayat Pesanan</h4>
                        <div class="mt-3 text-3xl font-bold text-blue-600">{{ $orders->count() }}</div>
                        <div class="mt-2 text-sm text-gray-500">Pesanan Selesai</div>
                        <a href="/orders" class="mt-4 block text-sm text-blue-600">Lihat Semua Riwayat →</a>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-700">Lacak Perangkat</h4>
                        <div class="mt-3 h-24 w-full rounded bg-gray-100 text-center leading-24 text-gray-400">
                            Static Map Area
                        </div>
                        <div class="mt-3 text-sm text-gray-600">
                            {{ $activeOrder ? $activeOrder->variant_slug . " • Online" : "Tidak ada perangkat aktif" }}
                        </div>
                        <a href="#" class="mt-2 block text-sm text-blue-600">Lihat Peta Langsung →</a>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-700">Pengaturan Akun</h4>
                        <div class="mt-2 text-sm text-gray-600">Perbarui info profil dan keamanan Anda.</div>
                        <a href="/profile" class="mt-4 block text-sm text-blue-600">Edit Profil & Keamanan →</a>
                    </div>
                </aside>
            </div>
        </main>
    </body>
</html>
