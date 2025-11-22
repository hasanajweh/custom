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

        // Load branches with file/subject/grade counts
        $branches = $network->branches()->withCount([
            'users',
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

        $branchIds = $branches->pluck('id')->toArray();

        // Global role counts (admin, supervisor, teacher)
        $roleCounts = SchoolUserRole::whereIn('school_id', $branchIds)
            ->selectRaw('role, COUNT(DISTINCT user_id) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        // Role counts per branch
        $branchRoleCounts = SchoolUserRole::whereIn('school_id', $branchIds)
            ->selectRaw('school_id, role, COUNT(DISTINCT user_id) as total')
            ->groupBy('school_id', 'role')
            ->get()
            ->groupBy('school_id')
            ->map(function ($group) {
                return $group->mapWithKeys(function ($row) {
                    return [
                        $row->role => ['total' => (int) $row->total],
                    ];
                });
            });

        // Total users per branch
        $branchUserTotals = SchoolUserRole::whereIn('school_id', $branchIds)
            ->selectRaw('school_id, COUNT(DISTINCT user_id) as total')
            ->groupBy('school_id')
            ->pluck('total', 'school_id')
            ->map(fn ($value) => (int) $value);

        // Inject the computed values into each branch
        $branches->transform(function ($branch) use ($branchRoleCounts, $branchUserTotals) {

            $roleGroup = $branchRoleCounts->get($branch->id, collect());

            $branch->subjects_count = (int) $branch->subjects_count;
            $branch->grades_count = (int) $branch->grades_count;
            $branch->file_submissions_count = (int) $branch->file_submissions_count;
            $branch->recent_files_count = (int) $branch->recent_files_count;
            $branch->plans_count = (int) $branch->plans_count;

            $branch->admins_count = (int) data_get($roleGroup->get('admin'), 'total', 0);
            $branch->supervisors_count = (int) data_get($roleGroup->get('supervisor'), 'total', 0);
            $branch->teachers_count = (int) data_get($roleGroup->get('teacher'), 'total', 0);

            $branch->users_count = (int) $branchUserTotals->get($branch->id, 0);

            return $branch;
        });

        // Recent uploads
        $recentUploads = FileSubmission::with(['school', 'user'])
            ->whereIn('school_id', $branchIds)
            ->where('created_at', '>=', now()->subHours(72))
            ->latest()
            ->take(10)
            ->get();

        // Final summary (SAFE — all integers)
        $summary = [
            'branches' => (int) $branches->count(),
            'files' => (int) FileSubmission::whereIn('school_id', $branchIds)->count(),
            'plans' => (int) FileSubmission::whereIn('school_id', $branchIds)
                ->where('submission_type', 'plan')->count(),
            'subjects' => (int) $branches->sum('subjects_count'),
            'grades' => (int) $branches->sum('grades_count'),
            'recent_files' => (int) $recentUploads->count(),
            'admins' => (int) ($roleCounts['admin'] ?? 0),
            'supervisors' => (int) ($roleCounts['supervisor'] ?? 0),
            'teachers' => (int) ($roleCounts['teacher'] ?? 0),
        ];

        return view('main-admin.dashboard', [
            'network' => $network,
            'branches' => $branches,
            'summary' => $summary,
            'recentUploads' => $recentUploads,
        ]);
    }
}
