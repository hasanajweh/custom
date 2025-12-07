<?php
// app/Http/Controllers/School/SupervisorController.php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FileSubmission;
use App\Models\Subject;
use App\Models\SupervisorSubject;
use Illuminate\Http\Request;
use App\Models\Network;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    private function validateContext(Network $network, School $branch): School
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        $user = Auth::user();
        
        // Main admin exception: can access any school in their network
        if ($user->isMainAdmin()) {
            if ($branch->network_id !== $user->network_id) {
                abort(403, 'School does not belong to your network.');
            }
            return $branch;
        }

        // Regular admin: must belong to this school
        if ($user->school_id !== $branch->id) {
            abort(403);
        }

        return $branch;
    }

    public function index(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        // ✅ Get all supervisors with their subjects from pivot table
        $supervisors = User::where('school_id', $school->id)
            ->where('role', 'supervisor')
            ->get()
            ->map(function ($supervisor) use ($school) {
                // Get supervisor's subjects from pivot table
                $supervisorSubjects = SupervisorSubject::where('supervisor_id', $supervisor->id)
                    ->with('subject')
                    ->get();

                // Get subject IDs
                $subjectIds = $supervisorSubjects->pluck('subject_id')->toArray();

                // ✅ Set the subject display name
                if ($supervisorSubjects->isNotEmpty()) {
                    $supervisor->subject = $supervisorSubjects->pluck('subject.name')->implode(', ');
                } else {
                    $supervisor->subject = 'Not Assigned';
                }

                // Store subject IDs for queries
                $supervisor->subject_ids = $subjectIds;

                // Get file counts for THIS supervisor's subjects only
                $supervisor->reviewed_count = empty($subjectIds) ? 0 :
                    FileSubmission::where('school_id', $school->id)
                        ->whereIn('subject_id', $subjectIds)
                        ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
                        ->count();

                // Get upload count
                $supervisor->uploaded_count = FileSubmission::where('school_id', $school->id)
                    ->where('user_id', $supervisor->id)
                    ->where('submission_type', 'supervisor_upload')
                    ->count();

                // Get last activity
                $supervisor->last_activity = FileSubmission::where('school_id', $school->id)
                    ->where('user_id', $supervisor->id)
                    ->latest()
                    ->first()?->created_at;

                return $supervisor;
            });

        return view('school.admin.supervisors.index', compact('school', 'supervisors', 'branch', 'network'));
    }

    public function files(Request $request, Network $network, School $branch, User $supervisor)
    {
        $school = $this->validateContext($network, $branch);

        // Verify supervisor belongs to school (main admin exception)
        $user = Auth::user();
        if (!$user->isMainAdmin() && ($supervisor->school_id !== $school->id || $supervisor->role !== 'supervisor')) {
            abort(404);
        }

        // Get supervisor's subjects
        $subjectIds = SupervisorSubject::where('supervisor_id', $supervisor->id)
            ->pluck('subject_id')
            ->toArray();

        // Get all subjects for filters
        $subjects = Subject::where('school_id', $school->id)
            ->whereIn('id', $subjectIds)
            ->get();

        // Build query for supervisor's files
        $query = FileSubmission::where('school_id', $school->id)
            ->where('user_id', $supervisor->id)
            ->with(['user', 'subject', 'grade']);

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

        if (request()->filled('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }

        if (request()->filled('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        // Paginate
        $files = $query->latest()->paginate(20);

        return view('supervisor.files', compact('school', 'supervisor', 'files', 'subjects'));
    }
}
