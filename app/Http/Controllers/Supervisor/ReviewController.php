<?php
// app/Http/Controllers/Supervisor/ReviewController.php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Models\FileSubmission;
use App\Models\SupervisorSubject;
use App\Traits\HandlesS3Storage;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    use HandlesS3Storage;

    public function index(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $supervisor = Auth::user();

        // Get supervisor's assigned subjects from PIVOT TABLE
        $subjectIds = SupervisorSubject::where('supervisor_id', $supervisor->id)
            ->pluck('subject_id')
            ->toArray();

        // Get subjects for filters
        $subjects = Subject::where('school_id', $school->id)
            ->whereIn('id', $subjectIds)
            ->get();

        // Get grades for filters
        $grades = Grade::where('school_id', $school->id)->get();

        // Get teachers for filters (only those who uploaded files in supervisor's subjects)
        $teachers = \App\Models\User::where('school_id', $school->id)
            ->where('role', 'teacher')
            ->whereHas('fileSubmissions', function($q) use ($school, $subjectIds) {
                $q->where('school_id', $school->id)
                  ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload']);
                if (!empty($subjectIds)) {
                    $q->whereIn('subject_id', $subjectIds);
                }
            })
            ->distinct()
            ->get();

        // Build query - only get academic files (not plans, not supervisor uploads)
        $query = FileSubmission::where('school_id', $school->id)
            ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
            ->with(['user', 'subject', 'grade']);

        // Only filter by subjects if supervisor has subjects assigned
        if (!empty($subjectIds)) {
            $query->whereIn('subject_id', $subjectIds);
        }

        // Apply comprehensive filters
        if (request()->filled('search')) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . request('search') . '%');
                  });
            });
        }

        if (request()->filled('subject_id')) {
            $query->where('subject_id', request('subject_id'));
        }

        if (request()->filled('grade_id')) {
            $query->where('grade_id', request('grade_id'));
        }

        if (request()->filled('type')) {
            $query->where('submission_type', request('type'));
        }

        if (request()->filled('teacher_id')) {
            $query->where('user_id', request('teacher_id'));
        }

        // Date range filters
        if (request()->filled('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request()->filled('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        // Single date filter (backward compatibility)
        if (request()->filled('date') && !request()->filled('date_from') && !request()->filled('date_to')) {
            $query->whereDate('created_at', request('date'));
        }

        // Extension filter
        if (request()->filled('extension')) {
            $extensions = explode(',', request('extension'));
            $query->where(function($q) use ($extensions) {
                foreach ($extensions as $ext) {
                    $q->orWhere('original_filename', 'like', '%.' . trim($ext));
                }
            });
        }

        // File size filter
        if (request()->filled('size_min')) {
            $query->where('file_size', '>=', request('size_min') * 1024 * 1024); // Convert MB to bytes
        }

        if (request()->filled('size_max')) {
            $query->where('file_size', '<=', request('size_max') * 1024 * 1024);
        }

        // Sort options
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'title', 'file_size', 'download_count'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // Always paginate
        $files = $query->paginate(20);

        return view('supervisor.reviews.index', compact('school', 'files', 'subjects', 'grades', 'teachers'));
    }

    public function show(Network $network, School $branch, FileSubmission $fileSubmission)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $supervisor = Auth::user();

        // Get supervisor's assigned subjects from pivot table
        $subjectIds = SupervisorSubject::where('supervisor_id', $supervisor->id)
            ->pluck('subject_id')
            ->toArray();

        // Check school access
        if ($fileSubmission->school_id !== $school->id) {
            abort(403, 'You do not have permission to view this file.');
        }

        // For academic files, check subject access
        $planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'];
        if (!in_array($fileSubmission->submission_type, $planTypes)) {
            // Only check if supervisor has subjects assigned
            if (!empty($subjectIds) && !in_array($fileSubmission->subject_id, $subjectIds)) {
                abort(403, 'You do not have permission to view files from this subject.');
            }
        }

        $fileSubmission->load(['user', 'subject', 'grade']);

        return view('supervisor.reviews.show', compact('school', 'fileSubmission'));
    }

    /**
     * ✅ UPDATED: Download file - uses trait method
     */
    public function download(Network $network, School $branch, FileSubmission $fileSubmission)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $supervisor = Auth::user();

        $subjectIds = SupervisorSubject::where('supervisor_id', $supervisor->id)
            ->pluck('subject_id')
            ->toArray();

        if ($fileSubmission->school_id !== $school->id) {
            abort(403, 'You do not have permission to download this file.');
        }

        $planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'];
        if (!in_array($fileSubmission->submission_type, $planTypes)) {
            if (!empty($subjectIds) && !in_array($fileSubmission->subject_id, $subjectIds)) {
                abort(403, 'You do not have permission to download this file.');
            }
        }

        // ✅ Use the trait method - authorization done above
        return $this->downloadFile($fileSubmission);
    }

    /**
     * Preview file - REDIRECT DIRECTLY TO S3 URL
     */
    public function preview(Network $network, School $branch, FileSubmission $fileSubmission)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $supervisor = Auth::user();

        $subjectIds = SupervisorSubject::where('supervisor_id', $supervisor->id)
            ->pluck('subject_id')
            ->toArray();

        if ($fileSubmission->school_id !== $school->id) {
            abort(403);
        }

        $planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'];
        if (!in_array($fileSubmission->submission_type, $planTypes)) {
            if (!empty($subjectIds) && !in_array($fileSubmission->subject_id, $subjectIds)) {
                abort(403);
            }
        }

        // Check if file can be previewed
        if (!$this->canPreviewInBrowser($fileSubmission->original_filename)) {
            return back()->withErrors(['preview' => 'This file type cannot be previewed in the browser. Please download it instead.']);
        }

        $fileSubmission->increment('download_count');
        $fileSubmission->update(['last_accessed_at' => now()]);

        try {
            // Get S3 URL and redirect directly
            $url = $this->getFileUrl($fileSubmission, 30);

            // Validate URL before redirecting
            if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invalid file URL generated');
            }

            return redirect()->away($url);

        } catch (\Exception $e) {
            Log::error('Failed to generate preview URL', [
                'file_id' => $fileSubmission->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'preview' => 'Unable to preview file at this time. Please try downloading it instead.'
            ]);
        }
    }
}
