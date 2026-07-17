<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Just allow admins to create new users
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'role'     => ['required', 'in:project_manager,employee'],
        ];
    }
}
