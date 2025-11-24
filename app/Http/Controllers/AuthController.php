<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
            'remember' => ['sometimes','boolean'],
        ]);

        $credentials = ['email' => $data['email'], 'password' => $data['password']];

        if (Auth::attempt($credentials, !empty($data['remember']))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // SECURITY: Store session metadata for hijacking prevention
            // Activity timestamp ensures invisible timeout (auto-refreshes with use)
            session([
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_activity' => time(),
                'last_regeneration' => time(),
            ]);

            // Save login state to cookie (expires in 30 days)
            $rememberDuration = !empty($data['remember']) ? 43200 : 120; // 30 days or 2 hours
            cookie()->queue('user_logged_in', 'true', $rememberDuration);
            cookie()->queue('user_id', $user->id, $rememberDuration);
            cookie()->queue('user_name', $user->name, $rememberDuration);

            // Log the login with IP and geolocation
            $this->logUserLogin($request, $user, 'success');

            // If the caller expects JSON (AJAX/fetch inline login), return a simple JSON success
            if ($request->expectsJson()) {
                return response()->json(['ok' => true, 'user' => ['id' => $user->id, 'name' => $user->name]]);
            }

            $redirect = ($user->is_admin ?? false) ? route('admin.dashboard') : route('dashboard');
            return redirect()->intended($redirect);
        }

        // Log failed login attempt
        if ($request->filled('email')) {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                $this->logUserLogin($request, $user, 'failed');
            }
        }

        // on failed credentials, return JSON errors for AJAX callers, otherwise show validation on the form
        if ($request->expectsJson()) {
            return response()->json(['message' => 'The provided credentials do not match our records.'], 422);
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegister()
    {
        // Prefer the `auth.signup` UI (original design) when present. It now includes CSRF
        // so it's safe to serve. Fall back to `auth.register` otherwise.
        if (view()->exists('auth.signup')) {
            return view('auth.signup');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Support both `signup` form (first_name/last_name) and `register` (name)
        $rules = [
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','confirmed','min:8'],
        ];

        // accept either name or first_name+last_name
        if ($request->filled('name')) {
            $rules['name'] = ['required','string','max:255'];
        } else {
            $rules['first_name'] = ['required','string','max:128'];
            $rules['last_name'] = ['required','string','max:128'];
        }

        // Optional referral code
        if ($request->filled('referral_code')) {
            $rules['referral_code'] = ['string','max:20'];
        }

        $data = $request->validate($rules);

        $name = $data['name'] ?? (trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) ?: null);

        // Check if referred by someone
        $referredBy = null;
        if (!empty($data['referral_code'])) {
            $referrer = User::where('referral_code', $data['referral_code'])->first();
            if ($referrer) {
                $referredBy = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $name,
            'email' => $data['email'],
            'password' => $data['password'],
            'referred_by' => $referredBy,
        ]);

        // Generate unique referral code
        $referralCode = 'SLICE' . strtoupper(substr(md5($user->id . time()), 0, 6));

        // Ensure uniqueness
        $attempts = 0;
        while (User::where('referral_code', $referralCode)->exists() && $attempts < 10) {
            $referralCode = 'SLICE' . strtoupper(substr(md5($user->id . time() . $attempts), 0, 6));
            $attempts++;
        }

        $user->update(['referral_code' => $referralCode]);

        Auth::login($user);
        $request->session()->regenerate();

        // If the registration was performed via AJAX/fetch, return JSON success so the client can stay
        // on the current page and update UI without being forced to a dashboard redirect.
        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'user' => ['id' => $user->id, 'name' => $user->name]]);
        }

        $redirect = ($user->is_admin ?? false) ? route('admin.dashboard') : route('dashboard');
        return redirect()->to($redirect);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // SECURITY: Completely flush all session data
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear login cookies
        cookie()->queue(cookie()->forget('user_logged_in'));
        cookie()->queue(cookie()->forget('user_id'));
        cookie()->queue(cookie()->forget('user_name'));

        return redirect('/')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Log user login with IP address and geolocation
     */
    protected function logUserLogin(Request $request, User $user, string $status = 'success')
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Get geolocation data from ip-api.com (free, no API key needed)
        $geoData = $this->getGeolocation($ipAddress);

        UserLoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'country' => $geoData['country'] ?? null,
            'region' => $geoData['region'] ?? null,
            'city' => $geoData['city'] ?? null,
            'latitude' => $geoData['lat'] ?? null,
            'longitude' => $geoData['lon'] ?? null,
            'timezone' => $geoData['timezone'] ?? null,
            'isp' => $geoData['isp'] ?? null,
            'login_status' => $status,
            'logged_in_at' => now(),
        ]);
    }

    /**
     * Get geolocation data from IP address
     */
    protected function getGeolocation(string $ipAddress): array
    {
        // Skip for local/private IPs
        if (in_array($ipAddress, ['127.0.0.1', '::1']) || str_starts_with($ipAddress, '192.168.') || str_starts_with($ipAddress, '10.')) {
            return [
                'country' => 'Local',
                'region' => 'Localhost',
                'city' => 'Development',
                'lat' => '0',
                'lon' => '0',
                'timezone' => 'UTC',
                'isp' => 'Local Network',
            ];
        }

        try {
            $response = Http::timeout(3)->get("https://ip-api.com/json/{$ipAddress}");

            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'city' => $data['city'] ?? null,
                        'lat' => $data['lat'] ?? null,
                        'lon' => $data['lon'] ?? null,
                        'timezone' => $data['timezone'] ?? null,
                        'isp' => $data['isp'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently fail - login should work even if geolocation fails
            logger()->error('Geolocation API failed: ' . $e->getMessage());
        }

        return [];
    }
}
