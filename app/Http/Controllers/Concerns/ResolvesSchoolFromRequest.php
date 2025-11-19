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

        abort(404, 'School not found for this request.');
    }
}
