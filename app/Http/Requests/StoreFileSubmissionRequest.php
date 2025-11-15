<?php

namespace App\Http\Requests;

use App\Support\SchoolResolver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFileSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $school = SchoolResolver::resolve($this->route('school'));
        $user = $this->user();

        // Super admins can upload to any school
        if ($user && $user->is_super_admin) {
            return true;
        }

        // Regular users must belong to the school
        return $school && $user && $user->school_id === $school->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan'];
        $submissionType = $this->input('submission_type');
        $isPlan = in_array($submissionType, $planTypes);
        $school = SchoolResolver::resolve($this->route('school'));
        $user = $this->user();

        $subjectRules = [];
        $gradeRules = [];

        if ($school) {
            $subjectRules[] = Rule::exists('subjects', 'id')->where('school_id', $school->id);
            $gradeRules[] = Rule::exists('grades', 'id')->where('school_id', $school->id);
        }

        if (! $isPlan && $user && method_exists($user, 'isTeacher') && $user->isTeacher()) {
            $subjectRules[] = Rule::exists('subject_user', 'subject_id')->where(fn($query) => $query
                ->where('user_id', $user->id)
                ->where('school_id', $school->id ?? $user->school_id)
            );
            $gradeRules[] = Rule::exists('grade_user', 'grade_id')->where(fn($query) => $query
                ->where('user_id', $user->id)
                ->where('school_id', $school->id ?? $user->school_id)
            );
        }

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{N}\s\-_.]+$/u' // Allow letters (all languages), numbers, spaces, hyphens, underscores, dots
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'file' => [
                'required',
                'file',
                'max:' . config('uploads.max_size_kb', 102400),
                // Don't validate mimes here - we do deeper validation in the service
            ],
            'submission_type' => [
                'required',
                'string',
                'in:exam,worksheet,summary,daily_plan,weekly_plan,monthly_plan'
            ],
            'subject_id' => [
                $isPlan ? 'nullable' : 'required',
                'integer',
                ...$subjectRules,
            ],
            'grade_id' => [
                $isPlan ? 'nullable' : 'required',
                'integer',
                ...$gradeRules,
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your file.',
            'title.regex' => 'Title can only contain letters, numbers, spaces, hyphens, underscores, and dots.',
            'file.required' => 'Please select a file to upload.',
            'file.max' => 'File size must not exceed ' . config('uploads.max_size_mb', 100) . 'MB.',
            'submission_type.required' => 'Please select a submission type.',
            'submission_type.in' => 'Invalid submission type selected.',
            'subject_id.required' => 'Subject is required for this submission type.',
            'subject_id.exists' => 'Selected subject is not available for your account.',
            'grade_id.required' => 'Grade is required for this submission type.',
            'grade_id.exists' => 'Selected grade is not available for your account.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'submission_type' => 'file type',
            'subject_id' => 'subject',
            'grade_id' => 'grade',
        ];
    }
}
