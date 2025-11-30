<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\ActiveContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContextSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role'      => ['required', 'string', 'in:admin,teacher,supervisor'],
        ]);

        $user = Auth::user();
        if (!$user) abort(403);

        $schoolId = (int) $request->school_id;
        $role     = $request->role;

        // Ensure user has this role in this school
        $has = $user->schoolUserRoles()
            ->where('school_id', $schoolId)
            ->where('role', $role)
            ->exists();

        if (!$has) abort(403);

        // Load the school with network
        $school = School::with('network')->findOrFail($schoolId);

        // Store the active context
        ActiveContext::setSchool($schoolId);
        ActiveContext::setRole($role);

        // Redirect to correct dashboard
        return match($role) {
            'admin'      => redirect()->to(tenant_route('school.admin.dashboard', $school)),
            'teacher'    => redirect()->to(tenant_route('teacher.dashboard', $school)),
            'supervisor' => redirect()->to(tenant_route('supervisor.dashboard', $school)),
            default      => abort(403),
        };
    }
}
