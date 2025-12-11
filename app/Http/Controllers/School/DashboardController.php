<?php
// app/Http/Controllers/School/DashboardController.php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Models\User;
use App\Models\FileSubmission;
use App\Traits\HandlesS3Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use HandlesS3Storage;

    public function index(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $user = Auth::user();

        // Main admin exception: can access any school in their network
        if ($user && $user->isMainAdmin()) {
            if ($branch->network_id !== $user->network_id) {
                abort(403, 'School does not belong to your network.');
            }
            // Main admin can proceed - no school_id check needed
        } elseif ($user) {
            // Allow access if the user has any role assignment for this branch
            $hasBranchRole = $user->schoolRoles()
                ->where('school_id', $branch->id)
                ->exists();

            if (!$hasBranchRole && $user->school_id !== $branch->id) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $school = $branch;

        // Get per_page from request, default to 10
        $perPage = request()->get('per_page', 10);

        // Get file extension filters
        $academicExtFilter = request()->get('academic_ext');
        $plansExtFilter = request()->get('plans_ext');
        $supervisorExtFilter = request()->get('supervisor_ext');

        // Basic stats
        $todayUploads = FileSubmission::where('school_id', $school->id)
            ->whereDate('created_at', today())
            ->count();

        $weekUploads = FileSubmission::where('school_id', $school->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $totalFiles = FileSubmission::where('school_id', $school->id)->count();

        $totalTeachers = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->count();

        // Get top teacher with most uploads
        $topTeacher = User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->withCount('fileSubmissions')
            ->having('file_submissions_count', '>', 0)
            ->orderBy('file_submissions_count', 'desc')
            ->first();

        if ($topTeacher) {
            $topTeacher->total_uploads = $topTeacher->file_submissions_count;
        }

        // Get academic files (last 72 hours) with pagination and extension filter
        $academicQuery = FileSubmission::where('school_id', $school->id)
            ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
            ->where('created_at', '>=', now()->subHours(72))
            ->with(['user', 'subject', 'grade']);

        if ($academicExtFilter) {
            $academicQuery->where('original_filename', 'LIKE', '%.' . $academicExtFilter);
        }

        $academicFiles = $academicQuery->latest()->paginate($perPage, ['*'], 'academic_page');

        // Get plan files (last 72 hours) with pagination and extension filter
        $plansQuery = FileSubmission::where('school_id', $school->id)
            ->whereIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan'])
            ->where('created_at', '>=', now()->subHours(72))
            ->with(['user']);

        if ($plansExtFilter) {
            $plansQuery->where('original_filename', 'LIKE', '%.' . $plansExtFilter);
        }

        $planFiles = $plansQuery->latest()->paginate($perPage, ['*'], 'plans_page');

        // Get supervisor uploads (last 72 hours) with pagination and extension filter
        $supervisorQuery = FileSubmission::where('school_id', $school->id)
            ->where('submission_type', 'supervisor_upload')
            ->where('created_at', '>=', now()->subHours(72))
            ->with(['user', 'subject']);

        if ($supervisorExtFilter) {
            $supervisorQuery->where('original_filename', 'LIKE', '%.' . $supervisorExtFilter);
        }

        $supervisorFiles = $supervisorQuery->latest()->paginate($perPage, ['*'], 'supervisor_page');

        // Get available extensions for each category
        $academicExtensions = $this->getAvailableExtensions($school->id, ['exam', 'worksheet', 'summary']);
        $plansExtensions = $this->getAvailableExtensions($school->id, ['daily_plan', 'weekly_plan', 'monthly_plan']);
        $supervisorExtensions = $this->getAvailableExtensions($school->id, ['supervisor_upload']);

        return view('school.admin.dashboard', compact(
            'school',
            'branch',
            'network',
            'todayUploads',
            'weekUploads',
            'totalFiles',
            'totalTeachers',
            'topTeacher',
            'academicFiles',
            'planFiles',
            'supervisorFiles',
            'perPage',
            'academicExtFilter',
            'plansExtFilter',
            'supervisorExtFilter',
            'academicExtensions',
            'plansExtensions',
            'supervisorExtensions'
        ));
    }

    /**
     * Get available file extensions for given submission types
     */
    private function getAvailableExtensions($schoolId, $submissionTypes)
    {
        return FileSubmission::where('school_id', $schoolId)
            ->whereIn('submission_type', $submissionTypes)
            ->where('created_at', '>=', now()->subHours(72))
            ->get()
            ->map(function($file) {
                return strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * ✅ UPDATED: Preview file - uses trait method
     */
    public function preview(School $school, $fileId)
    {
        $file = FileSubmission::where('school_id', $school->id)->findOrFail($fileId);

        // Check if file can be previewed
        if (!$this->canPreviewInBrowser($file->original_filename)) {
            return back()->withErrors(['preview' => 'This file type cannot be previewed in the browser. Please download it instead.']);
        }

        // Increment download count
        $file->increment('download_count');
        $file->update(['last_accessed_at' => now()]);

        try {
            // ✅ Use the trait method
            $url = $this->getFileUrl($file, 30);
            
            // Validate URL before redirecting
            if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invalid file URL generated');
            }
            
            return redirect()->away($url);

        } catch (\Exception $e) {
            Log::error('Failed to generate preview URL', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['preview' => 'Unable to preview file at this time. Please try downloading it instead.']);
        }
    }

    /**
     * ✅ UPDATED: Download file - uses trait method
     */
    public function download(School $school, $fileId)
    {
        $file = FileSubmission::where('school_id', $school->id)->findOrFail($fileId);

        // ✅ Use the trait method
        return $this->downloadFile($file);
    }
}
