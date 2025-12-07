<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\ActiveContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContextSwitchController extends Controller
{
    /**
     * Switch the active school/role context for the authenticated user.
     * 
     * This method validates the user has the requested role in the requested school,
     * sets the ActiveContext, and redirects to the correct dashboard using the
     * school's network and school slugs.
     */
    public function switch(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Not authenticated.');
        }

        $validated = $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role' => ['required', 'in:admin,teacher,supervisor'],
        ]);

        $school = School::with('network')->find($validated['school_id']);

        if (! $school || ! $school->network) {
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        // Validate user has this role in this school
        $assignment = $user
            ->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $validated['role'])
            ->first();

        if (! $assignment) {
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        // Set ActiveContext using the service
        ActiveContext::setSchool($school->id);
        ActiveContext::setRole($validated['role']);

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
