<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolUserRole;
use App\Services\ActiveContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContextSwitchController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Not authenticated');
        }

        $validated = $request->validate([
            'school_id' => 'required|integer|exists:schools,id',
            'role'      => 'required|string|in:admin,teacher,supervisor',
        ]);

        $schoolId = (int) $validated['school_id'];
        $role     = $validated['role'];

        $hasContext = SchoolUserRole::where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->where('role', $role)
            ->exists();

        if (! $hasContext) {
            abort(403, 'You do not have this role in this school.');
        }

        ActiveContext::setSchool($schoolId);
        ActiveContext::setRole($role);

        $school = School::with('network')->findOrFail($schoolId);
        $network = $school->network;

        if (! $network) {
            abort(500, 'School has no network.');
        }

        switch ($role) {
            case 'admin':
                $url = tenant_route('school.admin.dashboard', $school);
                break;
            case 'teacher':
                $url = tenant_route('teacher.dashboard', $school);
                break;
            case 'supervisor':
                $url = tenant_route('supervisor.dashboard', $school);
                break;
            default:
                abort(500, 'Unknown role.');
        }

        return redirect()->to($url);
    }
}
