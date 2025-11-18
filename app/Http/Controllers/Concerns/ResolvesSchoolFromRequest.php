<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Branch;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait ResolvesSchoolFromRequest
{
    protected function resolveSchool(Request $request): School
    {
        $schoolParam = $request->route('school');
        $branchParam = $request->route('branch');

        if ($schoolParam instanceof School) {
            return $schoolParam;
        }

        if ($branchParam instanceof School) {
            return $branchParam;
        }

        if ($branchParam instanceof Branch) {
            return $branchParam;
        }

        if (is_string($schoolParam) && $schoolParam !== '') {
            if ($school = School::where('slug', $schoolParam)->first()) {
                return $school;
            }
        }

        if (is_string($branchParam) && $branchParam !== '') {
            if ($school = School::where('slug', $branchParam)->first()) {
                return $school;
            }
        }

        $schoolSlug = $request->query('school');
        if (is_string($schoolSlug) && $schoolSlug !== '') {
            if ($school = School::where('slug', $schoolSlug)->first()) {
                return $school;
            }
        }

        $user = Auth::user();
        if ($user) {
            if ($user->relationLoaded('school') ? $user->school : $user->school()->exists()) {
                return $user->school;
            }

            if ($user->relationLoaded('branch') ? $user->branch : $user->branch()->exists()) {
                return $user->branch;
            }
        }

        abort(404, 'School not found for this request.');
    }
}
