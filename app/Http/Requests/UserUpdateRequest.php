<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],

            // The rule class mean that the email must be unique in the users table, but it will ignore the current user's email when checking for uniqueness.
            // This allows the user to keep their existing email address without triggering a validation error.
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('user')->id)],

            'role'  => ['required', 'in:project_manager,employee'],
        ];
    }
}