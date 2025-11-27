<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContextSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $user = Auth::user();

        $schoolId = $request->input('school_id');
        $role     = $request->input('role');

        // Validate school exists
        $school = School::find($schoolId);
        if (!$school) {
            return response()->json(['status' => 'error', 'message' => 'Invalid school'], 422);
        }

        // Validate user has this role in this school
        $hasRole = SchoolUserRole::where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->where('role', $role)
            ->exists();

        if (!$hasRole) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized role'], 403);
        }

        // SAVE CONTEXT IN SESSION
        session([
            'active_school_id' => $schoolId,
            'active_role' => $role,
        ]);

        // Redirect based on role:
        return response()->json([
            'status' => 'ok',
            'redirect' => match($role) {
                'admin' => tenant_route('school.admin.dashboard', $school),
                'teacher' => tenant_route('teacher.dashboard', $school),
                'supervisor' => tenant_route('supervisor.dashboard', $school),
                default => '/'
            }
        ]);
    }
}
