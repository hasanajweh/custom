<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Network;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubjectsGradesController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->get();
        $subjects = Subject::where('network_id', $network->id)
            ->with('schools')
            ->get();

        $grades = Grade::where('network_id', $network->id)
            ->with('schools')
            ->get();

        return view('main-admin.subjects_grades.index', [
            'network' => $network,
            'branches' => $branches,
            'subjects' => $subjects,
            'grades' => $grades,
        ]);
    }

    public function store(Network $network): RedirectResponse
    {
        $data = request()->validate([
            'type' => ['required', 'in:subject,grade'],
            'name' => ['required', 'string', 'max:255'],
            'branches' => ['required', 'array', 'min:1'],
            'branches.*' => ['exists:schools,id'],
        ]);

        $branchIds = School::where('network_id', $network->id)
            ->whereIn('id', $data['branches'])
            ->pluck('id');

        if ($branchIds->isEmpty()) {
            return back()->withErrors([
                'branches' => __('messages.validation.select_at_least_one_school') ?? __('Please select at least one school.'),
            ])->withInput();
        }

        $creatorSchool = auth()->user()?->school_id;
        $primarySchool = $branchIds->first();

        DB::transaction(function () use ($data, $network, $branchIds, $creatorSchool, $primarySchool) {
            if ($data['type'] === 'subject') {
                $subject = Subject::create([
                    'name' => $data['name'],
                    'network_id' => $network->id,
                    'school_id' => $primarySchool,
                    'created_by' => auth()->id(),
                    'created_in' => $creatorSchool,
                ]);
                $subject->schools()->sync($branchIds);
            } else {
                $grade = Grade::create([
                    'name' => $data['name'],
                    'network_id' => $network->id,
                    'school_id' => $primarySchool,
                    'created_by' => auth()->id(),
                    'created_in' => $creatorSchool,
                ]);
                $grade->schools()->sync($branchIds);
            }
        });

        return back()->with('status', __('messages.main_admin.subjects_grades.saved'));
    }

    public function update(Network $network, string $type, int $id): RedirectResponse
    {
        abort_unless(in_array($type, ['subject', 'grade']), 404);

        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'branches' => ['required', 'array', 'min:1'],
            'branches.*' => ['exists:schools,id'],
        ]);

        $branchIds = School::where('network_id', $network->id)
            ->whereIn('id', $data['branches'])
            ->pluck('id');

        if ($branchIds->isEmpty()) {
            return back()->withErrors([
                'branches' => __('messages.validation.select_at_least_one_school') ?? __('Please select at least one school.'),
            ])->withInput();
        }

        if ($type === 'subject') {
            $item = Subject::where('network_id', $network->id)->findOrFail($id);
        } else {
            $item = Grade::where('network_id', $network->id)->findOrFail($id);
        }

        DB::transaction(function () use ($item, $data, $branchIds, $type) {
            $item->update([
                'name' => $data['name'],
                'school_id' => $branchIds->first(),
            ]);

            $item->schools()->sync($branchIds);
        });

        return back()->with('status', __('messages.main_admin.subjects_grades.updated'));
    }

    public function destroy(Network $network, string $type, int $id): RedirectResponse
    {
        abort_unless(in_array($type, ['subject', 'grade']), 404);

        $item = $type === 'subject'
            ? Subject::where('network_id', $network->id)->findOrFail($id)
            : Grade::where('network_id', $network->id)->findOrFail($id);

        $item->schools()->detach();
        $item->delete();

        return back()->with('status', __('messages.main_admin.subjects_grades.archived'));
    }
}
