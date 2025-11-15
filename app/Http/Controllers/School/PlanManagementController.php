<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Traits\HandlesS3Storage;
use App\Models\FileSubmission;
use App\Models\School;
use Illuminate\Http\Request;

class PlanManagementController extends Controller
{

    use HandlesS3Storage;

    public function index(Request $request)
    {
        $schoolSlug = $request->route('school');
        $school = is_string($schoolSlug)
            ? School::where('slug', $schoolSlug)->firstOrFail()
            : $schoolSlug;

        $currentSubscription = $school->activeSubscription()->with('plan')->first();

        return view('plan-management.index', compact('school', 'currentSubscription'));
    }


    public function download(Request $request, FileSubmission $plan)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        if ($plan->school_id !== $school->id || $plan->submission_type !== 'plan') {
            abort(404);
        }

        // Use trait method
        return $this->downloadFile($plan);
    }


}
