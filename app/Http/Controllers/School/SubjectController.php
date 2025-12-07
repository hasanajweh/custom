<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
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

        $subjects = Subject::where('network_id', $network->id)
            ->where(function ($query) use ($school) {
                $query->whereHas('schools', fn($q) => $q->where('school_id', $school->id))
                    ->orWhereNull('created_in');
            })
            ->with('schools')
            ->orderBy('name')
            ->get();

        $archivedSubjects = Subject::onlyTrashed()
            ->where('network_id', $network->id)
            ->whereHas('schools', fn($q) => $q->where('school_id', $school->id))
            ->orderBy('name')
            ->get();

        return view('school.admin.subjects.index', compact('subjects', 'archivedSubjects', 'school', 'branch', 'network'));
    }

    public function store(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        $request->validate(['name' => 'required|string|max:255']);

        $subject = Subject::create([
            'name' => $request->input('name'),
            'network_id' => $network->id,
            'school_id' => $school->id,
            'created_by' => Auth::id(),
            'created_in' => $school->id,
        ]);

        $subject->schools()->syncWithoutDetaching([$school->id]);

        return redirect()->back()->with('success', __('messages.subjects.subject_created'));
    }

    public function archive(Request $request, Network $network, School $branch, Subject $subject)
    {
        $school = $this->validateContext($network, $branch);

        // Main admin exception: can archive any subject in their network
        $user = Auth::user();
        if (!$user->isMainAdmin() && $subject->created_in !== $school->id) {
            abort(403);
        }

        $subject->delete();

        return redirect()->back()->with('success', __('messages.subjects.subject_archived'));
    }

    public function restore(Request $request, Network $network, School $branch, $subjectId)
    {
        $school = $this->validateContext($network, $branch);

        // Main admin exception: can restore any subject in their network
        $user = Auth::user();
        $query = Subject::withTrashed();
        
        if ($user->isMainAdmin()) {
            $query->where('network_id', $user->network_id);
        } else {
            $query->where('created_in', $school->id);
        }
        
        $subject = $query->findOrFail($subjectId);

        $subject->restore();

        return redirect()->back()->with('success', __('messages.subjects.subject_restored'));
    }
}
