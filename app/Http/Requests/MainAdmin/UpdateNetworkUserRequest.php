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
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user')->id ?? null)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'assignments' => ['required', 'array', 'min:1'],
            'assignments.*.branch_id' => ['required', 'exists:schools,id'],
            'assignments.*.role' => ['required', Rule::in(['admin', 'supervisor', 'teacher'])],
            'assignments.*.enabled' => ['nullable', 'boolean'],
            'assignments.*.subjects' => ['array'],
            'assignments.*.subjects.*' => ['integer', 'exists:subjects,id'],
            'assignments.*.grades' => ['array'],
            'assignments.*.grades.*' => ['integer', 'exists:grades,id'],
            'is_active' => ['boolean'],
        ];
    }
}
