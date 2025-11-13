<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Instruksi Pembayaran - Slice</title>
        @vite(["resources/css/app.css"])
    </head>
    <body class="bg-gray-50">
        <div class="mx-auto max-w-3xl p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center gap-4">
                <a
                    href="{{ route("balance") }}"
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
                    <h1 class="text-2xl font-bold text-gray-900">Instruksi Pembayaran</h1>
                    <p class="mt-1 text-sm text-gray-500">Selesaikan pembayaran Anda</p>
                </div>
            </div>

            <!-- Status Banner -->
            <div class="mb-6 flex items-start gap-4 rounded-2xl border border-yellow-200 bg-yellow-50 p-6">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-500">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="mb-1 font-bold text-yellow-900">Menunggu Pembayaran</h3>
                    <p class="text-sm text-yellow-700">
                        Selesaikan pembayaran dalam 24 jam. Saldo akan otomatis ditambahkan setelah pembayaran berhasil.
                    </p>
                </div>
            </div>

            <!-- Transaction Details Card -->
            <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Transaksi</h3>
                </div>

                <div class="space-y-4 p-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nominal Top Up</span>
                        <span class="font-bold text-gray-900">
                            Rp {{ number_format($transaction->amount, 0, ",", ".") }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Admin</span>
                        <span class="font-bold text-gray-900">Rp 0</span>
                    </div>
                    <div class="h-px bg-gray-200"></div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-900">Total Pembayaran</span>
                        <span class="text-xl font-bold text-blue-600">
                            Rp {{ number_format($transaction->amount, 0, ",", ".") }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Virtual Account Card -->
            <div class="mb-6 overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-xl">
                <div class="p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm"
                        >
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-100">Transfer ke</p>
                            <p class="text-xl font-bold text-white">Bank {{ strtoupper($transaction->bank) }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/20 bg-white/10 p-6 backdrop-blur-sm">
                        <p class="mb-2 text-sm font-medium text-blue-100">Nomor Virtual Account</p>
                        <div class="flex items-center gap-3">
                            <p class="flex-1 font-mono text-2xl font-bold tracking-wider text-white" id="vaNumber">
                                {{ $transaction->virtual_account }}
                            </p>
                            <button
                                onclick="copyVA()"
                                class="flex items-center gap-2 rounded-xl bg-white px-5 py-3 font-semibold text-blue-600 transition hover:bg-blue-50 active:scale-[0.98]"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                Salin
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs text-blue-100">
                            <strong>Penting:</strong>
                            Pastikan Anda mentransfer ke nomor VA di atas dengan nominal yang tepat. Jangan gunakan
                            metode Beda Bank (RTGS/SKN).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Transfer Instructions -->
            <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Cara Transfer</h3>
                </div>

                <div class="space-y-4 p-6">
                    @if ($transaction->bank === "bca")
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900">Melalui ATM BCA</h4>
                            <ol class="space-y-2 text-sm text-gray-600">
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        1
                                    </span>
                                    <span>Masukkan kartu ATM dan PIN Anda</span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        2
                                    </span>
                                    <span>
                                        Pilih menu
                                        <strong>Transaksi Lainnya</strong>
                                        →
                                        <strong>Transfer</strong>
                                        →
                                        <strong>ke Rek BCA Virtual Account</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        3
                                    </span>
                                    <span>
                                        Masukkan nomor VA:
                                        <strong>{{ $transaction->virtual_account }}</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        4
                                    </span>
                                    <span>
                                        Masukkan nominal:
                                        <strong>Rp {{ number_format($transaction->amount, 0, ",", ".") }}</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        5
                                    </span>
                                    <span>Ikuti instruksi untuk menyelesaikan transaksi</span>
                                </li>
                            </ol>
                        </div>

                        <div class="my-4 h-px bg-gray-200"></div>

                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900">Melalui M-Banking BCA</h4>
                            <ol class="space-y-2 text-sm text-gray-600">
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        1
                                    </span>
                                    <span>Buka aplikasi BCA Mobile</span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        2
                                    </span>
                                    <span>
                                        Pilih
                                        <strong>m-Transfer</strong>
                                        →
                                        <strong>BCA Virtual Account</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        3
                                    </span>
                                    <span>Masukkan nomor VA dan nominal</span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-600"
                                    >
                                        4
                                    </span>
                                    <span>Konfirmasi dan selesaikan pembayaran</span>
                                </li>
                            </ol>
                        </div>
                    @elseif ($transaction->bank === "mandiri")
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900">Melalui ATM Mandiri</h4>
                            <ol class="space-y-2 text-sm text-gray-600">
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 text-xs font-semibold text-yellow-600"
                                    >
                                        1
                                    </span>
                                    <span>Masukkan kartu ATM dan PIN Anda</span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 text-xs font-semibold text-yellow-600"
                                    >
                                        2
                                    </span>
                                    <span>
                                        Pilih
                                        <strong>Bayar/Beli</strong>
                                        →
                                        <strong>Multipayment</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 text-xs font-semibold text-yellow-600"
                                    >
                                        3
                                    </span>
                                    <span>
                                        Masukkan kode perusahaan
                                        <strong>88908</strong>
                                        (Slice)
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 text-xs font-semibold text-yellow-600"
                                    >
                                        4
                                    </span>
                                    <span>
                                        Masukkan nomor VA:
                                        <strong>{{ $transaction->virtual_account }}</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 text-xs font-semibold text-yellow-600"
                                    >
                                        5
                                    </span>
                                    <span>Ikuti instruksi untuk menyelesaikan transaksi</span>
                                </li>
                            </ol>
                        </div>
                    @elseif ($transaction->bank === "bni")
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900">Melalui ATM BNI</h4>
                            <ol class="space-y-2 text-sm text-gray-600">
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-semibold text-orange-600"
                                    >
                                        1
                                    </span>
                                    <span>Masukkan kartu ATM dan PIN Anda</span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-semibold text-orange-600"
                                    >
                                        2
                                    </span>
                                    <span>
                                        Pilih
                                        <strong>Menu Lain</strong>
                                        →
                                        <strong>Transfer</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-semibold text-orange-600"
                                    >
                                        3
                                    </span>
                                    <span>
                                        Pilih
                                        <strong>Rekening Tabungan</strong>
                                        →
                                        <strong>Ke Rekening BNI</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-semibold text-orange-600"
                                    >
                                        4
                                    </span>
                                    <span>
                                        Masukkan nomor VA:
                                        <strong>{{ $transaction->virtual_account }}</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-semibold text-orange-600"
                                    >
                                        5
                                    </span>
                                    <span>Ikuti instruksi untuk menyelesaikan transaksi</span>
                                </li>
                            </ol>
                        </div>
                    @else
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900">Melalui ATM BRI</h4>
                            <ol class="space-y-2 text-sm text-gray-600">
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-700"
                                    >
                                        1
                                    </span>
                                    <span>Masukkan kartu ATM dan PIN Anda</span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-700"
                                    >
                                        2
                                    </span>
                                    <span>
                                        Pilih
                                        <strong>Transaksi Lain</strong>
                                        →
                                        <strong>Pembayaran</strong>
                                        →
                                        <strong>Lainnya</strong>
                                        →
                                        <strong>BRIVA</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-700"
                                    >
                                        3
                                    </span>
                                    <span>
                                        Masukkan nomor BRIVA:
                                        <strong>{{ $transaction->virtual_account }}</strong>
                                    </span>
                                </li>
                                <li class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-semibold text-blue-700"
                                    >
                                        4
                                    </span>
                                    <span>Ikuti instruksi untuk menyelesaikan transaksi</span>
                                </li>
                            </ol>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a
                    href="{{ route("balance") }}"
                    class="flex-1 rounded-xl border border-gray-200 bg-white py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Kembali ke Saldo
                </a>
                <a
                    href="{{ route("balance.confirm-payment", $transaction->id) }}"
                    class="flex-1 rounded-xl bg-blue-500 py-3 text-center font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                >
                    Sudah Transfer
                </a>
            </div>
        </div>

        <script>
            function copyVA() {
                const vaNumber = document.getElementById('vaNumber').textContent;
                navigator.clipboard.writeText(vaNumber).then(() => {
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tersalin!
                `;
                    btn.classList.add('bg-green-500', 'text-white');
                    btn.classList.remove('bg-white', 'text-blue-600');

                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.classList.remove('bg-green-500', 'text-white');
                        btn.classList.add('bg-white', 'text-blue-600');
                    }, 2000);
                });
            }
        </script>
    </body>
</html>
