<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolUserRole;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContextSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role' => ['required', 'in:admin,teacher,supervisor'],
        ]);

        $school = School::with('network')->find($validated['school_id']);

        $assignment = SchoolUserRole::where('user_id', $user->id)
            ->where('school_id', $validated['school_id'])
            ->where('role', $validated['role'])
            ->first();

        if (! $assignment) {
            $response = redirect()
                ->back()
                ->with('error', __('messages.auth.unauthorized'));

            return $request->expectsJson()
                ? response()->json(['status' => 'error', 'message' => __('messages.auth.unauthorized')], 403)
                : $response;
        }

        TenantContext::setActiveContext($school->id, $validated['role']);
        $user->setAttribute('school_id', $school->id);
        $user->setAttribute('role', $validated['role']);

        $redirect = tenant_dashboard_route($school, $user);

        return $request->expectsJson()
            ? response()->json(['status' => 'ok', 'redirect' => $redirect])
            : redirect()->to($redirect)->with('status', __('messages.switch_context_success'));
    }
}
