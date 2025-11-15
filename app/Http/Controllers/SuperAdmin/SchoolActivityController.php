<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SchoolActivityController extends Controller
{
    public function index(School $school)
    {
        // Get all activity logs where the subject (the thing being changed)
        // is related to the given school.
        $activities = Activity::whereHasMorph(
            'subject',
            ['App\Models\User', 'App\Models\FileSubmission'], // Add other models you log here
            function ($query) use ($school) {
                $query->where('school_id', $school->id);
            }
        )
            ->with(['causer', 'subject']) // Load the user who caused the event and the subject of the event
            ->latest()
            ->paginate(20);

        return view('superadmin.schools.activity', [
            'school' => $school,
            'activities' => $activities,
        ]);
    }
}
