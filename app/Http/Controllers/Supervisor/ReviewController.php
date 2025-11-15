<?php
// app/Http/Controllers/Supervisor/ReviewController.php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
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

    public function index(School $school)
    {
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

        // Build query - only get academic files (not plans, not supervisor uploads)
        $query = FileSubmission::where('school_id', $school->id)
            ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
            ->with(['user', 'subject', 'grade']);

        // Only filter by subjects if supervisor has subjects assigned
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

        // Date filter
        if (request()->filled('date')) {
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

        // Always paginate
        $files = $query->latest()->paginate(20);

        return view('supervisor.reviews.index', compact('school', 'files', 'subjects', 'grades'));
    }

    public function show(School $school, FileSubmission $fileSubmission)
    {
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
    public function download(School $school, FileSubmission $fileSubmission)
    {
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
    public function preview(School $school, FileSubmission $fileSubmission)
    {
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

            return redirect($url);

        } catch (\Exception $e) {
            Log::error('Failed to generate preview URL', [
                'file_id' => $fileSubmission->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors([
                'preview' => 'Unable to preview file at this time.'
            ]);
        }
    }
}
