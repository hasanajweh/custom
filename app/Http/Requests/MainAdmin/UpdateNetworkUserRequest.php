<?php

namespace App\Http\Requests\MainAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNetworkUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'main_admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
            'assignments' => ['required', 'array', 'min:1'],
            'assignments.*.school_id' => ['required', 'exists:schools,id'],
            'assignments.*.roles' => ['required', 'array', 'min:1'],
            'assignments.*.roles.*' => ['required', Rule::in(['admin', 'supervisor', 'teacher'])],
            'assignments.*.subjects' => ['array'],
            'assignments.*.subjects.*' => ['exists:subjects,id'],
            'assignments.*.grades' => ['array'],
            'assignments.*.grades.*' => ['exists:grades,id'],
            'assignments.*.teacher_subjects' => ['array'],
            'assignments.*.teacher_subjects.*' => ['exists:subjects,id'],
            'assignments.*.teacher_grades' => ['array'],
            'assignments.*.teacher_grades.*' => ['exists:grades,id'],
            'assignments.*.supervisor_subjects' => ['array'],
            'assignments.*.supervisor_subjects.*' => ['exists:subjects,id'],
            'assignments.*.supervisor_grades' => ['array'],
            'assignments.*.supervisor_grades.*' => ['exists:grades,id'],
        ];
    }
}
