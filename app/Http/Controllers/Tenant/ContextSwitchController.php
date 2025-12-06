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
            'role' => ['required', 'string', 'in:admin,teacher,supervisor'],
        ]);

        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $schoolId = (int) $request->input('school_id');
        $role = $request->input('role');

        $school = School::with('network')->findOrFail($schoolId);
        $routeNetwork = $request->route('network');

        if ($routeNetwork && $school->network_id !== $routeNetwork->id) {
            abort(404);
        }

        $hasContext = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $role)
            ->exists();

        if (! $hasContext) {
            abort(403);
        }

        ActiveContext::setSchool($school->id);
        ActiveContext::setRole($role);

        return match ($role) {
            'admin' => redirect()->to(tenant_route('school.admin.dashboard', $school)),
            'teacher' => redirect()->to(tenant_route('teacher.dashboard', $school)),
            'supervisor' => redirect()->to(tenant_route('supervisor.dashboard', $school)),
            default => abort(403),
        };
    }
}
