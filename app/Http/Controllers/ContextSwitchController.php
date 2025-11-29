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
        if (! Auth::check()) {
            abort(403, 'Not authenticated');
        }

        $validated = $request->validate([
            'school_id' => 'required|integer|exists:schools,id',
            'role' => 'required|string|in:admin,teacher,supervisor',
        ]);

        $user = $request->user();
        $schoolId = (int) $validated['school_id'];
        $role = $validated['role'];

        $context = SchoolUserRole::where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->where('role', $role)
            ->first();

        if (! $context) {
            abort(403, 'You do not have this role in this school.');
        }

        $school = School::with('network')->findOrFail($schoolId);

        ActiveContext::setSchool($school->id);
        ActiveContext::setRole($role);

        $target = match ($role) {
            'admin' => tenant_route('school.admin.dashboard', $school),
            'teacher' => tenant_route('teacher.dashboard', $school),
            'supervisor' => tenant_route('supervisor.dashboard', $school),
        };

        return redirect()->to($target);
    }
}
