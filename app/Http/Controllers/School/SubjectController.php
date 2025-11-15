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

        return view('school.subjects.index', compact('subjects', 'school'));
    }

    public function store(Request $request)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        $request->validate(['name' => 'required|string|max:255']);

        $school->subjects()->create($request->only('name'));

        return redirect()->back()->with('success', 'Subject created successfully.');
    }

    public function destroy(Request $request, Subject $subject)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        if ($subject->school_id !== $school->id) {
            abort(403);
        }

        $subject->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }
}
