<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Services\ActiveContext;
use Illuminate\Http\Request;

class ContextSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'school_id' => 'required|integer',
            'role' => 'required|string'
        ]);

        $school = School::findOrFail($request->school_id);

        // verify user belongs to school
        auth()->user()->schoolUserRoles()
            ->where('school_id', $school->id)
            ->where('role', $request->role)
            ->firstOrFail();

        ActiveContext::setSchool($school->id);
        ActiveContext::setRole($request->role);

        return redirect()->to(
            tenant_route('dashboard', $school)
        );
    }
}
