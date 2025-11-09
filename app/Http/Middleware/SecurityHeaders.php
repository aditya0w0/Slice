<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Basic recommended security headers. Tweak values for your environment.
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // HSTS only when served over HTTPS. We set it here defensively; in local dev
        // you can omit or adjust it. In production make sure HTTPS is enabled before
        // enabling HSTS.
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content Security Policy (basic). This is conservative and may require tuning.
    // During development (any non-production environment) we allow common
    // Vite dev origins so the dev server client and HMR websocket are
    // permitted. This covers setups where APP_ENV is "development" or
    // similar instead of "local". In production we keep a much stricter
    // policy.
    if (!app()->environment('production')) {
            $viteOrigins = [
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'http://[::1]:5173',
            ];

            $viteOriginsStr = implode(' ', $viteOrigins);

            $csp = "default-src 'self'; img-src 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' {$viteOriginsStr}; style-src 'self' 'unsafe-inline' {$viteOriginsStr}; connect-src 'self' ws://localhost:5173 ws://127.0.0.1:5173 ws://[::1]:5173;";
        } else {
            $csp = "default-src 'self'; img-src 'self' data: https:; script-src 'self'; style-src 'self';";
        }
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
