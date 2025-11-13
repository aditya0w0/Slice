<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Memproses Pembayaran - Slice</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes pulse {
                0%,
                100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .spinner {
                animation: spin 1s linear infinite;
            }

            .pulse-text {
                animation: pulse 1.5s ease-in-out infinite;
            }

            .fade-in {
                animation: fadeIn 0.5s ease-out;
            }
        </style>
    </head>
    <body class="bg-gray-50">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="w-full max-w-md text-center">
                <!-- Processing Animation -->
                <div class="fade-in mb-8">
                    <div
                        class="inline-flex h-32 w-32 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 shadow-2xl"
                    >
                        <svg class="spinner h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                    </div>
                </div>

                <!-- Processing Text -->
                <div class="fade-in mb-6" style="animation-delay: 0.2s">
                    <h1 class="pulse-text mb-3 text-3xl font-bold text-gray-900">Memproses Pembayaran</h1>
                    <p class="text-gray-600">Mohon tunggu, kami sedang memverifikasi pembayaran Anda...</p>
                </div>

                <!-- Transaction Info -->
                <div class="fade-in mb-6 rounded-2xl bg-white p-6 shadow-sm" style="animation-delay: 0.4s">
                    <div class="mb-2 text-sm text-gray-600">Jumlah Pembayaran</div>
                    <div class="text-3xl font-bold text-gray-900">
                        Rp {{ number_format($transaction->amount, 0, ",", ".") }}
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="fade-in rounded-xl border border-blue-200 bg-blue-50 p-4" style="animation-delay: 0.6s">
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600"
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
                        <div class="text-left">
                            <p class="text-sm font-medium text-blue-900">Transaksi Aman</p>
                            <p class="mt-1 text-xs text-blue-700">
                                Data pembayaran Anda dienkripsi dengan teknologi SSL
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Simulate payment processing with realistic delay (2-3 seconds)
            const processingDelay = 2500 + Math.random() * 500; // Random 2.5-3 seconds

            setTimeout(() => {
                // Call backend to process payment
                fetch('{{ route("balance.process.payment", $transaction->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Redirect to success page
                            window.location.href = '{{ route("balance.payment.success", $transaction->id) }}';
                        } else {
                            // Handle error
                            alert('Pembayaran gagal. Silakan coba lagi.');
                            window.location.href = '{{ route("balance") }}';
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                        window.location.href = '{{ route("balance") }}';
                    });
            }, processingDelay);
        </script>
    </body>
</html>
