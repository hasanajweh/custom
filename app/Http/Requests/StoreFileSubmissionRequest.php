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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $planTypes = ['daily_plan', 'weekly_plan', 'monthly_plan'];
        $submissionType = $this->input('submission_type');
        $isPlan = in_array($submissionType, $planTypes);

        // For plans, convert empty strings to null for subject_id and grade_id
        if ($isPlan) {
            if ($this->has('subject_id') && $this->input('subject_id') === '') {
                $this->merge(['subject_id' => null]);
            }
            if ($this->has('grade_id') && $this->input('grade_id') === '') {
                $this->merge(['grade_id' => null]);
            }
        }
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

        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
                // Unicode letters, numbers, punctuation, brackets, quotes, slashes
                "regex:/^[\p{L}\p{N}\p{M}\s\-_.,;:!?()\[\]{}'\"\/]+$/u"
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'file' => [
                'required',
                'file',
                // No size limit here - validated later
            ],
            'submission_type' => [
                'required',
                'string',
                'in:exam,worksheet,summary,daily_plan,weekly_plan,monthly_plan'
            ],
        ];

        // For plans, subject & grade are optional
        if ($isPlan) {
            $rules['subject_id'] = ['nullable', 'sometimes'];
            $rules['grade_id'] = ['nullable', 'sometimes'];

        } else {
            // For general resources, subject and grade are required
            $subjectRules = [];
            $gradeRules = [];

            if ($school) {
                $subjectRules[] = Rule::exists('subjects', 'id')->where('school_id', $school->id);
                $gradeRules[] = Rule::exists('grades', 'id')->where('school_id', $school->id);
            }

            if ($user && method_exists($user, 'isTeacher') && $user->isTeacher()) {
                $subjectRules[] = Rule::exists('subject_user', 'subject_id')->where(fn($query) => $query
                    ->where('user_id', $user->id)
                    ->where('school_id', $school->id ?? $user->school_id)
                );
                $gradeRules[] = Rule::exists('grade_user', 'grade_id')->where(fn($query) => $query
                    ->where('user_id', $user->id)
                    ->where('school_id', $school->id ?? $user->school_id)
                );
            }

            $rules['subject_id'] = array_merge(['required', 'integer'], $subjectRules);
            $rules['grade_id'] = array_merge(['required', 'integer'], $gradeRules);
        }

        return $rules;
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
            'title.regex' => 'Title contains invalid characters. Please use only letters, numbers, spaces, and common punctuation.',
            'file.required' => 'Please select a file to upload.',
            'file.max' => 'File size error.',
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