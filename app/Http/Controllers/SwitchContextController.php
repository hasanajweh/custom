<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchContextController extends Controller
{
    /**
     * Switch the active school/role context for the authenticated user.
     */
    public function switch(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role' => ['required', 'in:admin,teacher,supervisor'],
        ]);

        $school = School::with('network')->find($validated['school_id']);

        if (! $school) {
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        $assignment = $user
            ->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $validated['role'])
            ->first();

        if (! $assignment) {
            return back()->with('error', __('messages.auth.unauthorized'));
        }

        session(['active_school_id' => $school->id]);
        session(['active_role' => $validated['role']]);

        $routePrefix = $validated['role'] === 'admin' ? 'school.admin' : $validated['role'];

        return redirect()->to(
            tenant_route($routePrefix . '.dashboard', $school)
        );
    }
}
