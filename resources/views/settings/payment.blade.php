<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Metode Pembayaran - Slice</title>
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
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Metode Pembayaran</h1>
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

                <!-- Saved Payment Methods Card -->
                <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Kartu Tersimpan</h3>
                        <p class="mt-1 text-sm text-gray-500">Kelola metode pembayaran Anda</p>
                    </div>

                    <!-- Card 1 (Primary) -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600"
                                >
                                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <rect
                                            x="2"
                                            y="5"
                                            width="20"
                                            height="14"
                                            rx="2"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        />
                                        <path d="M2 10h20" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-900">Visa •••• 4242</p>
                                        <span
                                            class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-600"
                                        >
                                            Utama
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Berakhir 12/25</p>
                                </div>
                            </div>
                            <button
                                class="rounded-xl px-4 py-2 font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"
                            >
                                Kelola
                            </button>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-gray-700 to-gray-800"
                                >
                                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <rect
                                            x="2"
                                            y="5"
                                            width="20"
                                            height="14"
                                            rx="2"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        />
                                        <path d="M2 10h20" stroke="currentColor" stroke-width="2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Mastercard •••• 8888</p>
                                    <p class="mt-1 text-sm text-gray-500">Berakhir 03/26</p>
                                </div>
                            </div>
                            <button
                                class="rounded-xl px-4 py-2 font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"
                            >
                                Kelola
                            </button>
                        </div>
                    </div>

                    <!-- Add New Card Button -->
                    <div class="p-6">
                        <button
                            onclick="openModal('addCardModal')"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-gray-50 py-3 font-semibold text-gray-700 transition hover:bg-gray-100 active:scale-[0.98]"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                ></path>
                            </svg>
                            Tambah Kartu Baru
                        </button>
                    </div>
                </div>

                <!-- Payment History Card -->
                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h3>
                                <p class="mt-1 text-sm text-gray-500">Transaksi 30 hari terakhir</p>
                            </div>
                            <a
                                href="{{ route("settings.payment.history") }}"
                                class="text-sm font-medium text-blue-600 transition hover:text-blue-700"
                            >
                                Lihat Semua
                            </a>
                        </div>
                    </div>

                    <!-- Transaction 1 -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
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
                                    <p class="font-semibold text-gray-900">iPhone 15 Pro - Cicilan ke-1</p>
                                    <p class="mt-1 text-sm text-gray-500">2 Jan 2025 • Visa •••• 4242</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900">Rp 3.500.000</p>
                        </div>
                    </div>

                    <!-- Transaction 2 -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
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
                                    <p class="mt-1 text-sm text-gray-500">28 Des 2024 • Mastercard •••• 8888</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900">Rp 5.000.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Card Modal (Modern Popup) -->
        <div
            id="addCardModal"
            class="bg-opacity-50 fixed inset-0 z-50 hidden items-center justify-center bg-black p-4 backdrop-blur-sm"
            onclick="if(event.target === this) closeModal('addCardModal')"
        >
            <div
                class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-3xl bg-white shadow-2xl"
                onclick="event.stopPropagation()"
            >
                <div
                    class="sticky top-0 flex items-center justify-between rounded-t-3xl border-b border-gray-100 bg-white p-6"
                >
                    <h3 class="text-xl font-bold text-gray-900">Tambah Kartu Baru</h3>
                    <button
                        onclick="closeModal('addCardModal')"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 transition hover:bg-gray-200"
                    >
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route("settings.payment.add") }}">
                    @csrf

                    <!-- Card Number -->
                    <div class="border-b border-gray-100 p-6">
                        <label class="mb-2 block text-sm font-semibold text-gray-900">Nomor Kartu</label>
                        <input
                            type="text"
                            name="card_number"
                            placeholder="1234 5678 9012 3456"
                            maxlength="19"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        />
                    </div>

                    <!-- Card Name -->
                    <div class="border-b border-gray-100 p-6">
                        <label class="mb-2 block text-sm font-semibold text-gray-900">Nama di Kartu</label>
                        <input
                            type="text"
                            name="card_name"
                            placeholder="JOHN DOE"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        />
                    </div>

                    <!-- Expiry and CVV -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900">Tanggal Berakhir</label>
                                <input
                                    type="text"
                                    name="expiry"
                                    placeholder="MM/YY"
                                    maxlength="5"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    required
                                />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900">CVV</label>
                                <input
                                    type="text"
                                    name="cvv"
                                    placeholder="123"
                                    maxlength="4"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="border-b border-gray-100 p-6">
                        <label class="mb-2 block text-sm font-semibold text-gray-900">Alamat Penagihan</label>
                        <textarea
                            name="billing_address"
                            rows="3"
                            placeholder="Masukkan alamat lengkap"
                            class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        ></textarea>
                    </div>

                    <!-- Set as Primary -->
                    <div class="border-b border-gray-100 p-6">
                        <label class="flex cursor-pointer items-center">
                            <input
                                type="checkbox"
                                name="set_primary"
                                class="h-5 w-5 rounded text-blue-500 focus:ring-blue-500"
                            />
                            <span class="ml-3 font-medium text-gray-900">Jadikan metode pembayaran utama</span>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 p-6">
                        <button
                            type="button"
                            onclick="closeModal('addCardModal')"
                            class="flex-1 rounded-xl bg-gray-100 py-3 font-semibold text-gray-700 transition hover:bg-gray-200 active:scale-[0.98]"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-blue-500 py-3 font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                        >
                            Simpan Kartu
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }

            // Close modal on Escape key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeModal('addCardModal');
                }
            });
        </script>
    </body>
</html>
