<?php
namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesSchoolFromRequest;
use App\Traits\HandlesS3Storage;
use App\Models\FileSubmission;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    use HandlesS3Storage;
    use ResolvesSchoolFromRequest;

    public function index(Request $request)
    {
        $school = $this->resolveSchool($request);

        $query = FileSubmission::where('school_id', $school->id)
            ->whereIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan'])
            ->with(['user', 'subject', 'grade']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter by plan type
        if ($request->filled('plan_type')) {
            $query->where('submission_type', $request->plan_type);
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('user_id', $request->teacher_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by grade
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        // Filter by extension
        if ($request->filled('extension')) {
            $extensions = explode(',', $request->extension);
            $query->where(function($q) use ($extensions) {
                foreach ($extensions as $ext) {
                    $q->orWhere('original_filename', 'like', '%.' . trim($ext));
                }
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $plans = $query->latest()->paginate(20);

        // Get filters data
        $teachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->orderBy('name')
            ->get();
        $subjects = Subject::where('school_id', $school->id)->get();
        $grades = Grade::where('school_id', $school->id)->get();

        // Get stats
        $stats = [
            'total_plans' => FileSubmission::where('school_id', $school->id)
                ->whereIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan'])
                ->count(),
            'daily_plans' => FileSubmission::where('school_id', $school->id)
                ->where('submission_type', 'daily_plan')
                ->count(),
            'weekly_plans' => FileSubmission::where('school_id', $school->id)
                ->where('submission_type', 'weekly_plan')
                ->count(),
            'monthly_plans' => FileSubmission::where('school_id', $school->id)
                ->where('submission_type', 'monthly_plan')
                ->count(),
        ];

        return view('school.admin.plans.index', compact(
            'school',
            'plans',
            'teachers',
            'subjects',
            'grades',
            'stats'
        ));
    }

    public function show(Request $request, $school, FileSubmission $plan)
    {
        $school = $this->resolveSchool($request);

        if ($plan->school_id !== $school->id) {
            abort(404);
        }

        return view('school.admin.plans.show', compact('plan', 'school'));
    }

    public function download(Request $request, $school, FileSubmission $plan)
    {
        $school = $this->resolveSchool($request);

        if ($plan->school_id !== $school->id) {
            abort(404);
        }

        // ✅ Use the trait method - it handles everything now
        return $this->downloadFile($plan);
    }
}
