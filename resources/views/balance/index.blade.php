<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Balance - Slice</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Saldo</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola saldo dan top up</p>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-start gap-3">
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-green-900">Berhasil!</p>
                <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Current Balance Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-3xl shadow-xl overflow-hidden mb-6">
            <div class="p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-2">Saldo Tersedia</p>
                        <p class="text-4xl font-bold text-white mb-6">
                            Rp {{ number_format(Auth::user()->balance ?? 0, 0, ',', '.') }}
                        </p>
                        <button onclick="openModal('topupModal')" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition active:scale-[0.98] flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Top Up Saldo
                        </button>
                    </div>
                    <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Balance Info -->
            <div class="bg-white/10 backdrop-blur-sm border-t border-white/20 px-8 py-4 flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium mb-1">Total Top Up Bulan Ini</p>
                    <p class="text-white font-semibold">Rp {{ number_format($totalTopupThisMonth ?? 0, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-blue-100 text-xs font-medium mb-1">Total Pengeluaran</p>
                    <p class="text-white font-semibold">Rp {{ number_format($totalSpentThisMonth ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Referral Program Card -->
        <div class="bg-white rounded-3xl shadow-sm overflow-hidden mb-6 border border-gray-100">
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Program Referral</h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">Ajak teman dan dapatkan komisi <span class="font-bold text-blue-600">1%</span> dari setiap rental yang mereka lakukan!</p>
                    </div>
                    <a href="{{ route('balance.referral.earnings') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-xl transition-all duration-200 hover:scale-[0.98] text-sm">
                        Lihat Penghasilan
                    </a>
                </div>

                <!-- Referral Stats -->
                <div class="flex gap-6 mb-6">
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">Teman Diajak</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $referralCount ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">Bonus Diterima</p>
                        <p class="text-3xl font-bold text-blue-600">Rp {{ number_format(($referralCount ?? 0) * 50000, 0, ',', '.') }}</p>
                    </div>
                </div>

                        <!-- Referral Code -->
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200">
                            <p class="text-gray-600 text-xs font-medium mb-2">Kode Referral Anda</p>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-white rounded-xl px-4 py-3 border border-gray-200">
                                    <p class="text-gray-900 font-mono font-bold text-lg tracking-wider" id="referralCode">{{ Auth::user()->referral_code ?? 'SLICE' . strtoupper(substr(md5(Auth::user()->id), 0, 6)) }}</p>
                                </div>
                                <button onclick="copyReferralCode()" class="px-5 py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition active:scale-[0.98] flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="bg-white rounded-3xl shadow-sm overflow-hidden max-w-4xl mx-auto">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-900">Transaction History</h3>
            </div>

            @forelse(($transactions ?? [])->take(2) as $transaction)
            <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                            @if($transaction->type === 'credit') bg-green-100 @else bg-gray-100 @endif">
                            @if($transaction->type === 'credit')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm truncate">{{ $transaction->description }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $transaction->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <p class="font-semibold text-sm @if($transaction->type === 'credit') text-green-600 @else text-gray-900 @endif shrink-0">
                        {{ $transaction->type === 'credit' ? '+' : '-' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium text-sm">No transactions yet</p>
                <p class="text-xs text-gray-400 mt-1">Your transactions will appear here</p>
            </div>
            @endforelse

            @if(($transactions ?? [])->count() > 5)
            <div class="p-4">
                <a href="{{ route('balance.transactions') }}" class="block w-full py-3 text-center text-blue-600 font-semibold hover:bg-blue-50 rounded-xl transition-colors">
                    See More
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Top Up Modal - Step 1: Amount -->
    <div id="topupModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 p-4" onclick="if(event.target === this) closeTopupModal()">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-100 flex items-center justify-between rounded-t-3xl z-10">
                <h3 class="text-xl font-bold text-gray-900">Top Up Saldo</h3>
                <button onclick="closeTopupModal()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Amount Selection -->
            <div class="p-6 border-b border-gray-100">
                <label class="block text-sm font-semibold text-gray-900 mb-3">Nominal Top Up</label>
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <button type="button" onclick="selectAmount(50000)" class="amount-btn py-3 bg-gray-50 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition">
                        Rp 50K
                    </button>
                    <button type="button" onclick="selectAmount(100000)" class="amount-btn py-3 bg-gray-50 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition">
                        Rp 100K
                    </button>
                    <button type="button" onclick="selectAmount(200000)" class="amount-btn py-3 bg-gray-50 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition">
                        Rp 200K
                    </button>
                    <button type="button" onclick="selectAmount(500000)" class="amount-btn py-3 bg-gray-50 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition">
                        Rp 500K
                    </button>
                    <button type="button" onclick="selectAmount(1000000)" class="amount-btn py-3 bg-gray-50 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition">
                        Rp 1JT
                    </button>
                    <button type="button" onclick="selectAmount(2000000)" class="amount-btn py-3 bg-gray-50 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition">
                        Rp 2JT
                    </button>
                </div>
                <input
                    type="text"
                    id="amountInput"
                    placeholder="Atau masukkan nominal lain"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    oninput="formatCurrency(this)"
                >
            </div>

            <!-- Next Button -->
            <div class="p-6">
                <button
                    onclick="proceedToPaymentMethod()"
                    class="w-full py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition active:scale-[0.98]"
                >
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Method Selection Modal -->
    <div id="paymentMethodModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 p-4" onclick="if(event.target === this) closePaymentMethodModal()">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-100 flex items-center justify-between rounded-t-3xl z-10">
                <div class="flex items-center gap-3">
                    <button onclick="backToAmount()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pilih Pembayaran</h3>
                        <p class="text-sm text-gray-500 mt-0.5" id="selectedAmount">Rp 0</p>
                    </div>
                </div>
                <button onclick="closePaymentMethodModal()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-3">
                <!-- Credit/Debit Card -->
                <button onclick="selectCardPayment()" class="w-full p-4 bg-gray-50 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Kartu Kredit/Debit</p>
                            <p class="text-sm text-gray-500">Pilih atau tambah kartu baru</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </button>

                <!-- QRIS -->
                <button onclick="selectQRISPayment()" class="w-full p-4 bg-gray-50 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">QRIS</p>
                            <p class="text-sm text-gray-500">Scan & Pay dengan e-wallet</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </button>

                <!-- Bank Transfer -->
                <button onclick="selectBankTransfer()" class="w-full p-4 bg-gray-50 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Transfer Bank</p>
                            <p class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Card Selection Modal -->
    <div id="cardSelectionModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 p-4" onclick="if(event.target === this) closeCardSelectionModal()">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-100 flex items-center justify-between rounded-t-3xl z-10">
                <div class="flex items-center gap-3">
                    <button onclick="backToPaymentMethod()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h3 class="text-xl font-bold text-gray-900">Pilih Kartu</h3>
                </div>
                <button onclick="closeCardSelectionModal()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-3">
                <!-- Saved Card 1 - Visa -->
                <button onclick="confirmPaymentWithCard('visa_4242')" class="w-full p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl text-left hover:from-blue-600 hover:to-blue-700 transition">
                    <div class="flex items-start justify-between mb-3">
                        <svg class="w-12 h-8 text-white" viewBox="0 0 48 32" fill="currentColor">
                            <rect width="48" height="32" rx="4" fill="white" fill-opacity="0.2"/>
                            <text x="6" y="20" font-family="monospace" font-size="10" fill="white" font-weight="bold">VISA</text>
                        </svg>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold text-white">Utama</span>
                    </div>
                    <p class="text-white font-mono text-lg tracking-wider mb-2">•••• •••• •••• 4242</p>
                    <div class="flex justify-between items-center">
                        <p class="text-white/80 text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-white/80 text-sm">12/26</p>
                    </div>
                </button>

                <!-- Saved Card 2 - Mastercard -->
                <button onclick="confirmPaymentWithCard('master_8888')" class="w-full p-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-2xl text-left hover:from-gray-800 hover:to-gray-900 transition">
                    <div class="flex items-start justify-between mb-3">
                        <svg class="w-12 h-8 text-white" viewBox="0 0 48 32" fill="currentColor">
                            <rect width="48" height="32" rx="4" fill="white" fill-opacity="0.2"/>
                            <circle cx="16" cy="16" r="8" fill="#EB001B" opacity="0.8"/>
                            <circle cx="24" cy="16" r="8" fill="#F79E1B" opacity="0.8"/>
                        </svg>
                        <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-white/60">Backup</span>
                    </div>
                    <p class="text-white font-mono text-lg tracking-wider mb-2">•••• •••• •••• 8888</p>
                    <div class="flex justify-between items-center">
                        <p class="text-white/80 text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-white/80 text-sm">08/27</p>
                    </div>
                </button>

                <!-- Add New Card -->
                <button onclick="openAddCardModal()" class="w-full p-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl hover:border-blue-500 hover:bg-blue-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0 border-2 border-gray-200">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Tambah Kartu Baru</p>
                            <p class="text-sm text-gray-500">Visa, Mastercard, JCB</p>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Add Card Modal -->
    <div id="addCardModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 p-4" onclick="if(event.target === this) closeAddCardModal()">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-100 flex items-center justify-between rounded-t-3xl z-10">
                <div class="flex items-center gap-3">
                    <button onclick="backToCardSelection()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h3 class="text-xl font-bold text-gray-900">Tambah Kartu Baru</h3>
                </div>
                <button onclick="closeAddCardModal()" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('balance.topup') }}" id="addCardForm" onsubmit="return handleAddCardSubmit(event)">
                @csrf
                <input type="hidden" name="payment_method" value="credit_card">
                <input type="hidden" name="amount" id="finalAmount">

                <div class="p-6 space-y-4">
                    <!-- Card Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Nomor Kartu</label>
                        <input
                            type="text"
                            name="card_number"
                            placeholder="1234 5678 9012 3456"
                            maxlength="19"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required
                        >
                    </div>

                    <!-- Expiry and CVV -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Tanggal Berakhir</label>
                            <input
                                type="text"
                                name="expiry"
                                placeholder="MM/YY"
                                maxlength="5"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">CVV</label>
                            <input
                                type="text"
                                name="cvv"
                                placeholder="123"
                                maxlength="4"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                            >
                        </div>
                    </div>

                    <!-- Cardholder Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Nama di Kartu</label>
                        <input
                            type="text"
                            name="card_name"
                            placeholder="JOHN DOE"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required
                        >
                    </div>

                    <!-- Save Card Checkbox -->
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="save_card" checked class="w-5 h-5 text-blue-500 rounded focus:ring-blue-500">
                        <span class="ml-3 text-gray-900">Simpan kartu untuk pembayaran berikutnya</span>
                    </label>
                </div>

                <div class="p-6 border-t border-gray-100">
                    <button
                        type="submit"
                        class="w-full py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition active:scale-[0.98]"
                    >
                        Bayar <span id="finalAmountDisplay">Rp 0</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 p-4" onclick="if(event.target === this) closeModal('confirmationModal')">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full" onclick="event.stopPropagation()">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Konfirmasi Pembayaran</h3>
            </div>

            <div class="p-6">
                <!-- Amount Display -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 mb-6 text-center">
                    <p class="text-sm text-blue-700 mb-2">Jumlah Top Up</p>
                    <p class="text-4xl font-bold text-blue-900" id="confirmAmount">Rp 0</p>
                </div>

                <!-- Payment Method -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-semibold text-gray-900" id="confirmPaymentMethod">Credit Card</span>
                    </div>

                    <!-- Card Details (shown for card payments) -->
                    <div id="confirmCardDetails" class="hidden">
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600">Kartu</span>
                            <span class="font-semibold text-gray-900" id="confirmCardNumber">•••• 4242</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600">Nama</span>
                            <span class="font-semibold text-gray-900" id="confirmCardName">-</span>
                        </div>
                    </div>

                    <!-- Processing Time Notice -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-900">Proses Otomatis</p>
                                <p class="text-xs text-yellow-700 mt-1">Saldo akan masuk ke akun Anda dalam beberapa detik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 border-t border-gray-100 flex gap-3">
                <button
                    onclick="closeModal('confirmationModal')"
                    class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition active:scale-[0.98]"
                    id="cancelBtn"
                >
                    Batal
                </button>
                <button
                    onclick="submitPayment()"
                    class="flex-1 px-4 py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition active:scale-[0.98] flex items-center justify-center gap-2"
                    id="confirmBtn"
                >
                    <span id="confirmBtnText">Konfirmasi Bayar</span>
                    <svg id="confirmBtnLoader" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentAmount = 0;

        // Modal functions
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

        function closeTopupModal() {
            closeModal('topupModal');
        }

        function closePaymentMethodModal() {
            closeModal('paymentMethodModal');
        }

        function closeCardSelectionModal() {
            closeModal('cardSelectionModal');
        }

        function closeAddCardModal() {
            closeModal('addCardModal');
        }

        function backToAmount() {
            closePaymentMethodModal();
            openModal('topupModal');
        }

        function backToPaymentMethod() {
            closeCardSelectionModal();
            openModal('paymentMethodModal');
        }

        function backToCardSelection() {
            closeAddCardModal();
            openModal('cardSelectionModal');
        }

        // Amount selection
        function selectAmount(amount) {
            currentAmount = amount;
            const input = document.getElementById('amountInput');
            input.value = 'Rp ' + amount.toLocaleString('id-ID');

            // Remove active class from all buttons
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-600');
                btn.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-700');
            });

            // Add active class to clicked button
            event.target.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-700');
            event.target.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-600');
        }

        // Currency formatting
        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value) {
                currentAmount = parseInt(value);
                input.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            }
        }

        // Proceed to payment method (NO CLIENT-SIDE VALIDATION - Server handles it)
        function proceedToPaymentMethod() {
            const input = document.getElementById('amountInput');
            if (!input.value) {
                alert('Masukkan nominal top up');
                return;
            }

            document.getElementById('selectedAmount').textContent = input.value;
            closeTopupModal();
            openModal('paymentMethodModal');
        }

        // Select payment methods
        function selectCardPayment() {
            closePaymentMethodModal();
            openModal('cardSelectionModal');
        }

        function selectQRISPayment() {
            // Implement QRIS flow
            alert('QRIS payment coming soon!');
        }

        function selectBankTransfer() {
            // Implement bank transfer flow
            alert('Bank transfer coming soon!');
        }

        // Confirm payment with saved card
        let selectedCardId = null;
        let selectedCardNumber = null;
        let selectedCardName = null;

        function confirmPaymentWithCard(cardId) {
            // Store card details
            selectedCardId = cardId;

            // Get card details
            if (cardId === 'visa_4242') {
                selectedCardNumber = '•••• 4242';
                selectedCardName = 'Visa';
            } else if (cardId === 'master_8888') {
                selectedCardNumber = '•••• 8888';
                selectedCardName = 'Mastercard';
            }

            // Update confirmation modal
            document.getElementById('confirmAmount').textContent = 'Rp ' + currentAmount.toLocaleString('id-ID');
            document.getElementById('confirmPaymentMethod').textContent = 'Credit Card';
            document.getElementById('confirmCardNumber').textContent = selectedCardNumber;
            document.getElementById('confirmCardName').textContent = selectedCardName;
            document.getElementById('confirmCardDetails').classList.remove('hidden');

            // Close card selection and open confirmation
            closeModal('cardSelectionModal');
            openModal('confirmationModal');
        }

        // Open add card modal
        function openAddCardModal() {
            document.getElementById('finalAmount').value = 'Rp ' + currentAmount.toLocaleString('id-ID');
            document.getElementById('finalAmountDisplay').textContent = 'Rp ' + currentAmount.toLocaleString('id-ID');
            closeCardSelectionModal();
            openModal('addCardModal');
        }

        // Handle add card form submission
        function handleAddCardSubmit(event) {
            event.preventDefault();

            const form = event.target;
            const cardNumber = form.querySelector('input[name="card_number"]').value;
            const cardName = form.querySelector('input[name="card_name"]').value;

            // Store card details for confirmation
            selectedCardId = 'new_card';
            selectedCardNumber = '•••• ' + cardNumber.slice(-4);
            selectedCardName = cardName;

            // Update confirmation modal
            document.getElementById('confirmAmount').textContent = 'Rp ' + currentAmount.toLocaleString('id-ID');
            document.getElementById('confirmPaymentMethod').textContent = 'Credit Card (Kartu Baru)';
            document.getElementById('confirmCardNumber').textContent = selectedCardNumber;
            document.getElementById('confirmCardName').textContent = selectedCardName;
            document.getElementById('confirmCardDetails').classList.remove('hidden');

            // Close add card modal and open confirmation
            closeModal('addCardModal');
            openModal('confirmationModal');

            return false;
        }

        // Submit payment (updated to handle new card)
        function submitPayment() {
            // Show loading state
            const confirmBtn = document.getElementById('confirmBtn');
            const confirmBtnText = document.getElementById('confirmBtnText');
            const confirmBtnLoader = document.getElementById('confirmBtnLoader');
            const cancelBtn = document.getElementById('cancelBtn');

            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            confirmBtn.classList.add('opacity-75', 'cursor-not-allowed');
            confirmBtnText.textContent = 'Memproses...';
            confirmBtnLoader.classList.remove('hidden');

            if (selectedCardId === 'new_card') {
                // Submit the add card form
                const form = document.getElementById('addCardForm');
                form.onsubmit = null; // Remove the event listener
                form.submit();
            } else {
                // Create and submit form for saved card
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("balance.topup") }}';

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = 'payment_method';
                methodInput.value = 'credit_card';
                form.appendChild(methodInput);

                const amountInput = document.createElement('input');
                amountInput.type = 'hidden';
                amountInput.name = 'amount';
                amountInput.value = 'Rp ' + currentAmount.toLocaleString('id-ID');
                form.appendChild(amountInput);

                const cardInput = document.createElement('input');
                cardInput.type = 'hidden';
                cardInput.name = 'card_id';
                cardInput.value = selectedCardId;
                form.appendChild(cardInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Copy referral code
        function copyReferralCode() {
            const code = document.getElementById('referralCode').textContent;
            navigator.clipboard.writeText(code).then(() => {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tersalin!
                `;
                btn.classList.add('bg-green-500');
                btn.classList.remove('bg-blue-500');

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-green-500');
                    btn.classList.add('bg-blue-500');
                }, 2000);
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeTopupModal();
                closePaymentMethodModal();
                closeCardSelectionModal();
                closeAddCardModal();
            }
        });
    </script>
</body>
</html>
