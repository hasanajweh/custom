<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\School;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    private function validateContext(Network $network, School $branch): School
    {
        if ($branch->network_id !== $network->id) {
            abort(404);
        }

        if (Auth::user()->school_id !== $branch->id) {
            abort(403);
        }

        return $branch;
    }

    public function index(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        $grades = Grade::where('network_id', $network->id)
            ->where(function ($query) use ($school) {
                $query->whereHas('schools', fn($q) => $q->where('school_id', $school->id))
                    ->orWhereNull('created_in');
            })
            ->with('schools')
            ->orderBy('name')
            ->get();

        $archivedGrades = Grade::onlyTrashed()
            ->where('network_id', $network->id)
            ->whereHas('schools', fn($q) => $q->where('school_id', $school->id))
            ->orderBy('name')
            ->get();

        return view('school.admin.grades.index', compact('grades', 'archivedGrades', 'school', 'branch', 'network'));
    }

    public function store(Request $request, Network $network, School $branch)
    {
        $school = $this->validateContext($network, $branch);

        $request->validate(['name' => 'required|string|max:255']);

        $grade = Grade::create([
            'name' => $request->input('name'),
            'network_id' => $network->id,
            'school_id' => $school->id,
            'created_by' => Auth::id(),
            'created_in' => $school->id,
        ]);

        $grade->schools()->syncWithoutDetaching([$school->id]);

        return redirect()->back()->with('success', __('messages.grades.grade_created'));
    }

    public function archive(Request $request, Network $network, School $branch, Grade $grade)
    {
        $school = $this->validateContext($network, $branch);

        if ($grade->created_in !== $school->id) {
            abort(403);
        }

        $grade->delete();

        return redirect()->back()->with('success', __('messages.grades.grade_archived'));
    }

    public function restore(Request $request, Network $network, School $branch, $gradeId)
    {
        $school = $this->validateContext($network, $branch);

        $grade = Grade::withTrashed()
            ->where('created_in', $school->id)
            ->findOrFail($gradeId);

        $grade->restore();

        return redirect()->back()->with('success', __('messages.grades.grade_restored'));
    }
}
