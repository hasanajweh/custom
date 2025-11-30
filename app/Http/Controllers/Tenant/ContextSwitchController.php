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
    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role'      => ['required', 'string', 'in:admin,teacher,supervisor'],
        ]);

        $user = Auth::user();
        if (! $user) {
            abort(403);
        }

        $schoolId = (int) $request->input('school_id');
        $role     = $request->input('role');

        // Ensure the user ACTUALLY has this role in that school
        $hasContext = $user->schoolUserRoles()
            ->where('school_id', $schoolId)
            ->where('role', $role)
            ->exists();

        if (! $hasContext) {
            abort(403, 'You are not allowed to use this context.');
        }

        $school = School::with('network')->findOrFail($schoolId);

        ActiveContext::setSchool($schoolId);
        ActiveContext::setRole($role);

        return match ($role) {
            'admin'      => redirect()->to(tenant_route('school.admin.dashboard', $school)),
            'teacher'    => redirect()->to(tenant_route('teacher.dashboard', $school)),
            'supervisor' => redirect()->to(tenant_route('supervisor.dashboard', $school)),
            default      => abort(403, 'Invalid role.'),
        };
    }
}
