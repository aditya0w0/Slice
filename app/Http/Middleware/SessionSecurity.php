<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionSecurity
{
    /**
     * Handle an incoming request.
     * Implements session hijacking prevention with IP/User Agent validation.
     * Invisible timeout: Session refreshes automatically with activity.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get current request metadata
            $currentIp = $request->ip();
            $currentUserAgent = $request->userAgent();
            
            // Check if session metadata exists
            if (Session::has('user_ip') && Session::has('user_agent')) {
                $sessionIp = Session::get('user_ip');
                $sessionUserAgent = Session::get('user_agent');
                
                // Validate IP address hasn't changed
                if ($sessionIp !== $currentIp) {
                    // IP changed - potential session hijacking
                    Auth::logout();
                    Session::flush();
                    Session::regenerate();
                    
                    return redirect()->route('login')
                        ->withErrors(['security' => 'Session terminated for security reasons. Please log in again.']);
                }
                
                // Validate User Agent hasn't changed
                if ($sessionUserAgent !== $currentUserAgent) {
                    // User Agent changed - potential session hijacking
                    Auth::logout();
                    Session::flush();
                    Session::regenerate();
                    
                    return redirect()->route('login')
                        ->withErrors(['security' => 'Session terminated for security reasons. Please log in again.']);
                }
            } else {
                // First request after login - store metadata
                Session::put('user_ip', $currentIp);
                Session::put('user_agent', $currentUserAgent);
            }
            
            // Invisible timeout: User won't notice because activity refreshes it
            if (Session::has('last_activity')) {
                $lastActivity = Session::get('last_activity');
                $timeout = 120 * 60; // 2 hours (only triggers if completely idle)
                
                if (time() - $lastActivity > $timeout) {
                    // Only logout if truly idle for 2 hours
                    Auth::logout();
                    Session::flush();
                    Session::regenerate();
                    
                    return redirect()->route('login')
                        ->with('info', 'Your session expired due to inactivity.');
                }
            }
            
            // Automatically refresh activity on every request (invisible to user)
            Session::put('last_activity', time());
            
            // Auto-regenerate session ID every 15 minutes (prevents fixation, invisible to user)
            if (!Session::has('last_regeneration') || (time() - Session::get('last_regeneration')) > 900) {
                Session::regenerate();
                Session::put('last_regeneration', time());
            }
        }
        
        return $next($request);
    }
}
