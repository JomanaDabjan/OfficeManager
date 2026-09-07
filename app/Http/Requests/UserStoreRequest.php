<?php

namespace App\Http\Requests;

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
            'role'          => ['required', 'string', 'in:admin,manager,employee,team_leader'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'position'      => ['nullable', 'string', 'max:255'],
            'department'    => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'in:active,deactivated'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'joining_date'  => ['nullable', 'date'],
        ];
    }
}