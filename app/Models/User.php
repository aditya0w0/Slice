<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'legal_name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'date_of_birth',
        'id_number',
        'is_admin',
        'kyc_verified',
        'kyc_verified_at',
        'kyc_status',
        'kyc_id_number',
        'kyc_id_photo',
        'kyc_selfie_photo',
        'kyc_rejection_reason',
        'credit_score',
        'credit_tier',
        'credit_score_updated_at',
        'is_blacklisted',
        'blacklist_reason',
        // Profile fields
        'profile_photo',
        'two_factor_enabled',
        // Notification preferences
        'notify_order_updates',
        'notify_promotions',
        'notify_reminders',
        'notify_newsletter',
        'notify_security_alerts',
        'notification_frequency',
        // Privacy settings
        'profile_visibility',
        'activity_tracking',
        'personalized_ads',
        'location_services',
        // Subscription
        'subscription_plan',
        'subscription_expires_at',
        // Balance and referral
        'balance',
        'referral_code',
        'referred_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id_number',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'kyc_verified' => 'boolean',
            'kyc_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'credit_score_updated_at' => 'datetime',
            'is_blacklisted' => 'boolean',
            // New fields
            'two_factor_enabled' => 'boolean',
            'notify_order_updates' => 'boolean',
            'notify_promotions' => 'boolean',
            'notify_reminders' => 'boolean',
            'notify_newsletter' => 'boolean',
            'notify_security_alerts' => 'boolean',
            'profile_visibility' => 'boolean',
            'activity_tracking' => 'boolean',
            'personalized_ads' => 'boolean',
            'location_services' => 'boolean',
            'subscription_expires_at' => 'datetime',
            'balance' => 'decimal:2',
        ];
    }

    /**
     * Get balance transactions
     */
    public function balanceTransactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    /**
     * Get users referred by this user
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->hasMany(\App\Models\Notification::class)->where('is_read', false)->orderBy('created_at', 'desc');
    }

    /**
     * Get the user who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Calculate and update user's credit score based on behavior
     * Uses US credit score scale: 300-850
     */
    public function updateCreditScore()
    {
        $score = 500; // Base score (fair credit)

        // Factor 1: KYC Status (+100 or -100)
        if ($this->kyc_status === 'verified') {
            $score += 100;
        } elseif ($this->kyc_status === 'rejected') {
            $score -= 100;
        } elseif ($this->kyc_status === 'unverified') {
            $score -= 50; // Not verified yet
        }

        // Factor 2: Account Age (max +80)
        $accountAgeInDays = $this->created_at->diffInDays(now());
        if ($accountAgeInDays > 365) {
            $score += 80; // 1+ year
        } elseif ($accountAgeInDays > 180) {
            $score += 60; // 6+ months
        } elseif ($accountAgeInDays > 90) {
            $score += 40; // 3+ months
        } elseif ($accountAgeInDays > 30) {
            $score += 20; // 1+ month
        } elseif ($accountAgeInDays < 7) {
            $score -= 30; // Very new account
        }

        // Factor 3: Order History (max +120)
        $orders = Order::where('user_id', $this->id)->get();
        $successfulOrders = $orders->where('status', 'paid')->count();
        $rejectedOrders = $orders->where('status', 'rejected')->count();

        $score += min($successfulOrders * 8, 120); // +8 per success, max 120
        $score -= $rejectedOrders * 40; // -40 per rejection

        // Factor 4: Success Rate (max +70)
        $totalAttempts = $orders->count();
        if ($totalAttempts > 0) {
            $successRate = ($successfulOrders / $totalAttempts) * 100;
            if ($successRate >= 95) {
                $score += 70;
            } elseif ($successRate >= 85) {
                $score += 50;
            } elseif ($successRate >= 70) {
                $score += 30;
            } elseif ($successRate < 50) {
                $score -= 50;
            }
        }

        // Factor 5: Profile Completeness (max +40)
        $profileFields = [$this->phone, $this->address, $this->legal_name, $this->date_of_birth];
        $completedFields = count(array_filter($profileFields));
        $score += ($completedFields / 4) * 40;

        // Factor 6: Email Verification (+30)
        if ($this->email_verified_at) {
            $score += 30;
        } else {
            $score -= 20;
        }

        // Factor 7: Payment Velocity Penalty
        $recentOrders = Order::where('user_id', $this->id)
            ->where('created_at', '>', now()->subDays(7))
            ->count();
        if ($recentOrders >= 10) {
            $score -= 100; // Suspicious activity
        } elseif ($recentOrders >= 5) {
            $score -= 50;
        }

        // Blacklist penalty
        if ($this->is_blacklisted) {
            $score = 300; // Minimum possible score
        }

        // Clamp between 300-850 (US credit score range)
        $score = max(300, min(850, $score));

        // Determine tier (US credit ranges)
        $tier = 'poor';
        if ($score >= 800) {
            $tier = 'excellent'; // 800-850
        } elseif ($score >= 740) {
            $tier = 'very_good'; // 740-799
        } elseif ($score >= 670) {
            $tier = 'good'; // 670-739
        } elseif ($score >= 580) {
            $tier = 'fair'; // 580-669
        } // else poor (300-579)

        // Update user
        $this->update([
            'credit_score' => $score,
            'credit_tier' => $tier,
            'credit_score_updated_at' => now(),
        ]);

        return $score;
    }

    /**
     * Check if user passes credit check for payment
     */
    public function passesCreditCheck()
    {
        // Blacklisted users never pass
        if ($this->is_blacklisted) {
            return false;
        }

        // KYC MUST be verified to make ANY payment
        if ($this->kyc_status !== 'verified') {
            return false;
        }

        // Credit score must be at least 580 (fair tier minimum)
        return $this->credit_score >= 580;
    }

    /**
     * Get discount percentage based on credit score
     * Excellent credit gets rewarded with discounts
     */
    public function getCreditDiscountPercentage()
    {
        if ($this->credit_score >= 800) {
            return 10; // 10% discount for excellent credit
        } elseif ($this->credit_score >= 740) {
            return 7; // 7% discount for very good credit
        } elseif ($this->credit_score >= 670) {
            return 5; // 5% discount for good credit
        } elseif ($this->credit_score >= 580) {
            return 0; // No discount for fair credit
        }
        return 0; // Poor credit gets no discount
    }

    /**
     * Check if user has perfect credit score (trusted badge)
     */
    public function isTrustedUser()
    {
        return $this->credit_score >= 850 && $this->kyc_status === 'verified' && !$this->is_blacklisted;
    }

    /**
     * Get credit score color for UI (ADMIN ONLY)
     */
    public function getCreditScoreColorAttribute()
    {
        if ($this->credit_score >= 800) return 'green'; // Excellent
        if ($this->credit_score >= 740) return 'blue'; // Very Good
        if ($this->credit_score >= 670) return 'yellow'; // Good
        if ($this->credit_score >= 580) return 'orange'; // Fair
        return 'red'; // Poor
    }

    /**
     * Get the user's KYC submissions
     */
    public function kycs()
    {
        return $this->hasMany(UserKyc::class);
    }

    /**
     * Get the user's latest KYC submission
     */
    public function latestKyc()
    {
        return $this->hasOne(UserKyc::class)->latestOfMany();
    }

    /**
     * Get the user's login logs
     */
    public function loginLogs()
    {
        return $this->hasMany(UserLoginLog::class);
    }

    /**
     * Get the user's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
