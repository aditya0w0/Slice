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

        $data = $request->validate($rules);

        $name = $data['name'] ?? (trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) ?: null);

        $user = User::create([
            'name' => $name,
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

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
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
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
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ipAddress}");
            
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
