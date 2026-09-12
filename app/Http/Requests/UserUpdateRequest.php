<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $targetUser = $this->route('user');

        if (!$user) {
            return false;
        }

        // 1. السماح للـ Admin بتعديل أي مستخدم
        if (strtolower(trim($user->role)) === 'admin') {
            return true;
        }

        // 2. السماح للمستخدم بتعديل حسابه الشخصي (حتى لو كان مدير مشروع)
        if ($targetUser && $targetUser->id === $user->id) {
            return true;
        }

        // 3. ترك بقية الصلاحيات لـ Policy عبر الـ Controller
        return $user->can('update', $targetUser);
    }


    public function rules(): array
    {
        return [
            'name'  => ['sometimes', 'required', 'string', 'max:255'],

            // The rule class mean that the email must be unique in the users table, but it will ignore the current user's email when checking for uniqueness.
            // This allows the user to keep their existing email address without triggering a validation error.
            'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($this->route('user')->id)],

            'role'  => ['sometimes', 'required', 'in:admin,manager,team_leader,employee'],
            'password'      => ['nullable', 'string', 'min:6'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'position'      => ['nullable', 'string', 'max:255'],
            'department'    => ['nullable', 'string', 'max:255'],
            'status'        => ['sometimes', 'nullable', 'in:active,deactivated'],
            'working_hours' => ['nullable', 'string', 'max:255'],
            'joining_date'  => ['nullable', 'date'],
        ];
    }
}