<?php
// app/Http/Controllers/School/BaseSchoolController.php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

abstract class BaseSchoolController extends Controller
{
    protected function getSchool(Request $request): School
    {
        $schoolParam = $request->route('school');

        if ($schoolParam instanceof School) {
            return $schoolParam;
        }

        return School::where('slug', $schoolParam)->firstOrFail();
    }

    protected function authorizeSchoolAccess(School $school): void
    {
        $user = auth()->user();

        // SuperAdmin and MainAdmin can access
        if ($user->is_super_admin) {
            return;
        }
        
        // Main admin exception: can access any school in their network
        if ($user->isMainAdmin()) {
            if ($school->network_id !== $user->network_id) {
                abort(403, 'School does not belong to your network.');
            }
            return;
        }

        // Regular users: must belong to this school
        if ($user->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this school.');
        }
    }
}
