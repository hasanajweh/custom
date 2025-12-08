<?php
// app/Http/Controllers/Supervisor/FileSubmissionController.php

namespace App\Http\Controllers\Supervisor;

use App\Traits\HandlesS3Storage;
use App\Http\Controllers\Controller;
use App\Models\FileSubmission;
use App\Services\ActivityLoggerService;
use App\Models\Network;
use App\Models\Subject;
use App\Models\School;
use App\Models\SupervisorSubject;
use App\Notifications\NewFileUploaded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileSubmissionController extends Controller
{
    use HandlesS3Storage;


    public function index(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        // Redirect to review files for supervisors
        return redirect()->to(tenant_route('supervisor.reviews.index', $school));
    }

    public function create(Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $user = Auth::user();

        // Get supervisor's assigned subjects to display on the page
        $supervisorSubjects = SupervisorSubject::where('supervisor_id', $user->id)
            ->with('subject')
            ->get();

        $subjectNames = $supervisorSubjects->pluck('subject.name')->implode(', ');

        return view('supervisor.files.create', compact('school', 'subjectNames'));
    }

    public function store(Request $request, Network $network, School $branch)
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $school = $branch;
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', // No size limit
        ]);

        $user = Auth::user();

        // Get supervisor's subjects from pivot table
        $supervisorSubjects = SupervisorSubject::where('supervisor_id', $user->id)
            ->pluck('subject_id')
            ->toArray();

        // FIXED: Use S3 instead of local
        $file = $request->file('file');

        // Use S3 disk explicitly
        $path = $file->store('submissions/' . $school->id, 's3'); // Changed from 'local' to 's3'

        // Or better yet, use TenantStorageService for consistency
        // $tempResult = TenantStorageService::storeToTemp($file);

        // Auto-assign first subject if supervisor has subjects
        $subjectId = !empty($supervisorSubjects) ? $supervisorSubjects[0] : null;

        $submission = FileSubmission::create([
            'title' => $validated['title'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'submission_type' => 'supervisor_upload',
            'user_id' => Auth::id(),
            'school_id' => $school->id,
            'status' => 'approved',
            'subject_id' => $subjectId,
            'grade_id' => null,
        ]);

        $school->increment('storage_used', $file->getSize());
        $subject = $subjectId ? Subject::find($subjectId) : null;
         ActivityLoggerService::log(
            description: sprintf('Supervisor upload "%s"', $submission->title),
            school: $school,
            user: Auth::user(),
            logName: 'file_submissions',
            properties: array_filter([
                'action' => 'UPLOAD',
                'event_source' => 'supervisor',
                'submission_id' => $submission->id,
                'submission_type' => $submission->submission_type,
                'subject_id' => $subject?->id,
                'subject_name' => $subject?->name,
                 'success' => true,
                ], static fn ($value) => $value !== null),
                 event: 'file.upload',
                 subject: $submission
                  );

        return redirect()->to(tenant_route('supervisor.files.create', $school))
            ->with('upload_success', true)
            ->with('success', __('messages.files.file_has_been_uploaded'));
         }
}
