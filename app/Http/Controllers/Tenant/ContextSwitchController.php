<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\ActiveContext;
use App\Services\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContextSwitchController extends Controller
{
    /**
     * Handle GET requests to switch-context (redirect to dashboard with error).
     * Used when someone accidentally navigates to the switch URL via GET.
     */
    public function show(Request $request): RedirectResponse
    {
        return $this->redirectToDashboard();
    }

    /**
     * Handle GET requests to global switch route.
     */
    public function showGlobal(Request $request): RedirectResponse
    {
        return $this->redirectToDashboard();
    }

    /**
     * Redirect user to their appropriate dashboard.
     */
    private function redirectToDashboard(): RedirectResponse
    {
        $user = Auth::user();
        
        if (! $user) {
            return redirect('/');
        }

        $activeSchool = ActiveContext::getSchool();
        $activeRole = ActiveContext::getRole();

        if ($activeSchool && $activeRole) {
            $dashboardRoute = match ($activeRole) {
                'admin' => 'school.admin.dashboard',
                'teacher' => 'teacher.dashboard',
                'supervisor' => 'supervisor.dashboard',
                default => 'dashboard',
            };

            return redirect()->to(tenant_route($dashboardRoute, $activeSchool));
        }

        // Try to get first available context
        $firstContext = $user->schoolUserRoles()->with('school.network')->first();
        
        if ($firstContext && $firstContext->school) {
            ActiveContext::setContext($firstContext->school->id, $firstContext->role);
            return redirect()->to(tenant_route('dashboard', $firstContext->school));
        }

        return redirect('/');
    }

    /**
     * Switch context via tenant-prefixed route.
     */
    public function switch(Request $request): RedirectResponse
    {
        return $this->performSwitch($request);
    }

    /**
     * Switch context via global route (more reliable, no route binding issues).
     */
    public function switchGlobal(Request $request): RedirectResponse
    {
        return $this->performSwitch($request);
    }

    /**
     * Perform the actual context switch.
     * 
     * This method validates the user has the requested role in the requested school,
     * sets the ActiveContext, and redirects to the correct dashboard.
     */
    private function performSwitch(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/')->with('error', __('messages.auth.unauthorized'));
        }

        $validated = $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role' => ['required', 'in:admin,teacher,supervisor'],
        ]);

        // Get the target school with its network
        $school = School::with('network')->find($validated['school_id']);

        if (! $school || ! $school->network) {
            Log::warning('Context switch failed: school or network not found', [
                'user_id' => $user->id,
                'school_id' => $validated['school_id'],
            ]);
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        // Validate user has this role in this school
        $assignment = $user
            ->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $validated['role'])
            ->first();

        if (! $assignment) {
            Log::warning('Context switch failed: user does not have role in school', [
                'user_id' => $user->id,
                'school_id' => $school->id,
                'requested_role' => $validated['role'],
            ]);
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        // Set ActiveContext (and clear cached tenant context) using the service
        ActiveContext::setContext($school->id, $validated['role']);
        TenantContext::setActiveContext($school->id, $validated['role']);

        Log::info('Context switched successfully', [
            'user_id' => $user->id,
            'school_id' => $school->id,
            'role' => $validated['role'],
            'school_slug' => $school->slug,
            'network_slug' => $school->network->slug,
        ]);

        // Determine the correct dashboard route based on role
        $dashboardRoute = match ($validated['role']) {
            'admin' => 'school.admin.dashboard',
            'teacher' => 'teacher.dashboard',
            'supervisor' => 'supervisor.dashboard',
            default => 'dashboard',
        };

        // Generate the correct tenant URL using the school's slugs
        $redirectUrl = tenant_route($dashboardRoute, $school);

        return redirect()->to($redirectUrl);
    }
}
