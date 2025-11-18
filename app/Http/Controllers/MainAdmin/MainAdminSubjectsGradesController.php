<?php

namespace App\Http\Controllers\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Network;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MainAdminSubjectsGradesController extends Controller
{
    public function index(Network $network): View
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $branches = $userNetwork->schools()->get();
        $subjects = Subject::with(['schools'])
            ->where(function ($query) use ($userNetwork) {
                $query->where('network_id', $userNetwork->id)
                    ->orWhereNull('school_id');
            })
            ->get();

        $grades = Grade::with(['schools'])
            ->where(function ($query) use ($userNetwork) {
                $query->where('network_id', $userNetwork->id)
                    ->orWhereNull('school_id');
            })
            ->get();

        return view('main-admin.subjects-grades', [
            'network' => $userNetwork,
            'branches' => $branches,
            'subjects' => $subjects,
            'grades' => $grades,
        ]);
    }

    public function store(Network $network): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);

        $data = request()->validate([
            'type' => ['required', 'in:subject,grade'],
            'name' => ['required', 'string', 'max:255'],
            'branches' => ['required', 'array', 'min:1'],
            'branches.*' => ['exists:schools,id'],
        ]);

        $branches = School::where('network_id', $userNetwork->id)
            ->whereIn('id', $data['branches'])
            ->pluck('id');

        if ($branches->isEmpty()) {
            return back()->withErrors([
                'branches' => __('messages.main_admin.subjects_grades.invalid_branch_selection')
                    ?? __('messages.validation.select_at_least_one_school')
                    ?? 'Please select at least one school from this network.',
            ])->withInput();
        }

        $primarySchool = $branches->first();

        if ($data['type'] === 'subject') {
            $subject = Subject::create([
                'name' => $data['name'],
                'network_id' => $userNetwork->id,
                'school_id' => $primarySchool,
            ]);
            $subject->schools()->sync($branches);
        } else {
            $grade = Grade::create([
                'name' => $data['name'],
                'network_id' => $userNetwork->id,
                'school_id' => $primarySchool,
            ]);
            $grade->schools()->sync($branches);
        }

        return back()->with('status', __('messages.main_admin.subjects_grades.saved'));
    }

    public function update(Network $network, string $type, int $id): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);
        abort_unless(in_array($type, ['subject', 'grade']), 404);

        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'branches' => ['required', 'array', 'min:1'],
            'branches.*' => ['exists:schools,id'],
        ]);

        $branches = School::where('network_id', $userNetwork->id)
            ->whereIn('id', $data['branches'])
            ->pluck('id');

        if ($branches->isEmpty()) {
            return back()->withErrors([
                'branches' => __('messages.main_admin.subjects_grades.invalid_branch_selection')
                    ?? __('messages.validation.select_at_least_one_school')
                    ?? 'Please select at least one school from this network.',
            ])->withInput();
        }

        if ($type === 'subject') {
            $item = Subject::where('network_id', $userNetwork->id)->findOrFail($id);
            $item->update(['name' => $data['name'], 'network_id' => $userNetwork->id]);
            $item->schools()->sync($branches);
        } else {
            $item = Grade::where('network_id', $userNetwork->id)->findOrFail($id);
            $item->update(['name' => $data['name'], 'network_id' => $userNetwork->id]);
            $item->schools()->sync($branches);
        }

        return back()->with('status', __('messages.main_admin.subjects_grades.updated'));
    }

    public function destroy(Network $network, string $type, int $id): RedirectResponse
    {
        $userNetwork = auth()->user()?->network;

        abort_unless($userNetwork && $userNetwork->is($network), 404);
        abort_unless(in_array($type, ['subject', 'grade']), 404);

        $item = $type === 'subject'
            ? Subject::where('network_id', $userNetwork->id)->findOrFail($id)
            : Grade::where('network_id', $userNetwork->id)->findOrFail($id);

        $item->delete();

        return back()->with('status', __('messages.main_admin.subjects_grades.archived'));
    }
}
