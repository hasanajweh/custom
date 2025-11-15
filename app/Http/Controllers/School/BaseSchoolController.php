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

        if (!$user->is_super_admin && $user->school_id !== $school->id) {
            abort(403, 'Unauthorized access to this school.');
        }
    }
}
