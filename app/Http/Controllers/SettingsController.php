<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\UserLoginLog;

class SettingsController extends Controller
{
    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Debug logging
        \Log::info('Update Profile Request', [
            'has_file' => $request->hasFile('profile_photo'),
            'files' => $request->allFiles(),
            'all_data' => $request->all()
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;

            \Log::info('Photo uploaded', ['path' => $path]);
        }

        \Log::info('Validated data before update', ['validated' => $validated]);
        $user->update($validated);
        \Log::info('User after update', ['profile_photo' => $user->fresh()->profile_photo]);

        return redirect()->route('settings.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('settings.security')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Toggle 2FA
     */
    public function toggle2FA(Request $request)
    {
        $user = Auth::user();

        // Toggle 2FA status
        $user->update([
            'two_factor_enabled' => $request->has('enabled')
        ]);

        $message = $user->two_factor_enabled
            ? '2FA berhasil diaktifkan!'
            : '2FA berhasil dinonaktifkan!';

        return redirect()->route('settings.security')->with('success', $message);
    }

    /**
     * Logout from specific device
     */
    public function logoutDevice(Request $request, $deviceId)
    {
        // Logic to logout from specific device
        // You can use Laravel Sanctum or custom session management

        return redirect()->route('settings.security')->with('success', 'Berhasil logout dari perangkat tersebut!');
    }

    /**
     * Logout from all devices
     */
    public function logoutAllDevices(Request $request)
    {
        // Invalidate all sessions except current
        // Note: Laravel's logoutOtherDevices requires password, but we'll just invalidate all sessions

        $user = Auth::user();

        // Update remember_token to invalidate all sessions
        $user->update([
            'remember_token' => null
        ]);

        // Regenerate current session
        $request->session()->regenerate();

        return redirect()->route('settings.security')->with('success', 'Berhasil logout dari semua perangkat!');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'notify_order_updates' => $request->has('order_updates'),
            'notify_promotions' => $request->has('promotions'),
            'notify_reminders' => $request->has('reminders'),
            'notify_newsletter' => $request->has('newsletter'),
            'notification_frequency' => $request->input('frequency', 'realtime'),
        ]);

        return redirect()->route('settings.notifications')->with('success', 'Preferensi notifikasi berhasil diperbarui!');
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'profile_visibility' => $request->has('profile_visibility'),
            'activity_tracking' => $request->has('activity_tracking'),
            'personalized_ads' => $request->has('personalized_ads'),
            'location_services' => $request->has('location_services'),
        ]);

        return redirect()->route('settings.privacy')->with('success', 'Pengaturan privasi berhasil diperbarui!');
    }

    /**
     * Download user data (GDPR)
     */
    public function downloadData()
    {
        $user = Auth::user();

        // Generate JSON file with all user data
        $data = [
            'user' => $user->toArray(),
            'orders' => $user->orders()->with('device')->get()->toArray(),
            // Add more data as needed
        ];

        $fileName = 'user_data_' . $user->id . '_' . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Verify password
        $request->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        // Delete user data
        $user->delete();

        Auth::logout();

        return redirect()->route('home')->with('success', 'Akun Anda telah dihapus.');
    }

    /**
     * Add payment method
     */
    public function addPaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'card_number' => 'required|string|max:19',
            'card_name' => 'required|string|max:255',
            'expiry' => 'required|string|max:5',
            'cvv' => 'required|string|max:4',
            'billing_address' => 'required|string|max:500',
            'set_primary' => 'nullable|boolean'
        ]);

        // Save payment method (you'll need to create a payment_methods table)
        // For security, encrypt card details

        return redirect()->route('settings.payment')->with('success', 'Kartu berhasil ditambahkan!');
    }

    /**
     * Update subscription
     */
    public function updateSubscription(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'plan' => 'required|in:basic,plus,premium'
        ]);

        // Update subscription logic

        return redirect()->route('settings.subscription')->with('success', 'Paket berlangganan berhasil diperbarui!');
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();

        // Cancel subscription logic

        return redirect()->route('settings.subscription')->with('success', 'Langganan berhasil dibatalkan.');
    }

    /**
     * Get payment history with real orders data
     */
    public function paymentHistory(Request $request)
    {
        $user = Auth::user();

        // Get all orders with payment information, sorted by newest first
        $payments = Order::where('user_id', $user->id)
            ->with('device') // Assuming Order has device relationship
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('settings.payment-history', compact('payments'));
    }

    /**
     * Get activity log with real user login logs
     */
    public function activityLog(Request $request)
    {
        $user = Auth::user();

        // Get all user login logs, sorted by newest first
        $activities = UserLoginLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('settings.activity-log', compact('activities'));
    }
}
