<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Pembayaran Berhasil - Slice</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @keyframes checkmark {
                0% {
                    transform: scale(0);
                    opacity: 0;
                }
                50% {
                    transform: scale(1.2);
                }
                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .checkmark-animation {
                animation: checkmark 0.6s ease-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }
        </style>
    </head>
    <body class="bg-gray-50">
        <div class="flex min-h-screen items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Success Icon -->
                <div class="mb-8 text-center">
                    <div
                        class="checkmark-animation inline-flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700"
                    >
                        <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="3"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="fade-in-up mb-8 text-center" style="animation-delay: 0.2s">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600">Top up saldo Anda telah berhasil diproses</p>
                </div>

                <!-- Transaction Details -->
                <div class="fade-in-up mb-6 rounded-3xl bg-white p-6 shadow-sm" style="animation-delay: 0.3s">
                    <h2 class="mb-4 text-sm font-medium text-gray-500">Detail Transaksi</h2>

                    <div class="space-y-3">
                        <!-- Amount -->
                        <div class="flex items-center justify-between border-b border-gray-100 py-3">
                            <span class="text-gray-600">Jumlah</span>
                            <span class="text-lg font-bold text-gray-900">
                                Rp {{ number_format($transaction->amount, 0, ",", ".") }}
                            </span>
                        </div>

                        <!-- Payment Method -->
                        <div class="flex items-center justify-between border-b border-gray-100 py-3">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-medium text-gray-900">
                                @if ($transaction->payment_method === "credit_card")
                                    Kartu Kredit
                                @elseif ($transaction->payment_method === "bank_transfer")
                                    Transfer Bank ({{ strtoupper($transaction->bank) }})
                                @elseif ($transaction->payment_method === "qris")
                                    QRIS
                                @else
                                    {{ ucfirst($transaction->payment_method) }}
                                @endif
                            </span>
                        </div>

                        <!-- Transaction ID -->
                        <div class="flex items-center justify-between border-b border-gray-100 py-3">
                            <span class="text-gray-600">ID Transaksi</span>
                            <span class="font-mono text-sm text-gray-900">
                                #{{ str_pad($transaction->id, 8, "0", STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <!-- Date & Time -->
                        <div class="flex items-center justify-between py-3">
                            <span class="text-gray-600">Tanggal & Waktu</span>
                            <span class="text-gray-900">{{ $transaction->created_at->format("d M Y, H:i") }}</span>
                        </div>
                    </div>
                </div>

                <!-- New Balance -->
                <div
                    class="fade-in-up mb-6 rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 shadow-xl"
                    style="animation-delay: 0.4s"
                >
                    <div class="text-center">
                        <p class="mb-2 text-sm text-blue-100">Saldo Baru Anda</p>
                        <p class="text-4xl font-bold text-white">
                            Rp {{ number_format(Auth::user()->balance, 0, ",", ".") }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="fade-in-up space-y-3" style="animation-delay: 0.5s">
                    <a
                        href="{{ route("balance") }}"
                        class="block w-full rounded-2xl bg-blue-600 py-4 text-center font-medium text-white shadow-lg transition-all duration-200 hover:scale-[0.98] hover:bg-blue-700"
                    >
                        Kembali ke Saldo
                    </a>

                    <a
                        href="{{ route("dashboard") }}"
                        class="block w-full rounded-2xl border border-gray-200 bg-white py-4 text-center font-medium text-gray-700 shadow-sm transition-all duration-200 hover:scale-[0.98] hover:bg-gray-50"
                    >
                        Kembali ke Dashboard
                    </a>
                </div>

                <!-- Download Receipt -->
                <div class="fade-in-up mt-6 text-center" style="animation-delay: 0.6s">
                    <button
                        onclick="window.print()"
                        class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 transition-colors hover:text-blue-700"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                            ></path>
                        </svg>
                        Unduh Bukti Pembayaran
                    </button>
                </div>
            </div>
        </div>

        <!-- Print Styles -->
        <style media="print">
            body {
                background: white;
            }
            .fade-in-up {
                animation: none !important;
            }
            button {
                display: none;
            }
            a[href] {
                color: black !important;
                text-decoration: none !important;
            }
        </style>
    </body>
</html>
