<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Traits\HandlesS3Storage;
use App\Models\FileSubmission;
use App\Models\Network;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainAdminFileBrowserController extends Controller
{
    use HandlesS3Storage;

    public function index(Request $request, Network $network): View
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $branches = $userNetwork->schools()->with(['subjects', 'grades'])->get();
        $branchIds = $branches->pluck('id');

        $query = FileSubmission::with(['user', 'subject', 'grade', 'school'])
            ->whereIn('school_id', $branchIds);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('branch_id')) {
            $query->where('school_id', $request->branch_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->filled('type')) {
            $query->where('submission_type', $request->type);
        }

        if ($request->filled('teacher_id')) {
            $query->where('user_id', $request->teacher_id);
        }

        $files = $query->latest()->paginate(20)->withQueryString();

        $subjects = Subject::where('network_id', $userNetwork->id)
            ->orWhereNull('school_id')
            ->orderBy('name')
            ->get();

        $grades = Grade::where('network_id', $userNetwork->id)
            ->orWhereNull('school_id')
            ->orderBy('name')
            ->get();

        $teachers = User::where('network_id', $userNetwork->id)
            ->where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('main-admin.file-browser.index', [
            'network' => $userNetwork,
            'branches' => $branches,
            'subjects' => $subjects,
            'grades' => $grades,
            'teachers' => $teachers,
            'files' => $files,
        ]);
    }

    public function preview(Network $network, int $fileId): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $file = FileSubmission::with('school')
            ->whereHas('school', fn ($q) => $q->where('network_id', $userNetwork->id))
            ->findOrFail($fileId);

        if (!$this->canPreviewInBrowser($file->original_filename)) {
            return back()->withErrors(['preview' => 'This file type cannot be previewed in the browser.']);
        }

        $file->increment('download_count');
        $file->update(['last_accessed_at' => now()]);

        return redirect()->away($this->getFileUrl($file, 30));
    }

    public function download(Network $network, int $fileId)
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $file = FileSubmission::with('school')
            ->whereHas('school', fn ($q) => $q->where('network_id', $userNetwork->id))
            ->findOrFail($fileId);

        return $this->downloadFile($file);
    }
}
