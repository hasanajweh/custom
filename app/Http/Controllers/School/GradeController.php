<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\School;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        $grades = $school->grades()->orderBy('name')->get();

        return view('school.grades.index', compact('grades', 'school'));
    }

    public function store(Request $request)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        $request->validate(['name' => 'required|string|max:255']);

        $school->grades()->create($request->only('name'));

        return redirect()->back()->with('success', 'Grade created successfully.');
    }

    public function destroy(Request $request, Grade $grade)
    {
        $school = $request->route('school');
        if (is_string($school)) {
            $school = School::where('slug', $school)->firstOrFail();
        }

        if ($grade->school_id !== $school->id) {
            abort(403);
        }

        $grade->delete();

        return redirect()->back()->with('success', 'Grade deleted successfully.');
    }
}
