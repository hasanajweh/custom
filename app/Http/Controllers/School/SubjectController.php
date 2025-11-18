<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesSchoolFromRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use ResolvesSchoolFromRequest;

    public function index(Request $request)
    {
        $school = $this->resolveSchool($request);

        $subjects = $school->subjects()->orderBy('name')->get();
        $archivedSubjects = $school->subjects()->onlyTrashed()->orderBy('name')->get();

        return view('school.subjects.index', compact('subjects', 'archivedSubjects', 'school'));
    }

    public function store(Request $request)
    {
        $school = $this->resolveSchool($request);

        $request->validate(['name' => 'required|string|max:255']);

        $school->subjects()->create($request->only('name'));

        return redirect()->back()->with('success', __('messages.subjects.subject_created'));
    }

    public function archive(Request $request, Subject $subject)
    {
        $school = $this->resolveSchool($request);

        if ($subject->school_id !== $school->id) {
            abort(403);
        }

        $subject->delete();

        return redirect()->back()->with('success', __('messages.subjects.subject_archived'));
    }

    public function restore(Request $request, $subjectId)
    {
        $school = $this->resolveSchool($request);

        $subject = Subject::withTrashed()
            ->where('school_id', $school->id)
            ->findOrFail($subjectId);

        $subject->restore();

        return redirect()->back()->with('success', __('messages.subjects.subject_restored'));
    }
}
