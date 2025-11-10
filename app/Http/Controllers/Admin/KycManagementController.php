<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserKyc;
use Illuminate\Http\Request;

class KycManagementController extends Controller
{
    public function index()
    {
        $kycs = UserKyc::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'expired')")
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);
        
        return view('admin.kyc.index', compact('kycs'));
    }

    public function show(UserKyc $kyc)
    {
        $kyc->load('user', 'reviewer');
        return view('admin.kyc.show', compact('kyc'));
    }

    public function approve(UserKyc $kyc)
    {
        $kyc->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Update user's KYC status
        $kyc->user->update([
            'kyc_verified' => true,
            'kyc_verified_at' => now(),
        ]);

        return back()->with('success', 'KYC approved successfully!');
    }

    public function reject(Request $request, UserKyc $kyc)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $kyc->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Update user's KYC status
        $kyc->user->update([
            'kyc_verified' => false,
            'kyc_verified_at' => null,
        ]);

        return back()->with('success', 'KYC rejected.');
    }
}
