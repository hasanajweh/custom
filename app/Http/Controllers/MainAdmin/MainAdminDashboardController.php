<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\FileSubmission;
use App\Models\Network;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

class MainAdminDashboardController extends Controller
{
    public function index(Network $network): View
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        App::setLocale('ar');

        $branches = $userNetwork->schools()->withCount([
            'users as admins_count' => fn ($query) => $query->where('role', 'admin'),
            'users as supervisors_count' => fn ($query) => $query->where('role', 'supervisor'),
            'users as teachers_count' => fn ($query) => $query->where('role', 'teacher'),
            'fileSubmissions',
            'fileSubmissions as recent_files_count' => fn ($query) => $query->where('created_at', '>=', now()->subHours(72)),
            'fileSubmissions as plans_count' => fn ($query) => $query->where('submission_type', 'plan'),
        ])->get();

        $branchIds = $branches->pluck('id');

        $roleCounts = User::whereIn('school_id', $branchIds)
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

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
            'recent_files' => $recentUploads->count(),
            'admins' => $roleCounts['admin'] ?? 0,
            'supervisors' => $roleCounts['supervisor'] ?? 0,
            'teachers' => $roleCounts['teacher'] ?? 0,
        ];

        return view('main-admin.dashboard', [
            'network' => $userNetwork,
            'branches' => $branches,
            'summary' => $summary,
            'recentUploads' => $recentUploads,
        ]);
    }
}
