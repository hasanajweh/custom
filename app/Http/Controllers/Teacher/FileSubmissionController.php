<?php
// app/Http/Controllers/Teacher/FileSubmissionController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\FileSubmission;
use App\Traits\HandlesS3Storage;
use App\Models\Network;
use App\Models\School;
use App\Http\Requests\StoreFileSubmissionRequest;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\ActivityLoggerService;
use App\Models\Grade;
use App\Services\TenantStorageService;
use App\Jobs\ProcessFileUpload;
use App\Notifications\NewFileUploaded;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FileSubmissionController extends Controller
{
    use HandlesS3Storage;

    public function dashboard(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $user = Auth::user();

        $totalUploads = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->count();

        $totalDownloads = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->sum('download_count');

        $recentFiles = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $school->id)
            ->with(['subject', 'grade'])
            ->latest()
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'school',
            'totalUploads',
            'totalDownloads',
            'recentFiles'
        ));
    }

    public function myFiles(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $user = Auth::user();
        $subjects = $user->subjects()
            ->wherePivot('school_id', $school->id)
            ->orderBy('subjects.name')
            ->get();
        $grades = $user->grades()
            ->wherePivot('school_id', $school->id)
            ->orderBy('grades.name')
            ->get();

        $allowedSubjectIds = $subjects->pluck('id')->map(fn ($id) => (int) $id);
        $allowedGradeIds = $grades->pluck('id')->map(fn ($id) => (int) $id);

        // === GENERAL RESOURCES (Exam, Worksheet, Summary) ===
        $generalQuery = FileSubmission::where('user_id', Auth::id())
            ->where('school_id', $school->id)
            ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
            ->with(['subject', 'grade']);

        if (request()->filled('general_search')) {
            $generalQuery->where('title', 'like', '%' . request('general_search') . '%');
        }
        if (request()->filled('general_type')) {
            $generalQuery->where('submission_type', request('general_type'));
        }
        if (request()->filled('general_subject_id') && $allowedSubjectIds->contains((int) request('general_subject_id'))) {
            $generalQuery->where('subject_id', request('general_subject_id'));
        }
        if (request()->filled('general_grade_id') && $allowedGradeIds->contains((int) request('general_grade_id'))) {
            $generalQuery->where('grade_id', request('general_grade_id'));
        }

        $generalFiles = $generalQuery->latest()->paginate(20, ['*'], 'general_page');

        // === LESSON PLANS (Daily, Weekly, Monthly) ===
        $plansQuery = FileSubmission::where('user_id', Auth::id())
            ->where('school_id', $school->id)
            ->whereIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan'])
            ->with(['subject', 'grade']);

        if (request()->filled('plans_search')) {
            $plansQuery->where('title', 'like', '%' . request('plans_search') . '%');
        }
        if (request()->filled('plans_type')) {
            $plansQuery->where('submission_type', request('plans_type'));
        }
        if (request()->filled('plans_date_filter')) {
            $filter = request('plans_date_filter');
            if ($filter === 'this_week') {
                $plansQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter === 'this_month') {
                $plansQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($filter === 'last_month') {
                $plansQuery->whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year);
            }
        }

        $lessonPlans = $plansQuery->latest()->paginate(20, ['*'], 'plans_page');

        return view('teacher.files.index', compact('school', 'generalFiles', 'lessonPlans', 'subjects', 'grades'));
    }

    public function create(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $user = Auth::user();
        $subjects = $user->subjects()
            ->wherePivot('school_id', $school->id)
            ->orderBy('subjects.name')
            ->get();
        $grades = $user->grades()
            ->wherePivot('school_id', $school->id)
            ->orderBy('grades.name')
            ->get();

        $storageUsed = $school->storage_used;
        $storageLimit = $school->storage_limit;
        $storagePercentage = $school->storage_used_percentage;

        return view('teacher.files.create', compact(
            'school',
            'subjects',
            'grades',
            'storageUsed',
            'storageLimit',
            'storagePercentage'
        ));
    }

    public function store(Network $network, School $branch, Request $request)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $formRequest = new StoreFileSubmissionRequest();
        $validated = $request->validate(
            $formRequest->rules(),
            method_exists($formRequest, 'messages') ? $formRequest->messages() : []
        );
        Log::info('=== FILE UPLOAD START ===', [
            'user_id' => Auth::id(),
            'school_id' => $school->id,
            'request_data' => $request->except('file')
        ]);

        $planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan'];
        $submissionType = $request->input('submission_type');
        $file = $request->file('file');

        try {
            // Storage limit check removed - unlimited storage

            $tempResult = TenantStorageService::storeToTemp($file);

            Log::info('File validated and stored to temp', [
                'temp_path' => $tempResult['temp_path'],
                'hash' => $tempResult['hash']
            ]);

            // Store file with proper structure: school_slug/type/filename
            $finalPath = $this->storeFile($file, $school->slug, $submissionType);

            $submissionData = [
                'title' => $validated['title'],
                'file_path' => $finalPath,
                'original_filename' => $tempResult['original_name'],
                'file_size' => $tempResult['size'],
                'mime_type' => $tempResult['mime_type'],
                'file_hash' => $tempResult['hash'],
                'submission_type' => $validated['submission_type'],
                'user_id' => Auth::id(),
                'school_id' => $school->id,
                'status' => 'approved',
                'subject_id' => null,
                'grade_id' => null,
            ];

            if (!in_array($submissionType, $planTypes)) {
                $submissionData['subject_id'] = $validated['subject_id'];
                $submissionData['grade_id'] = $validated['grade_id'];
            }

            $submission = FileSubmission::create($submissionData);
            $subject = $submission->subject_id ? Subject::find($submission->subject_id) : null;
            $grade = $submission->grade_id ? Grade::find($submission->grade_id) : null;

             ActivityLoggerService::log(
                description: sprintf(
                    'Uploaded %s "%s"',
                    $submission->submission_type,
                    $submission->title
                ),
                school: $school,
                user: Auth::user(),
                logName: 'file_submissions',
                properties: array_filter([
                    'action' => 'UPLOAD',
                    'event_source' => 'teacher',
                    'submission_id' => $submission->id,
                    'submission_type' => $submission->submission_type,
                    'subject_id' => $subject?->id,
                    'subject_name' => $subject?->name,
                    'grade_id' => $grade?->id,
                    'grade_name' => $grade?->name,
                 'success' => true,
                ], static fn ($value) => $value !== null),
                event: 'file.upload',
                subject: $submission
            );

            Log::info('FileSubmission created', [
                'id' => $submission->id,
                'status' => $submission->status,
                'path' => $finalPath
            ]);

            // Update school storage
            $school->increment('storage_used', $file->getSize());

            return redirect()->to(
                tenant_route('teacher.files.create', $branch)
            )->with('status', __('messages.files.upload_success'));

        } catch (\InvalidArgumentException $e) {
            Log::error('File validation failed', [
                'error' => $e->getMessage(),
                'file_name' => $file->getClientOriginalName()
            ]);

            return back()->withErrors([
                'file' => $e->getMessage()
            ])->withInput();

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'file' => 'File upload failed. Please try again.'
            ])->withInput();
        }
    }

    public function preview(Network $network, School $branch, FileSubmission $fileSubmission)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;

        if ($fileSubmission->user_id !== Auth::id() || $fileSubmission->school_id !== $school->id) {
            abort(403);
        }

        if ($fileSubmission->status === 'pending') {
            return back()->with('info', 'File is still being processed. Please try again in a moment.');
        }

        $fileSubmission->increment('download_count');
        $fileSubmission->update(['last_accessed_at' => now()]);

        try {
            $url = TenantStorageService::url($fileSubmission->file_path, $school);
            return redirect($url);
        } catch (\Exception $e) {
            Log::error('Failed to generate file URL', [
                'file_id' => $fileSubmission->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors([
                'preview' => 'Unable to preview file at this time.'
            ]);
        }
    }

    public function show(Network $network, School $branch, FileSubmission $fileSubmission)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;

        if ($fileSubmission->user_id !== Auth::id() || $fileSubmission->school_id !== $school->id) {
            abort(403);
        }

        $fileSubmission->load(['subject', 'grade']);

        return view('teacher.files.show', compact('school', 'fileSubmission'));
    }

    public function download(Network $network, School $branch, FileSubmission $fileSubmission)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;

        if ($fileSubmission->user_id !== Auth::id() || $fileSubmission->school_id !== $school->id) {
            abort(403);
        }

        if ($fileSubmission->status === 'pending') {
            return back()->with('info', 'File is still being processed. Please try again in a moment.');
        }

        // ✅ Use trait method for direct download - ALREADY CORRECT
        return $this->downloadFile($fileSubmission);
    }

    public function destroy(Network $network, School $branch, FileSubmission $fileSubmission)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;

        if ($fileSubmission->user_id !== Auth::id() || $fileSubmission->school_id !== $school->id) {
            abort(403);
        }

        try {
            $fileSubmission->delete();

            Log::info('File soft deleted', [
                'file_id' => $fileSubmission->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->to(tenant_route('teacher.files.index', $school))
                ->with('success', 'File deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to delete file', [
                'file_id' => $fileSubmission->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors([
                'delete' => 'Failed to delete file.'
            ]);
        }
    }
}
