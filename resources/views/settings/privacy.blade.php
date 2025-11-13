<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Privasi & Data - Slice</title>
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
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Privasi & Data</h1>
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

                <!-- Privacy Settings Card -->
                <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaturan Privasi</h3>
                        <p class="mt-1 text-sm text-gray-500">Kelola bagaimana data Anda digunakan</p>
                    </div>

                    <form method="POST" action="{{ route("settings.privacy.update") }}">
                        @csrf
                        @method("PUT")

                        <!-- Profile Visibility -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Visibilitas Profil</p>
                                    <p class="mt-1 text-sm text-gray-500">Tampilkan profil saya di pencarian publik</p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="profile_visibility"
                                        {{ Auth::user()->profile_visibility ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Activity Tracking -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Pelacakan Aktivitas</p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Izinkan kami meningkatkan layanan dengan data penggunaan
                                    </p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="activity_tracking"
                                        {{ Auth::user()->activity_tracking ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Personalized Ads -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Iklan yang Dipersonalisasi</p>
                                    <p class="mt-1 text-sm text-gray-500">Tampilkan iklan berdasarkan minat saya</p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="personalized_ads"
                                        {{ Auth::user()->personalized_ads ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Location Services -->
                        <div class="border-b border-gray-100 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Layanan Lokasi</p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Gunakan lokasi saya untuk pengalaman lebih baik
                                    </p>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        name="location_services"
                                        {{ Auth::user()->location_services ? "checked" : "" }}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="peer h-8 w-14 rounded-full bg-gray-200 peer-checked:bg-blue-500 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:top-1 after:left-1 after:h-6 after:w-6 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="p-6">
                            <button
                                type="submit"
                                class="w-full rounded-xl bg-blue-500 py-3 font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                            >
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Data Management Card -->
                <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Manajemen Data</h3>
                        <p class="mt-1 text-sm text-gray-500">Unduh atau hapus data pribadi Anda</p>
                    </div>

                    <!-- Download Data -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gray-100">
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
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Unduh Data Saya</p>
                                    <p class="mt-1 text-sm text-gray-500">Dapatkan salinan semua data pribadi Anda</p>
                                </div>
                            </div>
                            <a
                                href="{{ route("settings.data.download") }}"
                                class="rounded-xl bg-gray-50 px-6 py-2 font-medium text-gray-700 transition hover:bg-gray-100 active:scale-[0.98]"
                            >
                                Unduh
                            </a>
                        </div>
                    </div>

                    <!-- View Activity Log -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gray-100">
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
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Log Aktivitas</p>
                                    <p class="mt-1 text-sm text-gray-500">Lihat riwayat semua aktivitas akun</p>
                                </div>
                            </div>
                            <button
                                class="rounded-xl bg-gray-50 px-6 py-2 font-medium text-gray-700 transition hover:bg-gray-100 active:scale-[0.98]"
                            >
                                Lihat
                            </button>
                        </div>
                    </div>

                    <!-- Clear Cache -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gray-100">
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
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Hapus Cache & Cookies</p>
                                    <p class="mt-1 text-sm text-gray-500">Bersihkan data sementara yang tersimpan</p>
                                </div>
                            </div>
                            <button
                                class="rounded-xl bg-gray-50 px-6 py-2 font-medium text-gray-700 transition hover:bg-gray-100 active:scale-[0.98]"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone Card -->
                <div class="overflow-hidden rounded-3xl border-2 border-red-100 bg-white shadow-sm">
                    <div class="border-b border-red-100 p-6">
                        <h3 class="text-lg font-semibold text-red-600">Zona Berbahaya</h3>
                        <p class="mt-1 text-sm text-gray-500">Tindakan permanen yang tidak bisa dibatalkan</p>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">Hapus Akun</p>
                                <p class="mt-1 text-sm text-gray-500">Hapus permanen akun dan semua data Anda</p>
                            </div>
                            <button
                                onclick="if(confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')) { document.getElementById('delete-account-form').submit(); }"
                                class="rounded-xl bg-red-50 px-6 py-2 font-medium text-red-600 transition hover:bg-red-100 active:scale-[0.98]"
                            >
                                Hapus Akun
                            </button>
                        </div>

                        <form id="delete-account-form" method="POST" action="{{ route('settings.account.delete') }}" class="hidden">
                            @csrf
                            @method("DELETE")
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
