<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with user credit scores
     */
    public function users()
    {
        // Only admins can access
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        // Get all users with their order counts and credit scores
        $users = User::withCount([
            
            'orders as successful_orders' => function($query) {
                $query->where('status', 'paid');
            },
            'orders as rejected_orders' => function($query) {
                $query->where('status', 'rejected');
            },
        ])
        ->orderBy('credit_score', 'asc') // Show risky users first
        ->paginate(50);

        return view('admin.users', compact('users'));
    }

    /**
     * Blacklist a user
     */
    public function blacklistUser(Request $request, User $user)
    {
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $user->update([
            'is_blacklisted' => true,
            'blacklist_reason' => $request->reason ?? 'Admin action',
            'credit_score' => 0,
            'credit_tier' => 'poor',
        ]);

        return back()->with('success', "User {$user->name} has been blacklisted");
    }

    /**
     * Unblacklist a user
     */
    public function unblacklistUser(User $user)
    {
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $user->update([
            'is_blacklisted' => false,
            'blacklist_reason' => null,
        ]);

        // Recalculate credit score
        $user->updateCreditScore();

        return back()->with('success', "User {$user->name} has been unblacklisted");
    }

    /**
     * Approve KYC verification
     */
    public function approveKyc(User $user)
    {
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $user->update([
            'kyc_status' => 'verified',
            'kyc_verified' => true,
            'kyc_verified_at' => now(),
        ]);

        // Update credit score
        $user->updateCreditScore();

        return back()->with('success', "KYC approved for {$user->name}");
    }

    /**
     * Reject KYC verification
     */
    public function rejectKyc(Request $request, User $user)
    {
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $user->update([
            'kyc_status' => 'rejected',
            'kyc_rejection_reason' => $request->reason ?? 'Invalid documents',
        ]);

        // Update credit score
        $user->updateCreditScore();

        return back()->with('success', "KYC rejected for {$user->name}");
    }

    /**
     * Show admin profile page
     */
    public function profile()
    {
        return view('admin.profile');
    }

    /**
     * Update admin profile photo
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $user->update(['profile_photo' => $path]);

        return back()->with('success', 'Profile photo updated successfully!');
    }

    /**
     * Delete admin profile photo
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->update(['profile_photo' => null]);
        }

        return back()->with('success', 'Profile photo removed successfully!');
    }
}
