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
            'role' => ['required', Rule::in(['admin', 'supervisor', 'teacher'])],
            'school_id' => ['required', 'exists:schools,id'],
            'subjects' => ['array'],
            'subjects.*' => ['exists:subjects,id'],
            'grades' => ['array'],
            'grades.*' => ['exists:grades,id'],
        ];
    }
}
