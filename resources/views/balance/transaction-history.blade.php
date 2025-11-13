<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Transaction History - Slice</title>
        @vite(["resources/css/app.css"])
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen px-4 py-8">
            <div class="mx-auto max-w-6xl">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route("balance") }}"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm transition-colors hover:bg-gray-100"
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
                        <h1 class="text-3xl font-bold text-gray-900">Transaction History</h1>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6 flex gap-3 rounded-2xl bg-white p-4 shadow-sm">
                    <button
                        onclick="filterTransactions('all')"
                        class="filter-btn active rounded-xl px-5 py-2.5 text-sm font-semibold transition-colors"
                        data-filter="all"
                    >
                        All
                    </button>
                    <button
                        onclick="filterTransactions('credit')"
                        class="filter-btn rounded-xl px-5 py-2.5 text-sm font-semibold transition-colors"
                        data-filter="credit"
                    >
                        Income
                    </button>
                    <button
                        onclick="filterTransactions('debit')"
                        class="filter-btn rounded-xl px-5 py-2.5 text-sm font-semibold transition-colors"
                        data-filter="debit"
                    >
                        Expense
                    </button>
                    <button
                        onclick="filterTransactions('pending')"
                        class="filter-btn rounded-xl px-5 py-2.5 text-sm font-semibold transition-colors"
                        data-filter="pending"
                    >
                        Pending
                    </button>
                </div>

                <!-- Transaction List -->
                <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                    @forelse ($transactions as $transaction)
                        <div
                            class="transaction-item border-b border-gray-100 px-6 py-4 transition-colors hover:bg-gray-50"
                            data-type="{{ $transaction->type }}"
                            data-status="{{ $transaction->status }}"
                        >
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex min-w-0 flex-1 items-center gap-4">
                                    <!-- Icon -->
                                    <div
                                        class="@if($transaction->type === 'credit') bg-green-100 @else bg-gray-100 @endif flex h-12 w-12 shrink-0 items-center justify-center rounded-xl"
                                    >
                                        @if ($transaction->type === "credit")
                                            <svg
                                                class="h-6 w-6 text-green-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2.5"
                                                    d="M12 4v16m8-8H4"
                                                ></path>
                                            </svg>
                                        @else
                                            <svg
                                                class="h-6 w-6 text-gray-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2.5"
                                                    d="M20 12H4"
                                                ></path>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate font-semibold text-gray-900">
                                            {{ $transaction->description }}
                                        </p>
                                        <div class="mt-1 flex items-center gap-3">
                                            <p class="text-sm text-gray-500">
                                                {{ $transaction->created_at->format("M d, Y · h:i A") }}
                                            </p>
                                            @if ($transaction->payment_method)
                                                <span class="text-sm text-gray-400">•</span>
                                                <p class="text-sm text-gray-500">
                                                    @if ($transaction->payment_method === "credit_card")
                                                        Credit Card
                                                    @elseif ($transaction->payment_method === "bank_transfer")
                                                        {{ strtoupper($transaction->bank) }} Transfer
                                                    @elseif ($transaction->payment_method === "qris")
                                                        QRIS
                                                    @else
                                                        {{ ucfirst($transaction->payment_method) }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount and Status -->
                                <div class="shrink-0 text-right">
                                    <p
                                        class="@if($transaction->type === 'credit') text-green-600 @else text-gray-900 @endif text-lg font-bold"
                                    >
                                        {{ $transaction->type === "credit" ? "+" : "-" }}Rp{{ number_format($transaction->amount, 0, ",", ".") }}
                                    </p>
                                    @if ($transaction->status === "pending")
                                        <span
                                            class="mt-1 inline-block rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700"
                                        >
                                            Pending
                                        </span>
                                    @elseif ($transaction->status === "failed")
                                        <span
                                            class="mt-1 inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"
                                        >
                                            Failed
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <div
                                class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-gray-100"
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
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    ></path>
                                </svg>
                            </div>
                            <p class="text-lg font-semibold text-gray-500">No transactions yet</p>
                            <p class="mt-2 text-sm text-gray-400">Your transactions will appear here</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($transactions->hasPages())
                    <div class="mt-6">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Filter functionality
            function filterTransactions(filter) {
                const items = document.querySelectorAll('.transaction-item');
                const buttons = document.querySelectorAll('.filter-btn');

                // Update active button
                buttons.forEach((btn) => {
                    if (btn.dataset.filter === filter) {
                        btn.classList.add('active', 'bg-blue-500', 'text-white');
                        btn.classList.remove('bg-gray-100', 'text-gray-700');
                    } else {
                        btn.classList.remove('active', 'bg-blue-500', 'text-white');
                        btn.classList.add('bg-gray-100', 'text-gray-700');
                    }
                });

                // Filter items
                items.forEach((item) => {
                    const type = item.dataset.type;
                    const status = item.dataset.status;

                    if (filter === 'all') {
                        item.style.display = 'flex';
                    } else if (filter === 'credit' || filter === 'debit') {
                        item.style.display = type === filter ? 'flex' : 'none';
                    } else if (filter === 'pending') {
                        item.style.display = status === 'pending' ? 'flex' : 'none';
                    }
                });
            }

            // Initialize active state
            document.addEventListener('DOMContentLoaded', () => {
                const activeBtn = document.querySelector('.filter-btn.active');
                if (activeBtn) {
                    activeBtn.classList.add('bg-blue-500', 'text-white');
                    activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
                }
            });
        </script>
    </body>
</html>
