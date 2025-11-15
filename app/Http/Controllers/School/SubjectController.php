<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\School;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        $subjects = $school->subjects()->orderBy('name')->get();
        $archivedSubjects = $school->subjects()->onlyTrashed()->orderBy('name')->get();

        return view('school.subjects.index', compact('subjects', 'archivedSubjects', 'school'));
    }

    public function store(Request $request)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        $request->validate(['name' => 'required|string|max:255']);

        $school->subjects()->create($request->only('name'));

        return redirect()->back()->with('success', __('messages.subjects.subject_created'));
    }

    public function archive(Request $request, Subject $subject)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        if ($subject->school_id !== $school->id) {
            abort(403);
        }

        $subject->delete();

        return redirect()->back()->with('success', __('messages.subjects.subject_archived'));
    }

    public function restore(Request $request, $subjectId)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        $subject = Subject::withTrashed()
            ->where('school_id', $school->id)
            ->findOrFail($subjectId);

        $subject->restore();

        return redirect()->back()->with('success', __('messages.subjects.subject_restored'));
    }
}
