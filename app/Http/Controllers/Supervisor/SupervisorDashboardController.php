<?php
// app/Http/Controllers/Supervisor/SupervisorDashboardController.php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Models\FileSubmission;
use App\Models\SupervisorSubject;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisorDashboardController extends Controller
{
    public function index(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $supervisor = Auth::user();

        // Get supervisor's subjects
        $subjectIds = SupervisorSubject::where('supervisor_id', $supervisor->id)
            ->pluck('subject_id')
            ->toArray(); // Convert to array

        // If supervisor has no subjects assigned, show empty dashboard
        if (empty($subjectIds)) {
            return view('supervisor.dashboard', [
                'school' => $school,
                'totalReviewed' => 0,
                'thisWeekReviews' => 0,
                'totalTeachers' => 0,
                'avgReviewTime' => 0,
                'recentFiles' => collect([]),
                'subjectStats' => collect([]),
                'recentActivity' => collect([])
            ]);
        }

        // Statistics
        $totalReviewed = FileSubmission::whereIn('subject_id', $subjectIds)
            ->where('school_id', $school->id)
            ->count();

        $thisWeekReviews = FileSubmission::whereIn('subject_id', $subjectIds)
            ->where('school_id', $school->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $totalTeachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->count();

        // Average review time (mock data for now)
        $avgReviewTime = 12; // minutes

        // Recent files to review - excluding plans
        $recentFiles = FileSubmission::whereIn('subject_id', $subjectIds)
            ->where('school_id', $school->id)
            ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan'])
            ->with(['user', 'subject', 'grade'])
            ->latest()
            ->take(5)
            ->get();

        // Subject statistics
        $subjectStats = DB::table('subjects')
            ->leftJoin('file_submissions', function($join) use ($school) {
                $join->on('subjects.id', '=', 'file_submissions.subject_id')
                    ->where('file_submissions.school_id', '=', $school->id);
            })
            ->whereIn('subjects.id', $subjectIds)
            ->select(
                'subjects.id',
                'subjects.name',
                DB::raw('COUNT(DISTINCT file_submissions.id) as files_count'),
                DB::raw('COUNT(DISTINCT file_submissions.user_id) as teachers_count'),
                DB::raw('SUM(CASE WHEN file_submissions.created_at >= "' . now()->startOfWeek() . '" THEN 1 ELSE 0 END) as week_files'),
                DB::raw('COALESCE(SUM(file_submissions.download_count), 0) as downloads')
            )
            ->groupBy('subjects.id', 'subjects.name')
            ->get();

        // Recent activity - excluding plans
        $recentActivity = FileSubmission::whereIn('subject_id', $subjectIds)
            ->where('school_id', $school->id)
            ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan'])
            ->with(['user', 'subject'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function($file) {
                return (object)[
                    'file' => $file,
                    'created_at' => $file->created_at
                ];
            });

        return view('supervisor.dashboard', compact(
            'school',
            'totalReviewed',
            'thisWeekReviews',
            'totalTeachers',
            'avgReviewTime',
            'recentFiles',
            'subjectStats',
            'recentActivity'
        ));
    }

    public function files(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $supervisor = Auth::user();

        // Get supervisor's subjects
        $subjectIds = SupervisorSubject::where('supervisor_id', $supervisor->id)
            ->pluck('subject_id')
            ->toArray();

        // Get all subjects for filters
        $subjects = Subject::where('school_id', $school->id)
            ->whereIn('id', $subjectIds)
            ->get();

        // Build query
        $query = FileSubmission::where('school_id', $school->id)
            ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan'])
            ->with(['user', 'subject', 'grade']);

        // Filter by supervisor's subjects
        if (!empty($subjectIds)) {
            $query->whereIn('subject_id', $subjectIds);
        }

        // Apply filters
        if (request()->filled('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('subject_id')) {
            $query->where('subject_id', request('subject_id'));
        }

        if (request()->filled('type')) {
            $query->where('submission_type', request('type'));
        }

        // Paginate
        $files = $query->latest()->paginate(20);

        return view('supervisor.files', compact('school', 'supervisor', 'files', 'subjects'));
    }
}
