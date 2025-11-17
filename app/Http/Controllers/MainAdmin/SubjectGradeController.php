<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Network;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectGradeController extends Controller
{
    public function index(Network $network): View
    {
        $branches = $network->branches()->get();
        $subjects = Subject::with(['schools'])
            ->where(function ($query) use ($network) {
                $query->where('network_id', $network->id)
                    ->orWhereNull('school_id');
            })
            ->get();

        $grades = Grade::with(['schools'])
            ->where(function ($query) use ($network) {
                $query->where('network_id', $network->id)
                    ->orWhereNull('school_id');
            })
            ->get();

        return view('main-admin.subjects-grades', [
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
            'branches' => ['array'],
            'branches.*' => ['exists:schools,id'],
        ]);

        $branches = School::where('network_id', $network->id)
            ->whereIn('id', $data['branches'] ?? [])
            ->pluck('id');

        if ($data['type'] === 'subject') {
            $subject = Subject::create([
                'name' => $data['name'],
                'network_id' => $network->id,
                'school_id' => null,
            ]);
            $subject->schools()->sync($branches);
        } else {
            $grade = Grade::create([
                'name' => $data['name'],
                'network_id' => $network->id,
                'school_id' => null,
            ]);
            $grade->schools()->sync($branches);
        }

        return back()->with('status', __('messages.main_admin.subjects_grades.saved'));
    }

    public function update(Network $network, string $type, int $id): RedirectResponse
    {
        abort_unless(in_array($type, ['subject', 'grade']), 404);

        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'branches' => ['array'],
            'branches.*' => ['exists:schools,id'],
        ]);

        $branches = School::where('network_id', $network->id)
            ->whereIn('id', $data['branches'] ?? [])
            ->pluck('id');

        if ($type === 'subject') {
            $item = Subject::where('network_id', $network->id)->findOrFail($id);
            $item->update(['name' => $data['name'], 'network_id' => $network->id]);
            $item->schools()->sync($branches);
        } else {
            $item = Grade::where('network_id', $network->id)->findOrFail($id);
            $item->update(['name' => $data['name'], 'network_id' => $network->id]);
            $item->schools()->sync($branches);
        }

        return back()->with('status', __('messages.main_admin.subjects_grades.updated'));
    }

    public function destroy(Network $network, string $type, int $id): RedirectResponse
    {
        abort_unless(in_array($type, ['subject', 'grade']), 404);

        $item = $type === 'subject'
            ? Subject::where('network_id', $network->id)->findOrFail($id)
            : Grade::where('network_id', $network->id)->findOrFail($id);

        $item->delete();

        return back()->with('status', __('messages.main_admin.subjects_grades.archived'));
    }
}
