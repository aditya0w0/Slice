<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Preferensi Notifikasi - Slice</title>
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
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Preferensi Notifikasi</h1>
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

                <!-- Email Notifications Card -->
                <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi Email</h3>
                        <p class="mt-1 text-sm text-gray-500">Pilih jenis email yang ingin Anda terima</p>
                    </div>

                    <form method="POST" action="/settings/notifications">
                        @csrf
                        @method("PUT")

                        <!-- Order Updates -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Pembaruan Pesanan</p>
                                    <p class="mt-1 text-sm text-gray-500">Status pengiriman dan konfirmasi pesanan</p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="order_updates"
                                        {{ Auth::user()->notify_order_updates ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Promotions -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Promosi & Penawaran</p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Diskon, promo khusus, dan penawaran terbaru
                                    </p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="promotions"
                                        {{ Auth::user()->notify_promotions ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Reminders -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Pengingat</p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Pembayaran, jatuh tempo, dan pengembalian perangkat
                                    </p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="reminders"
                                        {{ Auth::user()->notify_reminders ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Newsletter -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Newsletter</p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Tips, berita produk, dan update fitur terbaru
                                    </p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="newsletter"
                                        {{ Auth::user()->notify_newsletter ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Security Alerts -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-start gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">Peringatan Keamanan</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Login mencurigakan dan aktivitas tidak biasa
                                        </p>
                                    </div>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="security_alerts"
                                        checked
                                        disabled
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                            <p class="mt-3 text-xs text-gray-400">
                                Notifikasi ini tidak dapat dinonaktifkan untuk keamanan akun Anda
                            </p>
                        </div>

                        <!-- Save Button -->
                        <div class="p-6">
                            <button
                                type="submit"
                                class="w-full rounded-xl bg-blue-500 py-3 font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                            >
                                Simpan Preferensi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Notification Frequency Card -->
                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Frekuensi Email</h3>
                        <p class="mt-1 text-sm text-gray-500">Seberapa sering Anda ingin menerima email</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3">
                            <label
                                class="{{ Auth::user()->notification_frequency === "realtime" ? "border-blue-500 bg-blue-50" : "" }} flex cursor-pointer items-center rounded-xl border-2 border-gray-200 p-4 transition hover:border-blue-500"
                            >
                                <input
                                    type="radio"
                                    name="frequency"
                                    value="realtime"
                                    {{ Auth::user()->notification_frequency === "realtime" ? "checked" : "" }}
                                    class="h-5 w-5 text-blue-500 focus:ring-blue-500"
                                />
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">Segera</p>
                                    <p class="text-sm text-gray-500">Setiap ada pembaruan penting</p>
                                </div>
                            </label>

                            <label
                                class="{{ Auth::user()->notification_frequency === "daily" ? "border-blue-500 bg-blue-50" : "" }} flex cursor-pointer items-center rounded-xl border-2 border-gray-200 p-4 transition hover:border-blue-500"
                            >
                                <input
                                    type="radio"
                                    name="frequency"
                                    value="daily"
                                    {{ Auth::user()->notification_frequency === "daily" ? "checked" : "" }}
                                    class="h-5 w-5 text-blue-500 focus:ring-blue-500"
                                />
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">Rangkuman Harian</p>
                                    <p class="text-sm text-gray-500">Satu email per hari dengan semua update</p>
                                </div>
                            </label>

                            <label
                                class="{{ Auth::user()->notification_frequency === "weekly" ? "border-blue-500 bg-blue-50" : "" }} flex cursor-pointer items-center rounded-xl border-2 border-gray-200 p-4 transition hover:border-blue-500"
                            >
                                <input
                                    type="radio"
                                    name="frequency"
                                    value="weekly"
                                    {{ Auth::user()->notification_frequency === "weekly" ? "checked" : "" }}
                                    class="h-5 w-5 text-blue-500 focus:ring-blue-500"
                                />
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">Rangkuman Mingguan</p>
                                    <p class="text-sm text-gray-500">Satu email per minggu</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
