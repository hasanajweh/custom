<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileSubmission;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\School;
use App\Services\TenantStorageService;
use App\Traits\HandlesS3Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FilesController extends Controller
{
    use HandlesS3Storage;
    /**
     * Get user's files
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get user's school from available contexts or direct school_id
        $availableContexts = $user->availableContexts();
        $schoolId = null;
        
        if ($availableContexts->isNotEmpty()) {
            $schoolId = $availableContexts->first()->school_id;
        } elseif ($user->school_id) {
            $schoolId = $user->school_id;
        }

        if (!$schoolId) {
            return response()->json([
                'files' => [],
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 20,
                    'total' => 0,
                ],
            ]);
        }

        $query = FileSubmission::where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->with(['subject', 'grade']);

        // Filters
        if ($request->filled('type')) {
            $query->where('submission_type', $request->type);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $files = $query->latest()->paginate(20);

        $filesArray = $files->map(function ($file) {
            return [
                'id' => $file->id,
                'title' => $file->title,
                'description' => $file->description,
                'submission_type' => $file->submission_type,
                'file_path' => $file->file_path,
                'file_name' => $file->file_name ?? $file->original_filename,
                'original_filename' => $file->original_filename,
                'file_size' => $file->file_size,
                'mime_type' => $file->mime_type,
                'download_count' => $file->download_count ?? 0,
                'user_id' => $file->user_id,
                'school_id' => $file->school_id,
                'subject_id' => $file->subject_id,
                'grade_id' => $file->grade_id,
                'created_at' => $file->created_at->toISOString(),
                'updated_at' => $file->updated_at?->toISOString(),
                'subject' => $file->subject ? [
                    'id' => $file->subject->id,
                    'name' => $file->subject->name,
                    'school_id' => $file->subject->school_id,
                    'is_active' => $file->subject->is_active,
                ] : null,
                'grade' => $file->grade ? [
                    'id' => $file->grade->id,
                    'name' => $file->grade->name,
                    'school_id' => $file->grade->school_id,
                    'is_active' => $file->grade->is_active,
                ] : null,
            ];
        })->toArray();

        return response()->json([
            'files' => array_values($filesArray),
            'pagination' => [
                'current_page' => $files->currentPage(),
                'last_page' => $files->lastPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
            ],
        ]);
    }

    /**
     * Get file details
     */
    public function show($id)
    {
        $file = FileSubmission::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['subject', 'grade', 'school'])
            ->firstOrFail();

        return response()->json([
            'file' => [
                'id' => $file->id,
                'title' => $file->title,
                'description' => $file->description,
                'submission_type' => $file->submission_type,
                'file_name' => $file->file_name ?? $file->original_filename,
                'file_size' => $file->file_size,
                'download_count' => $file->download_count,
                'created_at' => $file->created_at,
                'subject' => $file->subject ? [
                    'id' => $file->subject->id,
                    'name' => $file->subject->name,
                ] : null,
                'grade' => $file->grade ? [
                    'id' => $file->grade->id,
                    'name' => $file->grade->name,
                ] : null,
            ],
        ]);
    }

    /**
     * Upload file
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'submission_type' => 'required|in:exam,worksheet,summary,daily_plan,weekly_plan,monthly_plan',
            'subject_id' => 'nullable|exists:subjects,id',
            'grade_id' => 'nullable|exists:grades,id',
            'file' => 'required|file|max:102400', // 100MB
        ]);

        $user = Auth::user();
        
        // Get school from available contexts or direct relationship
        $availableContexts = $user->availableContexts();
        $school = null;
        
        if ($availableContexts->isNotEmpty()) {
            $school = $availableContexts->first()->school;
        } elseif ($user->school_id) {
            $school = School::find($user->school_id);
        }

        if (!$school) {
            return response()->json([
                'message' => 'No school assigned to user.',
            ], 403);
        }

        $file = $request->file('file');

        try {
            // Validate file first
            $validation = TenantStorageService::validateFile($file);
            if (!$validation['valid']) {
                return response()->json([
                    'message' => 'File validation failed: ' . implode(', ', $validation['errors']),
                ], 422);
            }

            // Use the same method as the web controller (from trait)
            $finalPath = $this->storeFile($file, $school->slug, $request->submission_type);

            $submission = FileSubmission::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $finalPath,
                'original_filename' => $file->getClientOriginalName(),
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'submission_type' => $request->submission_type,
                'user_id' => $user->id,
                'school_id' => $school->id,
                'subject_id' => $request->subject_id,
                'grade_id' => $request->grade_id,
            ]);

            return response()->json([
                'message' => 'File uploaded successfully.',
                'file' => [
                    'id' => $submission->id,
                    'title' => $submission->title,
                    'submission_type' => $submission->submission_type,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'File upload failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete file
     */
    public function destroy($id)
    {
        $file = FileSubmission::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            // Use trait method for deletion
            $this->deleteFile($file->file_path);
            $file->delete();

            return response()->json([
                'message' => 'File deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete file.',
            ], 500);
        }
    }

    /**
     * Get download URL
     */
    public function download($id)
    {
        $file = FileSubmission::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $file->increment('download_count');

        $url = TenantStorageService::url($file->file_path, $file->school);

        return response()->json([
            'download_url' => $url,
        ]);
    }

    /**
     * Get subjects and grades for current user
     */
    public function getSubjectsGrades()
    {
        $user = Auth::user();
        
        // Get school from available contexts or direct relationship
        $availableContexts = $user->availableContexts();
        $school = null;
        
        if ($availableContexts->isNotEmpty()) {
            $school = $availableContexts->first()->school;
        } elseif ($user->school_id) {
            $school = School::find($user->school_id);
        }

        if (!$school) {
            return response()->json([
                'subjects' => [],
                'grades' => [],
            ]);
        }

        $subjects = $user->subjects()
            ->wherePivot('school_id', $school->id)
            ->orderBy('subjects.name')
            ->get()
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->name]);

        $grades = $user->grades()
            ->wherePivot('school_id', $school->id)
            ->orderBy('grades.name')
            ->get()
            ->map(fn($g) => ['id' => $g->id, 'name' => $g->name]);

        return response()->json([
            'subjects' => $subjects,
            'grades' => $grades,
        ]);
    }
}
