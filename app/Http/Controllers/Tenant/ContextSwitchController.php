<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\ActiveContext;
use App\Services\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContextSwitchController extends Controller
{
    /**
     * Handle GET requests to switch-context (redirect to dashboard).
     */
    public function show(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (! $user) {
            return redirect('/');
        }

        // Get current context or first available
        $activeSchool = ActiveContext::getSchool();
        $activeRole = ActiveContext::getRole();

        if ($activeSchool && $activeRole) {
            return redirect()->to(tenant_route($this->getDashboardRoute($activeRole), $activeSchool));
        }

        // Fallback to first available context
        $firstContext = $user->schoolUserRoles()->with('school.network')->first();
        if ($firstContext && $firstContext->school) {
            ActiveContext::setContext($firstContext->school->id, $firstContext->role);
            return redirect()->to(tenant_route('dashboard', $firstContext->school));
        }

        return redirect('/');
    }

    /**
     * Switch the active school/role context for the authenticated user.
     */
    public function switch(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/');
        }

        $validated = $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role' => ['required', 'in:admin,teacher,supervisor'],
        ]);

        // Get the target school with its network
        $school = School::with('network')->find($validated['school_id']);

        if (! $school || ! $school->network) {
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        // Validate user has this role in this school
        $hasRole = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $validated['role'])
            ->exists();

        if (! $hasRole) {
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        // Set the new context
        ActiveContext::setContext($school->id, $validated['role']);
        TenantContext::setActiveContext($school->id, $validated['role']);

        // Redirect to the appropriate dashboard
        $dashboardRoute = $this->getDashboardRoute($validated['role']);
        
        return redirect()->to(tenant_route($dashboardRoute, $school));
    }

    /**
     * Get the dashboard route name for a role.
     */
    private function getDashboardRoute(string $role): string
    {
        return match ($role) {
            'admin' => 'school.admin.dashboard',
            'teacher' => 'teacher.dashboard',
            'supervisor' => 'supervisor.dashboard',
            default => 'dashboard',
        };
    }
}
