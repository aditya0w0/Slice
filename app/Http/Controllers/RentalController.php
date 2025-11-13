<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function recipe(Request $request)
    {
        $deviceSlug = $request->query('device');
        $months = (int) $request->query('months', 12);
        $quantity = (int) $request->query('quantity', 1);
        $capacity = $request->query('capacity');

        $device = Device::where('slug', $deviceSlug)->firstOrFail();

        $priceMonthly = (int) ($device->price_monthly ?? 0);
        $subtotal = $priceMonthly * $months * $quantity;
        $total = $subtotal;

        return view('recipe', [
            'device' => $device,
            'months' => $months,
            'quantity' => $quantity,
            'capacity' => $capacity,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
    }

    public function confirm(Request $request)
    {
        $data = $request->validate([
            'device_slug' => 'required|string',
            'months' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'capacity' => 'nullable|string',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
        ]);

        // Update user profile if provided
        $user = Auth::user();
        if (isset($data['full_name'])) {
            $user->legal_name = $data['full_name'];
            $user->phone = $data['phone'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->zip_code = $data['zip_code'];
            $user->save();
        }

        // ==========================================
        // CREDIT SCORE CHECK - PRIMARY GATE
        // ==========================================

        // Update user's credit score (skip for whitelisted users with perfect 850 score)
        if ($user->credit_score !== 850) {
            $user->updateCreditScore();
        }

        // Check KYC status FIRST - must be verified
        if ($user->kyc_status !== 'verified') {
            // Get device for price calculation
            $device = Device::where('slug', $data['device_slug'])->first();

            // Create rejected order for tracking
            $order = Order::create([
                'user_id' => $user->id,
                'variant_slug' => $data['device_slug'],
                'capacity' => $data['capacity'] ?? null,
                'months' => $data['months'],
                'price_monthly' => $device->price_monthly ?? 0,
                'total_price' => 0,
                'status' => 'rejected',
            ]);

            return redirect()->route('payment.failed', $order->id)
                ->with('kyc_required', true);
        }

        // Check if user passes credit check (score + blacklist)
        if (!$user->passesCreditCheck()) {
            // Get device for price calculation
            $device = Device::where('slug', $data['device_slug'])->first();

            $order = Order::create([
                'user_id' => $user->id,
                'variant_slug' => $data['device_slug'],
                'capacity' => $data['capacity'] ?? null,
                'months' => $data['months'],
                'price_monthly' => $device->price_monthly ?? 0,
                'total_price' => 0,
                'status' => 'rejected',
            ]);

            return redirect()->route('payment.failed', $order->id)
                ->with('credit_failure', true);
        }

        // Find the device
        $device = Device::where('slug', $data['device_slug'])->firstOrFail();

        // Calculate base price
        $basePrice = $device->price_monthly * $data['months'] * $data['quantity'];

        // Apply credit score discount (good credit gets rewards!)
        $discountPercentage = $user->getCreditDiscountPercentage();
        $discountAmount = ($basePrice * $discountPercentage) / 100;
        $totalPrice = $basePrice - $discountAmount;

        // Determine payment status based on risk factors
        $paymentStatus = $this->determinePaymentStatus($user, $totalPrice);

        // Map payment status to order status
        switch ($paymentStatus) {
            case 'success':
                $status = 'paid';
                break;
            case 'pending':
                $status = 'pending_review';
                break;
            case 'failed':
                $status = 'rejected';
                break;
            default:
                $status = 'created';
                $paymentStatus = 'pending';
        }

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'variant_slug' => $data['device_slug'],
            'capacity' => $data['capacity'] ?? null,
            'months' => $data['months'],
            'price_monthly' => $device->price_monthly,
            'total_price' => $totalPrice,
            'status' => $status,
        ]);

        // Redirect to appropriate payment status page
        return redirect()->route("payment.{$paymentStatus}", $order->id);
    }

    /**
     * Determine payment status based on advanced risk assessment algorithm
     * Uses weighted scoring with multiple behavioral patterns
     * Returns: 'success', 'pending', or 'failed'
     */
    protected function determinePaymentStatus($user, $totalPrice)
    {
        $riskFactors = [];
        $trustScore = 0;
        $profileScore = 0;
        $missingFields = [];

        // ==========================================
        // CREDIT TIER INTELLIGENCE SYSTEM
        // ==========================================
        // Credit score provides baseline trust adjustment
        // This is the backbone that makes the system production-ready
        $creditTier = $user->credit_tier;
        $creditScore = $user->credit_score;

        // Credit tier risk adjustment (inverse relationship to risk)
        $creditBonus = 0;
        switch ($creditTier) {
            case 'excellent': // 800-850
                $creditBonus = -50; // Massive risk reduction
                $riskFactors['excellent_credit'] = -50;
                break;
            case 'very_good': // 740-799
                $creditBonus = -35; // Strong risk reduction
                $riskFactors['very_good_credit'] = -35;
                break;
            case 'good': // 670-739
                $creditBonus = -20; // Moderate risk reduction
                $riskFactors['good_credit'] = -20;
                break;
            case 'fair': // 580-669
                $creditBonus = 0; // Neutral
                break;
            case 'poor': // 300-579
                $creditBonus = 25; // Risk increase
                $riskFactors['poor_credit'] = 25;
                break;
        }

        // Fine-grained score adjustment (within tier)
        // Higher score within tier = slightly better terms
        $scoreWithinTier = ($creditScore % 100) / 100; // 0.0 to 1.0
        $microAdjustment = -($scoreWithinTier * 5); // -0 to -5 points
        $creditBonus += $microAdjustment;

        // ==========================================
        // FACTOR 1: Trust Score (Account Reputation)
        // ==========================================
        $accountAgeInDays = $user->created_at->diffInDays(now());
        $accountAgeInHours = $user->created_at->diffInHours(now());

        // Exponential decay trust score based on account age
        if ($accountAgeInHours < 1) {
            $trustScore = 0; // Brand new account - zero trust
            $riskFactors['new_account_critical'] = 40;
        } elseif ($accountAgeInDays < 1) {
            $trustScore = 10; // Less than 24 hours
            $riskFactors['new_account_high'] = 30;
        } elseif ($accountAgeInDays < 7) {
            $trustScore = 30; // Less than a week
            $riskFactors['new_account_medium'] = 20;
        } elseif ($accountAgeInDays < 30) {
            $trustScore = 60; // Less than a month
            $riskFactors['new_account_low'] = 10;
        } elseif ($accountAgeInDays < 90) {
            $trustScore = 80; // 1-3 months
            $riskFactors['account_bonus'] = -5; // Slight bonus
        } else {
            $trustScore = 100; // 3+ months - fully trusted
            $riskFactors['veteran_bonus'] = -10; // Trust bonus
        }

        // ==========================================
        // FACTOR 2: Order History Pattern Analysis
        // ==========================================
        $allOrders = Order::where('user_id', $user->id)->get();
        $successfulOrders = $allOrders->where('status', 'paid')->count();
        $totalOrderValue = $allOrders->where('status', 'paid')->sum('total_price');
        $rejectedOrders = $allOrders->where('status', 'rejected')->count();

        // Calculate success rate
        $totalAttempts = $allOrders->count();
        $successRate = $totalAttempts > 0 ? ($successfulOrders / $totalAttempts) * 100 : 0;

        if ($successfulOrders === 0) {
            // First-time buyer - needs verification
            $riskFactors['first_time_buyer'] = 25;

            // But if account is old, reduce risk
            if ($accountAgeInDays > 30) {
                $riskFactors['old_account_first_order'] = -10;
            }

            // SMART: Excellent credit users get benefit of doubt
            if ($creditTier === 'excellent') {
                $riskFactors['first_time_buyer'] = 10; // Reduce from 25 to 10
                $riskFactors['excellent_credit_first_order'] = -5;
            }
        } elseif ($successfulOrders < 3) {
            // New customer (1-2 orders)
            $riskFactors['new_customer'] = 10;
        } else {
            // Loyal customer bonus
            $loyaltyBonus = min($successfulOrders * -2, -15); // Max -15 bonus
            $riskFactors['loyalty_bonus'] = $loyaltyBonus;

            // SMART: Excellent credit + loyalty = trusted
            if ($creditTier === 'excellent' && $successfulOrders >= 5) {
                $riskFactors['trusted_customer'] = -10;
            }
        }

        // Check rejection history
        if ($rejectedOrders > 0) {
            $rejectionPenalty = min($rejectedOrders * 20, 50); // Max 50 penalty

            // SMART: Reduce penalty if credit improved since rejections
            if ($creditTier === 'excellent' || $creditTier === 'very_good') {
                $rejectionPenalty *= 0.5; // Half the penalty
                $riskFactors['credit_improved_since_rejection'] = -($rejectionPenalty * 0.5);
            }

            $riskFactors['rejection_history'] = $rejectionPenalty;
        }

        // Success rate penalty
        if ($successRate < 50 && $totalAttempts >= 3) {
            $riskFactors['low_success_rate'] = 25;

            // SMART: Current good credit can override past failures
            if ($creditScore >= 740) {
                $riskFactors['low_success_rate'] = 10; // Reduce penalty
            }
        }

        // ==========================================
        // FACTOR 3: Transaction Value Analysis
        // ==========================================
        // Compare to user's historical average
        $avgOrderValue = $successfulOrders > 0 ? $totalOrderValue / $successfulOrders : 0;

        if ($totalPrice > 10000000) { // > Rp 10M (~$640)
            $riskFactors['extremely_high_value'] = 35;
        } elseif ($totalPrice > 5000000) { // > Rp 5M (~$320)
            $riskFactors['very_high_value'] = 25;
        } elseif ($totalPrice > 2000000) { // > Rp 2M (~$128)
            $riskFactors['high_value'] = 15;
        }

        // Sudden spike in order value (anomaly detection)
        if ($avgOrderValue > 0) {
            $valueRatio = $totalPrice / $avgOrderValue;
            if ($valueRatio > 5) { // 5x higher than usual
                $riskFactors['anomaly_spike'] = 30;
            } elseif ($valueRatio > 3) { // 3x higher
                $riskFactors['unusual_amount'] = 15;
            } elseif ($valueRatio < 0.5 && $totalPrice < 1000000) {
                // Unusually low order (potential card testing)
                $riskFactors['possible_card_testing'] = 10;
            }
        }

        // ==========================================
        // FACTOR 4: Velocity & Frequency Analysis
        // ==========================================
        $ordersLast1Hour = Order::where('user_id', $user->id)
            ->where('created_at', '>', now()->subHour())
            ->count();

        $ordersLast24Hours = Order::where('user_id', $user->id)
            ->where('created_at', '>', now()->subDay())
            ->count();

        $ordersLast7Days = Order::where('user_id', $user->id)
            ->where('created_at', '>', now()->subDays(7))
            ->count();

        // Rapid-fire orders (fraud pattern)
        if ($ordersLast1Hour >= 3) {
            $riskFactors['rapid_fire_critical'] = 50;

            // SMART: High credit users might be testing variants legitimately
            if ($creditScore >= 800 && $successfulOrders >= 3) {
                $riskFactors['rapid_fire_critical'] = 25; // Reduce by half
                $riskFactors['trusted_rapid_order'] = -10;
            }
        } elseif ($ordersLast1Hour >= 2) {
            $riskFactors['rapid_fire_high'] = 35;

            if ($creditScore >= 740) {
                $riskFactors['rapid_fire_high'] = 15; // Reduce penalty
            }
        }

        // Daily velocity check
        if ($ordersLast24Hours >= 5) {
            $riskFactors['high_daily_velocity'] = 30;
        } elseif ($ordersLast24Hours >= 3) {
            $riskFactors['moderate_daily_velocity'] = 15;
        }

        // Weekly pattern anomaly
        if ($ordersLast7Days >= 10) {
            $riskFactors['abnormal_weekly_pattern'] = 25;
        }

        // ==========================================
        // FACTOR 5: Profile Completeness & Verification
        // ==========================================
        $missingFields = [];

        if (empty($user->phone)) {
            $missingFields[] = 'phone';
            $riskFactors['missing_phone'] = 15;
        } else {
            $profileScore += 25;
        }

        if (empty($user->address)) {
            $missingFields[] = 'address';
            $riskFactors['missing_address'] = 15;
        } else {
            $profileScore += 25;
        }

        if (empty($user->legal_name)) {
            $missingFields[] = 'legal_name';
            $riskFactors['missing_name'] = 10;
        } else {
            $profileScore += 25;
        }

        // Multiple missing fields = red flag
        if (count($missingFields) >= 2) {
            $riskFactors['incomplete_profile_critical'] = 20;
        }

        // Email verification check
        if (isset($user->email_verified_at)) {
            if (!$user->email_verified_at) {
                $riskFactors['unverified_email'] = 20;
            } else {
                $profileScore += 25;
                // Email verified bonus
                $riskFactors['verified_email_bonus'] = -5;
            }
        }

        // Complete profile bonus
        if ($profileScore === 100) {
            $riskFactors['complete_profile_bonus'] = -10;
        }

        // ==========================================
        // FACTOR 6: Behavioral Time Patterns
        // ==========================================
        $currentHour = now()->hour;

        // Orders at suspicious hours (midnight to 5 AM)
        if ($currentHour >= 0 && $currentHour < 5) {
            $riskFactors['suspicious_time'] = 10;
        }

        // ==========================================
        // CALCULATE FINAL RISK SCORE
        // ==========================================
        $totalRiskScore = array_sum($riskFactors);

        // Adjust based on trust score (inverse relationship)
        $trustAdjustment = (100 - $trustScore) * 0.3; // Max 30 points from low trust
        $totalRiskScore += $trustAdjustment;

        // Apply credit tier bonus (this is the intelligence!)
        $totalRiskScore += $creditBonus;

        // ==========================================
        // INTELLIGENT DECISION MATRIX
        // ==========================================

        // TIER 1: Critical rejection (fraud detected)
        if ($totalRiskScore >= 75) {
            // Exception: Excellent credit + veteran account
            if ($creditTier === 'excellent' && $trustScore >= 90 && $successfulOrders >= 5) {
                return 'pending'; // Downgrade to manual review
            }
            return 'failed';
        }

        // TIER 2: Strong rejection (high risk)
        if ($totalRiskScore >= 50) {
            // Exception: Very good or excellent credit + good history
            if (($creditTier === 'excellent' || $creditTier === 'very_good') && $successfulOrders >= 3) {
                return 'pending'; // Downgrade to review
            }

            // Exception: Veteran accounts with reasonable history
            if ($trustScore >= 80 && $successfulOrders >= 5) {
                return 'pending';
            }

            return 'failed';
        }

        // TIER 3: Moderate risk (needs review)
        if ($totalRiskScore >= 25) {
            // Exception: Excellent credit can skip review
            if ($creditTier === 'excellent' && $trustScore >= 60) {
                return 'success'; // Upgrade to approve
            }

            // Exception: Very good credit + established account
            if ($creditTier === 'very_good' && $trustScore >= 80 && $successfulOrders >= 3) {
                return 'success';
            }

            return 'pending';
        }

        // TIER 4: Low risk (minor flags)
        if ($totalRiskScore >= 10) {
            // First-time high-value orders need review (unless excellent credit)
            if ($successfulOrders === 0 && $totalPrice > 3000000 && $creditTier !== 'excellent') {
                return 'pending';
            }

            return 'success';
        }

        // TIER 5: Very low risk - auto approve
        return 'success';
    }

    // Payment status pages
    public function paymentSuccess($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Authorization check
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $device = Device::where('slug', $order->variant_slug)->first();
        $user = $order->user;

        // Calculate discount info (don't expose actual credit score to user)
        $discountPercentage = $user->getCreditDiscountPercentage();

        return view('payment-success', [
            'order' => $order,
            'orderNumber' => $order->invoice_number,
            'device' => $device->name ?? 'Unknown Device',
            'duration' => $order->months,
            'address' => $order->user->address ?? 'N/A',
            'total' => $order->total_price,
            'creditTier' => $user->credit_tier,
            'discountPercentage' => $discountPercentage,
            'transactionType' => null, // Order payment, not balance top-up
        ]);
    }

    public function paymentPending($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Authorization check
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $device = Device::where('slug', $order->variant_slug)->first();

        return view('payment-pending', [
            'order' => $order,
            'orderNumber' => $order->invoice_number,
            'device' => $device->name ?? 'Unknown Device',
            'duration' => $order->months,
            'total' => $order->total_price,
            'submittedAt' => $order->created_at->format('d M Y, H:i'),
        ]);
    }

    public function paymentFailed($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Authorization check
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $device = Device::where('slug', $order->variant_slug)->first();

        return view('payment-failed', [
            'order' => $order,
            'orderNumber' => $order->invoice_number,
            'device' => $device->name ?? 'Unknown Device',
            'duration' => $order->months,
            'total' => $order->total_price,
            'rejectedAt' => $order->updated_at->format('d M Y, H:i'),
        ]);
    }

    public function start(Request $request)
    {
        $data = $request->validate([
            'variant_slug' => 'required|string',
            'months' => 'required|integer|min:1',
            'capacity' => 'nullable|string',
        ]);

        $device = Device::where('slug', $data['variant_slug'])->first();
        if (!$device) {
            return back()->withErrors(['variant_slug' => 'Selected device variant not found']);
        }

        $priceMonthly = (int) ($device->price_monthly ?? 0);
        $months = (int) $data['months'];
        $total = $priceMonthly * $months;

        $order = Order::create([
            'user_id' => Auth::id(),
            'variant_slug' => $data['variant_slug'],
            'capacity' => $data['capacity'] ?? null,
            'months' => $months,
            'price_monthly' => $priceMonthly,
            'total_price' => $total,
            'status' => 'created',
        ]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Order created');
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        // basic authorization: only owner can view
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        return view('orders.receipt', compact('order'));
    }
}
