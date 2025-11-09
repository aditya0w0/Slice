<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // If the caller expects JSON (AJAX/fetch inline login), return a simple JSON success
            if ($request->expectsJson()) {
                return response()->json(['ok' => true, 'user' => ['id' => $user->id, 'name' => $user->name]]);
            }

            $redirect = ($user->is_admin ?? false) ? route('admin.dashboard') : route('dashboard');
            return redirect()->intended($redirect);
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
}
