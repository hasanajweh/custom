<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ResolvesSchoolFromRequest;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    use ResolvesSchoolFromRequest;

    public function index(Request $request)
    {
        $school = $this->resolveSchool($request);

        $grades = $school->grades()->orderBy('name')->get();
        $archivedGrades = $school->grades()->onlyTrashed()->orderBy('name')->get();

        return view('school.grades.index', compact('grades', 'archivedGrades', 'school'));
    }

    public function store(Request $request)
    {
        $school = $this->resolveSchool($request);

        $request->validate(['name' => 'required|string|max:255']);

        $school->grades()->create($request->only('name'));

        return redirect()->back()->with('success', __('messages.grades.grade_created'));
    }

    public function archive(Request $request, Grade $grade)
    {
        $school = $this->resolveSchool($request);

        if ($grade->school_id !== $school->id) {
            abort(403);
        }

        $grade->delete();

        return redirect()->back()->with('success', __('messages.grades.grade_archived'));
    }

    public function restore(Request $request, $gradeId)
    {
        $school = $this->resolveSchool($request);

        $grade = Grade::withTrashed()
            ->where('school_id', $school->id)
            ->findOrFail($gradeId);

        $grade->restore();

        return redirect()->back()->with('success', __('messages.grades.grade_restored'));
    }
}
