<?php

namespace App\Http\Requests\MainAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNetworkUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'main_admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'assignments' => ['required', 'array', 'min:1'],
            'assignments.*.school_id' => ['required', 'exists:schools,id'],
            'assignments.*.roles' => ['array'],
            'assignments.*.roles.*' => [Rule::in(['admin', 'supervisor', 'teacher'])],
            'assignments.*.subjects' => ['array'],
            'assignments.*.subjects.*' => ['exists:subjects,id'],
            'assignments.*.grades' => ['array'],
            'assignments.*.grades.*' => ['exists:grades,id'],
        ];
    }
}
