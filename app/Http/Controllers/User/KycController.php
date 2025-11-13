<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycSubmissionRequest;
use App\Models\UserKyc;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KycController extends Controller
{
    /**
     * Show the KYC verification form
     */
    public function create()
    {
        $user = auth()->user();
        $kyc = UserKyc::where('user_id', $user->id)->first();

        return view('kyc.index', compact('user', 'kyc'));
    }

    /**
     * Store KYC verification submission with comprehensive security validation
     */
    public function store(KycSubmissionRequest $request)
    {
        // All validation happens automatically in KycSubmissionRequest
        // Data is already sanitized by prepareForValidation()

        $user = auth()->user();

        // Check if user already has a pending or approved KYC
        $existingKyc = UserKyc::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingKyc) {
            if ($existingKyc->status === 'approved') {
                return redirect()->back()->with('error', 'Your identity is already verified.');
            }
            if ($existingKyc->status === 'pending') {
                return redirect()->back()->with('error', 'Your KYC submission is already under review. Please wait for admin approval.');
            }
        }

        try {
            // Validate file MIME types server-side (additional security)
            $idFront = $request->file('id_front');
            $selfie = $request->file('selfie');

            if (!$this->isValidImage($idFront)) {
                throw new \Exception('ID front photo is not a valid image file.');
            }
            if (!$this->isValidImage($selfie)) {
                throw new \Exception('Selfie photo is not a valid image file.');
            }

            // Store uploaded images with secure filenames
            $idFrontPath = $idFront->store('kyc/id_cards', 'public');
            $selfiePath = $selfie->store('kyc/selfies', 'public');

            $idBackPath = null;
            if ($request->hasFile('id_back')) {
                $idBack = $request->file('id_back');
                if (!$this->isValidImage($idBack)) {
                    throw new \Exception('ID back photo is not a valid image file.');
                }
                $idBackPath = $idBack->store('kyc/id_cards', 'public');
            }
        } catch (\Exception $e) {
            Log::error('KYC file upload failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'File upload failed. Please ensure all files are valid images.');
        }

        // Map id_type to document_type (database column name)
        $documentTypeMap = [
            'ktp' => 'national_id',
            'passport' => 'passport',
            'sim' => 'driver_license',
        ];

        // Create or update KYC record - using actual database column names
        UserKyc::updateOrCreate(
            ['user_id' => $user->id],
            [
                'document_type' => $documentTypeMap[$request->id_type],
                'document_number' => $request->id_number,
                'document_front_image' => $idFrontPath,
                'document_back_image' => $idBackPath,
                'selfie_image' => $selfiePath,
                'status' => 'pending',
                'submitted_at' => now(),
            ]
        );

        // Create notification for user
        Notification::create([
            'user_id' => $user->id,
            'title' => 'KYC Verification Submitted',
            'message' => 'Your KYC documents have been submitted successfully. We will review your documents within 1-2 business days.',
            'type' => 'system',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'KYC verification submitted successfully! We will review your documents within 1-2 business days.');
    }

    /**
     * Show KYC verification status
     */
    public function status()
    {
        $user = auth()->user();
        $kyc = UserKyc::where('user_id', $user->id)->first();

        return view('kyc.status', compact('user', 'kyc'));
    }

    /**
     * Validate image file MIME type (server-side security check)
     */
    private function isValidImage($file): bool
    {
        // Check actual MIME type (not just extension)
        $mimeType = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];

        if (!in_array($mimeType, $allowedMimes)) {
            return false;
        }

        // Additional check: try to get image dimensions (confirms it's a real image)
        try {
            $imageSize = getimagesize($file->getRealPath());
            if ($imageSize === false) {
                return false;
            }

            // Check if dimensions are reasonable
            if ($imageSize[0] < 300 || $imageSize[1] < 300) {
                return false;
            }

            if ($imageSize[0] > 8000 || $imageSize[1] > 8000) {
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Image validation failed: ' . $e->getMessage());
            return false;
        }

        return true;
    }
}

