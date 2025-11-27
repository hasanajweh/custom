<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Services\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContextSwitchController extends Controller
{
    public function switch(Request $request, Network $network, School $branch): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'role' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $school = School::findOrFail($validated['school_id']);

        abort_unless($school->network_id === $network->id, 404);

        $hasAssignment = $user->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $validated['role'])
            ->exists();

        if (! $hasAssignment) {
            abort(403, 'Unauthorized context selection.');
        }

        TenantContext::setActiveContext($school->id, $validated['role']);

        return match ($validated['role']) {
            'admin' => redirect()->to(tenant_route('school.admin.dashboard', $school)),
            'teacher' => redirect()->to(tenant_route('teacher.dashboard', $school)),
            'supervisor' => redirect()->to(tenant_route('supervisor.dashboard', $school)),
            default => redirect()->back(),
        };
    }
}
