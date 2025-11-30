<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolUserRole;
use App\Services\ActiveContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContextSwitchController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Not authenticated');
        }

        $validated = $request->validate([
            'school_id' => 'required|integer|exists:schools,id',
            'role' => 'required|string|in:admin,teacher,supervisor',
        ]);

        $school = School::with('network')->findOrFail($validated['school_id']);

        $hasContext = SchoolUserRole::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->where('role', $validated['role'])
            ->exists();

        if (! $hasContext) {
            abort(403, 'You do not have this role in this school.');
        }

        ActiveContext::setSchool($school->id);
        ActiveContext::setRole($validated['role']);

        $target = match ($validated['role']) {
            'admin' => tenant_route('school.admin.dashboard', $school),
            'teacher' => tenant_route('teacher.dashboard', $school),
            'supervisor' => tenant_route('supervisor.dashboard', $school),
            default => abort(403, 'Invalid role'),
        };

        return redirect()->to($target);
    }
}
