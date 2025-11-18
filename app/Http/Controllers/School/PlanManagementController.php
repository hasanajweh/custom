<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesSchoolFromRequest;
use App\Traits\HandlesS3Storage;
use App\Models\FileSubmission;
use Illuminate\Http\Request;

class PlanManagementController extends Controller
{

    use HandlesS3Storage;
    use ResolvesSchoolFromRequest;

    public function index(Request $request)
    {
        $school = $this->resolveSchool($request);

        $currentSubscription = $school->activeSubscription()->with('plan')->first();

        return view('plan-management.index', compact('school', 'currentSubscription'));
    }


    public function download(Request $request, FileSubmission $plan)
    {
        $school = $this->resolveSchool($request);

        if ($plan->school_id !== $school->id || $plan->submission_type !== 'plan') {
            abort(404);
        }

        // Use trait method
        return $this->downloadFile($plan);
    }


}
