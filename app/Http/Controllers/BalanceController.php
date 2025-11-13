<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BalanceTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    /**
     * Display balance page
     */
    public function index()
    {
        $user = Auth::user();

        // Get recent transactions
        $transactions = BalanceTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate stats for current month
        $totalTopupThisMonth = BalanceTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $totalSpentThisMonth = BalanceTransaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        // Get referral count
        $referralCount = $user->referrals()->count();

        return view('balance', compact(
            'transactions',
            'totalTopupThisMonth',
            'totalSpentThisMonth',
            'referralCount'
        ));
    }

    /**
     * Show all transactions with filtering
     */
    public function transactionHistory()
    {
        $user = Auth::user();

        $transactions = BalanceTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('balance.transaction-history', compact('transactions'));
    }

    /**
     * Process top up request
     */
    public function topup(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'amount' => 'required|string',
            'payment_method' => 'required|in:credit_card,qris,bank_transfer',
            'bank' => 'required_if:payment_method,bank_transfer|in:bca,mandiri,bni,bri',
            'card_id' => 'nullable|string',
            'card_number' => 'nullable|string|regex:/^[0-9]{13,19}$/',
            'card_expiry' => 'nullable|string|regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/',
            'card_cvv' => 'nullable|string|regex:/^[0-9]{3,4}$/',
            'card_name' => 'nullable|string|max:255',
            'save_card' => 'nullable|boolean',
        ]);

        // SERVER-SIDE: Extract and validate numeric amount
        $amount = (float) preg_replace('/[^0-9]/', '', $validated['amount']);

        // SERVER-SIDE: Validate minimum amount
        if ($amount < 10000) {
            return back()->with('error', 'Minimum top up adalah Rp 10.000');
        }

        // SERVER-SIDE: Validate maximum amount (prevent fraud)
        if ($amount > 100000000) { // 100 million max
            return back()->with('error', 'Maximum top up adalah Rp 100.000.000');
        }

        // SERVER-SIDE: Validate card details if new card
        if ($validated['payment_method'] === 'credit_card' && !isset($validated['card_id'])) {
            if (!isset($validated['card_number']) || !isset($validated['card_expiry']) ||
                !isset($validated['card_cvv']) || !isset($validated['card_name'])) {
                return back()->with('error', 'Data kartu tidak lengkap');
            }

            // Validate card expiry date
            [$month, $year] = explode('/', $validated['card_expiry']);
            $expiryYear = 2000 + (int)$year;
            $expiryDate = \Carbon\Carbon::createFromDate($expiryYear, $month, 1)->endOfMonth();

            if ($expiryDate->isPast()) {
                return back()->with('error', 'Kartu sudah kadaluarsa');
            }

            // Validate Luhn algorithm for card number
            if (!$this->validateLuhn($validated['card_number'])) {
                return back()->with('error', 'Nomor kartu tidak valid');
            }
        }

        // SERVER-SIDE: Rate limiting - max 10 top-ups per day
        $todayTopups = BalanceTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->whereDate('created_at', today())
            ->count();

        if ($todayTopups >= 10) {
            return back()->with('error', 'Anda telah mencapai batas top up harian (10 transaksi)');
        }

        // Generate Virtual Account if bank transfer
        $virtualAccount = null;
        if ($validated['payment_method'] === 'bank_transfer') {
            $virtualAccount = BalanceTransaction::generateVirtualAccount(
                $validated['bank'],
                $user->id
            );
        }

        // Create transaction
        $transaction = BalanceTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => $amount,
            'description' => 'Top Up Saldo',
            'payment_method' => $validated['payment_method'],
            'bank' => $validated['bank'] ?? null,
            'virtual_account' => $virtualAccount,
            'status' => 'pending',
        ]);

        // If credit card or QRIS, redirect to payment processing page
        if (in_array($validated['payment_method'], ['credit_card', 'qris'])) {
            // Store transaction ID in session for processing page
            session(['processing_transaction_id' => $transaction->id]);

            // Redirect to processing page (will auto-process after delay)
            return redirect()->route('balance.payment.processing');
        }

        // For bank transfer, redirect to payment instruction page
        return redirect()->route('balance.payment-instruction', $transaction->id);
    }

    /**
     * Show payment processing page with delay
     */
    public function paymentProcessing()
    {
        $transactionId = session('processing_transaction_id');

        if (!$transactionId) {
            return redirect()->route('balance');
        }

        $transaction = BalanceTransaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('balance.payment-processing', compact('transaction'));
    }

    /**
     * Process the payment (called after delay from processing page)
     */
    public function processPayment($transactionId)
    {
        $transaction = BalanceTransaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Simulate payment gateway processing
        sleep(1); // Small server-side delay

        // Update transaction status
        $transaction->update(['status' => 'success']);

        // Add balance to user in a DB transaction (atomic operation)
        DB::transaction(function () use ($transaction) {
            $transaction->user->increment('balance', $transaction->amount);
        });

        // Clear session
        session()->forget('processing_transaction_id');

        return response()->json(['success' => true]);
    }

    /**
     * Validate credit card using Luhn algorithm
     */
    private function validateLuhn($number)
    {
        $number = preg_replace('/\D/', '', $number);
        $sum = 0;
        $numDigits = strlen($number);
        $parity = $numDigits % 2;

        for ($i = 0; $i < $numDigits; $i++) {
            $digit = (int)$number[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
            }
            if ($digit > 9) {
                $digit -= 9;
            }
            $sum += $digit;
        }

        return ($sum % 10) == 0;
    }

    /**
     * Show payment instruction page (for bank transfer)
     */
    public function paymentInstruction($transactionId)
    {
        $transaction = BalanceTransaction::findOrFail($transactionId);

        // Ensure transaction belongs to current user
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('balance.payment-instruction', compact('transaction'));
    }

    /**
     * Simulate payment confirmation (webhook callback in real app)
     */
    public function confirmPayment($transactionId)
    {
        $transaction = BalanceTransaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        DB::transaction(function () use ($transaction) {
            $transaction->update(['status' => 'success']);
            $transaction->user->increment('balance', $transaction->amount);
        });

        return redirect()->route('balance.payment.success', $transaction->id);
    }

    public function paymentSuccess($transactionId)
    {
        $transaction = BalanceTransaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->where('status', 'success')
            ->firstOrFail();

        // Pass data in format compatible with payment-success view
        return view('payment-success', [
            'transaction' => $transaction,
            'transactionType' => 'balance_topup',
            'orderNumber' => 'BAL-' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT),
            'order' => null, // No order for balance top-up
        ]);
    }

    public function referralEarnings()
    {
        $user = Auth::user();

        // Get all referred users with their ACTUAL commission earned (server-side calculation)
        $referrals = User::where('referred_by', $user->id)
            ->withCount(['orders as total_rentals' => function($query) {
                $query->where('status', 'completed');
            }])
            ->get()
            ->map(function($referral) use ($user) {
                // Calculate ACTUAL commission from balance_transactions table (SECURE)
                $referral->earned_commission = BalanceTransaction::where('user_id', $user->id)
                    ->where('type', 'credit')
                    ->where('description', 'like', 'Komisi Referral dari ' . $referral->name . '%')
                    ->sum('amount');

                return $referral;
            });

        // Calculate total earnings from referral commissions
        $totalEarnings = BalanceTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('description', 'like', 'Komisi Referral%')
            ->sum('amount');

        // Get recent commission transactions
        $commissionTransactions = BalanceTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('description', 'like', 'Komisi Referral%')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('balance.referral-earnings', compact('referrals', 'totalEarnings', 'commissionTransactions'));
    }
}
