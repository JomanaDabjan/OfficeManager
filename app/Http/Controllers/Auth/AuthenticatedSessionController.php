<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * =========================================================================
 * AUTHENTICATED SESSION CONTROLLER CLASS (SESSION & SECURITY MANAGEMENT)
 * =========================================================================
 * This controller manages the user authentication life cycle, including
 * rendering the login view, processing secure login submissions through
 * the LoginRequest, protecting user sessions, and handling secure logouts.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        // Return the login blade view file to the visitor's browser
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // =====================================================================
        // SECURITY LAYER 1: Authentication & Credential Verification
        // =====================================================================
        // Trigger the LoginRequest to validate input fields, check rate limiting,
        // verify database existence, and check passwords securely.
        $request->authenticate();

        // =====================================================================
        // SECURITY LAYER 2: Session Fixation Protection
        // =====================================================================
        // Regenerate a brand-new session ID for the authenticated user to ensure
        // pre-login temporary visitor session IDs cannot be hijacked by attackers.
        $request->session()->regenerate();


        // =====================================================================
        // REMEMBER ME HANDLING: Explicit Cookie Token Management
        // =====================================================================
        // Check if the user checked the 'remember me' input checkbox in the form.
        if ($request->boolean('remember')) {
            // If checked, we explicitly instruct Laravel's auth guard to remember 
            // the currently authenticated user by generating a persistent token cookie.
            $user = Auth::user();
            Auth::login($user, remember: true);
        } else {
            // If NOT checked, ensure any lingering remember tokens are cleared 
            // so the session expires immediately when the browser is closed.
            $user = Auth::user();
            Auth::login($user, remember: false);
        }

        // =====================================================================
        // SESSION STATE: One-time Flash Flag for First-Login Welcome Modal
        // =====================================================================
        // Set a temporary flash session that triggers the welcome modal exclusively
        // on the initial login request and auto-clears on subsequent page navigations.
        $request->session()->flash('show_welcome_modal', true);

        // =====================================================================
        // NAVIGATION: Safe Redirection to Intended Destination
        // =====================================================================
        // Redirect the user to the unified dashboard page they originally requested.
        return redirect()->intended(route('admin.dash.index', absolute: false));
    }

    /**
     * Destroy an authenticated session (Secure Logout).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // =====================================================================
        // LOGOUT STEP 1: Terminate Web Guard Authentication
        // =====================================================================
        // Log out the currently authenticated user from the main web guard session.
        Auth::guard('web')->logout();

        // =====================================================================
        // LOGOUT STEP 2: Invalidate Session Data
        // =====================================================================
        // Wipe and flush all stored data associated with the current user session.
        $request->session()->invalidate();

        // =====================================================================
        // LOGOUT STEP 3: Regenerate CSRF Token
        // =====================================================================
        // Generate a new CSRF token to prevent cross-site request forgery vulnerabilities.
        $request->session()->regenerateToken();

        // =====================================================================
        // LOGOUT STEP 4: Redirect to Public Homepage
        // =====================================================================
        // Safely redirect the guest back to the root landing page.
        return redirect('/');
    }
}