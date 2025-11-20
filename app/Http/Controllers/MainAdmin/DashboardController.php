<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\FileSubmission;
use App\Models\Network;
use App\Models\SchoolUserRole;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public function index(Network $network): View
    {
        App::setLocale('ar');

        $branches = $network->branches()->withCount([
            'subjects',
            'grades',
            'fileSubmissions',
            'fileSubmissions as recent_files_count' => function ($query) {
                $query->where('created_at', '>=', now()->subHours(72));
            },
            'fileSubmissions as plans_count' => function ($query) {
                $query->where('submission_type', 'plan');
            },
        ])->get();

        $branchIds = $branches->pluck('id');

        $roleCounts = SchoolUserRole::whereIn('school_id', $branchIds)
            ->selectRaw('role, COUNT(DISTINCT user_id) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $branchRoleCounts = SchoolUserRole::whereIn('school_id', $branchIds)
            ->selectRaw('school_id, role, COUNT(DISTINCT user_id) as total')
            ->groupBy('school_id', 'role')
            ->get()
            ->groupBy('school_id');

        $branchUserTotals = SchoolUserRole::whereIn('school_id', $branchIds)
            ->selectRaw('school_id, COUNT(DISTINCT user_id) as total')
            ->groupBy('school_id')
            ->pluck('total', 'school_id');

        $branches->transform(function ($branch) use ($branchRoleCounts, $branchUserTotals) {
            $branch->admins_count = $branchRoleCounts[$branch->id]?->firstWhere('role', 'admin')?->total ?? 0;
            $branch->supervisors_count = $branchRoleCounts[$branch->id]?->firstWhere('role', 'supervisor')?->total ?? 0;
            $branch->teachers_count = $branchRoleCounts[$branch->id]?->firstWhere('role', 'teacher')?->total ?? 0;
            $branch->users_count = $branchUserTotals[$branch->id] ?? 0;

            return $branch;
        });

        $recentUploads = FileSubmission::with(['school', 'user'])
            ->whereIn('school_id', $branchIds)
            ->where('created_at', '>=', now()->subHours(72))
            ->latest()
            ->take(10)
            ->get();

        $summary = [
            'branches' => $branches->count(),
            'files' => FileSubmission::whereIn('school_id', $branchIds)->count(),
            'plans' => FileSubmission::whereIn('school_id', $branchIds)->where('submission_type', 'plan')->count(),
            'subjects' => $branches->sum('subjects_count'),
            'grades' => $branches->sum('grades_count'),
            'recent_files' => $recentUploads->count(),
            'admins' => $roleCounts['admin'] ?? 0,
            'supervisors' => $roleCounts['supervisor'] ?? 0,
            'teachers' => $roleCounts['teacher'] ?? 0,
        ];

        return view('main-admin.dashboard', [
            'network' => $network,
            'branches' => $branches,
            'summary' => $summary,
            'recentUploads' => $recentUploads,
        ]);
    }
}
