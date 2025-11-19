<?php
// app/Http/Controllers/School/FileBrowserController.php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Traits\HandlesS3Storage;
use App\Models\FileSubmission;
use App\Models\Grade;
use App\Models\Network;
use App\Models\Subject;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FileBrowserController extends Controller
{
    use HandlesS3Storage;
    public function index(Request $request, Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;

        // ✅ EXCLUDE supervisor files and any plan-related files
        $query = FileSubmission::where('school_id', $school->id)
            ->whereIn('submission_type', ['exam', 'worksheet', 'summary']) // removed plans
            ->with(['user', 'subject', 'grade']);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by grade
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        // Filter by type (✅ only allow exam, worksheet, summary)
        if ($request->filled('type')) {
            if (in_array($request->type, ['exam', 'worksheet', 'summary'])) {
                $query->where('submission_type', $request->type);
            }
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('user_id', $request->teacher_id);
        }

        // Filter by file extension
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

        $files = $query->latest()->paginate(20);

        // Get filter data
        $subjects = $school->subjects()->orderBy('name')->get();
        $grades = $school->grades()->orderBy('name')->get();
        $teachers = User::whereHas('branches', function ($q) use ($school) {
                $q->where('branches.id', $school->id);
            })
            ->where('role', 'teacher')
            ->orderBy('name')
            ->get();

        // ✅ Statistics updated (plans removed)
        $stats = [
            'total_files' => FileSubmission::where('school_id', $school->id)
                ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
                ->count(),
            'total_size' => FileSubmission::where('school_id', $school->id)
                ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
                ->sum('file_size'),
            'total_downloads' => FileSubmission::where('school_id', $school->id)
                ->whereIn('submission_type', ['exam', 'worksheet', 'summary'])
                ->sum('download_count'),
            'active_teachers' => User::whereHas('branches', function ($q) use ($school) {
                    $q->where('branches.id', $school->id);
                })
                ->where('role', 'teacher')
                ->whereHas('fileSubmissions', function ($q) {
                    $q->whereIn('submission_type', ['exam', 'worksheet', 'summary']);
                })
                ->count()
        ];

        return view('school.admin.file-browser.index', compact(
            'network',
            'branch',
            'school',
            'files',
            'subjects',
            'grades',
            'teachers',
            'stats'
        ));
    }

    public function show(Network $network, School $branch, $fileId)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $file = FileSubmission::where('school_id', $branch->id)
            ->with(['user', 'subject', 'grade'])
            ->findOrFail($fileId);

        return view('school.admin.file-browser.show', [
            'network' => $network,
            'branch' => $branch,
            'file' => $file,
        ]);
    }

    public function preview(Network $network, School $branch, $fileId)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $file = FileSubmission::where('school_id', $branch->id)->findOrFail($fileId);

        // Check if file can be previewed
        if (!$this->canPreviewInBrowser($file->original_filename)) {
            return back()->withErrors(['preview' => 'This file type cannot be previewed in the browser. Please download it instead.']);
        }

        // Increment download count
        $file->increment('download_count');
        $file->update(['last_accessed_at' => now()]);

        try {
            // Get the S3 signed URL
            $fileUrl = $this->getFileUrl($file, 30);

            // Redirect to file
            return redirect()->away($fileUrl);

        } catch (\Exception $e) {
            Log::error('Failed to generate preview URL', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['preview' => 'Unable to preview file at this time.']);
        }
    }

    public function download(Network $network, School $branch, $fileId)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $file = FileSubmission::where('school_id', $branch->id)->findOrFail($fileId);
        return $this->downloadFile($file);
    }
}
