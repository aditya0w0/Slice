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

        // Content Security Policy: In dev mode, disable CSP entirely to avoid blocking Vite HMR.
        // CSP will be enforced in production only.
        if (!app()->environment('production')) {
            // Development: allow everything so Vite HMR and inline scripts work without issues
            $csp = "default-src *; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline'; img-src * data:; connect-src * ws: wss:;";
        } else {
            // Production: strict CSP
            $csp = "default-src 'self'; img-src 'self' data: https:; script-src 'self'; style-src 'self'; connect-src 'self';";
        }
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
