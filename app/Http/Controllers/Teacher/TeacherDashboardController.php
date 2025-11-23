<?php
// app/Http/Controllers/Teacher/TeacherDashboardController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Models\FileSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherDashboardController extends Controller
{
    public function index(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $user = Auth::user();

        // Statistics
        $totalUploads = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->count();

        $totalDownloads = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->sum('download_count');

        $thisWeekUploads = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Storage used (in MB)
        $storageUsed = round(FileSubmission::where('user_id', $user->id)
                ->where('school_id', $school->id)
                ->sum('file_size') / (1024 * 1024), 2);

        // Recent files
        $recentFiles = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->with(['subject', 'grade'])
            ->latest()
            ->take(5)
            ->get();

        // File type statistics
        $fileTypeStats = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->select('submission_type', DB::raw('count(*) as count'))
            ->groupBy('submission_type')
            ->pluck('count', 'submission_type')
            ->toArray();

        // Ensure all file types are present
        $allTypes = ['daily_plan', 'weekly_plan', 'monthly_plan', 'exam', 'worksheet', 'summary'];
        foreach ($allTypes as $type) {
            if (!isset($fileTypeStats[$type])) {
                $fileTypeStats[$type] = 0;
            }
        }

        return view('teacher.dashboard', compact(
            'school',
            'totalUploads',
            'totalDownloads',
            'thisWeekUploads',
            'storageUsed',
            'recentFiles',
            'fileTypeStats'
        ));
    }
}
