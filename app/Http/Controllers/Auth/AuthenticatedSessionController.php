<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Logging\SecurityLogger;
use App\Models\Network;
use App\Models\School;
use App\Services\ActiveContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Network $network, School $branch): View
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        return view('auth.login', [
            'network' => $network,
            'school' => $branch,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, Network $network, School $branch): RedirectResponse
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

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
            if ($user->network_id !== $branch->network_id) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'This account does not manage the selected network.',
                ])->withInput($request->only('email'));
            }

            SecurityLogger::logSuccessfulLogin($user);
            $user->updateLastLogin();
            $request->session()->regenerate();
            Auth::login($user, true);

            return redirect()->route('main-admin.dashboard', ['network' => $user->network?->slug]);
        }

        $assignedRoles = $user->schoolUserRoles()
            ->where('school_id', $branch->id)
            ->pluck('role')
            ->toArray();

        // Check if user belongs to this school
        if (empty($assignedRoles)) {
            SecurityLogger::logTenantIsolationBreach($branch->id, $user->school_id);
            Auth::logout();

            return back()->withErrors([
                'email' => 'Invalid credentials for this school.',
            ])->withInput($request->only('email'));
        }

        // Check if user is active (not deactivated)
        if (! $user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact your administrator.',
            ])->withInput($request->only('email'));
        }

        // Check if school is active
        if (! $branch->isActiveWithNetwork()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'This school is currently inactive. Please contact support.',
            ])->withInput($request->only('email'));
        }

        // Check for active subscription (skip for super admin)
        if (! $user->is_super_admin && ! $branch->hasActiveSubscription()) {
            // Allow admin's first login to set up subscription
            if ($branch->subscriptions()->count() === 0 && $user->role === 'admin') {
                SecurityLogger::logSuccessfulLogin($user);

                // Update last login timestamp
                $user->updateLastLogin();

                $request->session()->regenerate();

                // Keep user logged in for 30 days
                Auth::login($user, true);

                return redirect()->intended(tenant_dashboard_route($branch, $user));
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

        $effectiveRole = $user->role && in_array($user->role, $assignedRoles)
            ? $user->role
            : collect(['admin', 'supervisor', 'teacher'])
                ->first(fn ($role) => in_array($role, $assignedRoles))
                ?? $assignedRoles[0];

        $ctx = $user->schoolRoles()
            ->where('school_id', $branch->id)
            ->first();

        if ($ctx) {
            ActiveContext::setSchool($branch->id);
            ActiveContext::setRole($ctx->role);
        } else {
            ActiveContext::setSchool($branch->id);
            if (! empty($user->role)) {
                ActiveContext::setRole($user->role);
            }
        }

        $user->setAttribute('role', $ctx?->role ?? $effectiveRole);
        $user->setAttribute('school_id', $branch->id);

        if ($user->is_super_admin) {
            return redirect()->route('superadmin.dashboard');
        }

        return redirect()->route('dashboard', [$network, $branch]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request, Network $network, School $branch): RedirectResponse
    {
        // Simply logout without logging (method doesn't exist)
        Auth::guard('web')->logout();

        ActiveContext::clear();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->to(safe_tenant_route('login', $branch));
    }
}
