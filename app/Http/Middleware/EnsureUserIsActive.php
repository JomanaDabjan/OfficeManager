<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // =====================================================================
        // ACCOUNT STATUS CHECK: Prevent Deactivated Users From Accessing System
        // =====================================================================
        // Check whether a user is currently authenticated and whether
        // their account has been deactivated by an administrator.
        if (
            Auth::guard('web')->check() &&
            strtolower(trim(Auth::guard('web')->user()->status ?? '')) === 'deactivated'
        ) {
            // =================================================================
            // LOGOUT STEP 1: Terminate the authenticated user's session
            // =================================================================
            Auth::guard('web')->logout();

            // =================================================================
            // LOGOUT STEP 2: Invalidate the current session
            // =================================================================
            $request->session()->invalidate();

            // =================================================================
            // LOGOUT STEP 3: Regenerate the CSRF token
            // =================================================================
            $request->session()->regenerateToken();

            // =================================================================
            // LOGOUT STEP 4: Redirect the deactivated user to Login
            // =================================================================
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated by an administrator. You have been logged out and cannot access the system.',
            ]);
        }

        // =====================================================================
        // ACCESS GRANTED: Continue the request normally
        // =====================================================================
        return $next($request);
    }
}
