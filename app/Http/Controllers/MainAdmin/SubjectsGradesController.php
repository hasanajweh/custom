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

        // Enhanced Analytics for Subjects
        $subjectsAnalytics = $subjects->map(function($subject) use ($network) {
            $schoolIds = $subject->schools->pluck('id');
            
            // Get file submissions for this subject across all assigned schools
            $filesCount = \App\Models\FileSubmission::whereIn('school_id', $schoolIds)
                ->where('subject_id', $subject->id)
                ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
                ->count();
            
            // Get teachers using this subject
            $teachersCount = \App\Models\User::whereIn('school_id', $schoolIds)
                ->where('role', 'teacher')
                ->whereHas('fileSubmissions', function($q) use ($subject) {
                    $q->where('subject_id', $subject->id)
                      ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload']);
                })
                ->distinct()
                ->count();
            
            // Get total downloads
            $downloadsCount = \App\Models\FileSubmission::whereIn('school_id', $schoolIds)
                ->where('subject_id', $subject->id)
                ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
                ->sum('download_count');
            
            // This week files
            $thisWeekFiles = \App\Models\FileSubmission::whereIn('school_id', $schoolIds)
                ->where('subject_id', $subject->id)
                ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();
            
            return [
                'subject' => $subject,
                'files_count' => $filesCount,
                'teachers_count' => $teachersCount,
                'downloads_count' => $downloadsCount,
                'this_week_files' => $thisWeekFiles,
                'assigned_schools_count' => $subject->schools->count(),
            ];
        });

        // Enhanced Analytics for Grades
        $gradesAnalytics = $grades->map(function($grade) use ($network) {
            $schoolIds = $grade->schools->pluck('id');
            
            // Get file submissions for this grade across all assigned schools
            $filesCount = \App\Models\FileSubmission::whereIn('school_id', $schoolIds)
                ->where('grade_id', $grade->id)
                ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
                ->count();
            
            // Get teachers using this grade
            $teachersCount = \App\Models\User::whereIn('school_id', $schoolIds)
                ->where('role', 'teacher')
                ->whereHas('fileSubmissions', function($q) use ($grade) {
                    $q->where('grade_id', $grade->id)
                      ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload']);
                })
                ->distinct()
                ->count();
            
            // Get total downloads
            $downloadsCount = \App\Models\FileSubmission::whereIn('school_id', $schoolIds)
                ->where('grade_id', $grade->id)
                ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
                ->sum('download_count');
            
            // This week files
            $thisWeekFiles = \App\Models\FileSubmission::whereIn('school_id', $schoolIds)
                ->where('grade_id', $grade->id)
                ->whereNotIn('submission_type', ['daily_plan', 'weekly_plan', 'monthly_plan', 'supervisor_upload'])
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();
            
            return [
                'grade' => $grade,
                'files_count' => $filesCount,
                'teachers_count' => $teachersCount,
                'downloads_count' => $downloadsCount,
                'this_week_files' => $thisWeekFiles,
                'assigned_schools_count' => $grade->schools->count(),
            ];
        });

        // Overall Network Statistics
        $networkStats = [
            'total_subjects' => $subjects->count(),
            'total_grades' => $grades->count(),
            'total_branches' => $branches->count(),
            'subjects_with_files' => $subjectsAnalytics->where('files_count', '>', 0)->count(),
            'grades_with_files' => $gradesAnalytics->where('files_count', '>', 0)->count(),
            'total_files_network' => $subjectsAnalytics->sum('files_count'),
            'total_downloads_network' => $subjectsAnalytics->sum('downloads_count'),
        ];

        return view('main-admin.subjects_grades.index', [
            'network' => $network,
            'branches' => $branches,
            'subjects' => $subjects,
            'grades' => $grades,
            'subjectsAnalytics' => $subjectsAnalytics,
            'gradesAnalytics' => $gradesAnalytics,
            'networkStats' => $networkStats,
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
