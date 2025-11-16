<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Logging\SecurityLogger;
use App\Models\Network;
use App\Models\School;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Network $network, School $school): View
    {
        return view('auth.login', [
            'school' => $school
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, Network $network, School $school): RedirectResponse
    {
        // Attempt authentication
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log failed attempt
            SecurityLogger::logFailedLogin(
                $request->input('email'),
                $request->ip()
            );
            throw $e;
        }

        $user = Auth::user();

        if ($user->role === 'main_admin') {
            if ($user->network_id !== $school->network_id) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'This account does not manage the selected network.',
                ])->withInput($request->only('email'));
            }

            SecurityLogger::logSuccessfulLogin($user);
            $user->updateLastLogin();
            $request->session()->regenerate();
            Auth::login($user, true);

            return redirect()->route('main-admin.dashboard', $user->network?->slug);
        }

        // Check if user belongs to this school
        if ($user->school_id !== $school->id) {
            SecurityLogger::logTenantIsolationBreach($school->id, $user->school_id);
            Auth::logout();

            return back()->withErrors([
                'email' => 'Invalid credentials for this school.',
            ])->withInput($request->only('email'));
        }

        // Check if user is active (not deactivated)
        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact your administrator.',
            ])->withInput($request->only('email'));
        }

        // Check if school is active
        if (!$school->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'This school is currently inactive. Please contact support.',
            ])->withInput($request->only('email'));
        }

        // Check for active subscription (skip for super admin)
        if (!$user->is_super_admin && !$school->hasActiveSubscription()) {
            // Allow admin's first login to set up subscription
            if ($school->subscriptions()->count() === 0 && $user->role === 'admin') {
                SecurityLogger::logSuccessfulLogin($user);

                // Update last login timestamp
                $user->updateLastLogin();

                $request->session()->regenerate();

                // Keep user logged in for 30 days
                Auth::login($user, true);

                return redirect()->intended(tenant_route('dashboard', $school));
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'This school\'s subscription is not active. Please contact your administrator.',
            ])->withInput($request->only('email'));
        }

        // Log successful login
        SecurityLogger::logSuccessfulLogin($user);

        // Update last login timestamp
        $user->updateLastLogin();

        $request->session()->regenerate();

        // Keep user logged in for 30 days (remember me = true)
        Auth::login($user, true);

        // Redirect based on user role
        if ($user->is_super_admin) {
            return redirect()->route('superadmin.dashboard');
        }

        return redirect()->intended(tenant_route('dashboard', $school));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request, Network $network, School $school): RedirectResponse
    {
        // Simply logout without logging (method doesn't exist)
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->to(tenant_route('login', $school));
    }
}
