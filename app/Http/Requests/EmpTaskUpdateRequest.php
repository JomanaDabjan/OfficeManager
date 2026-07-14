<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmpTaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Get the task instance from the route
        $task = $this->route('task');

        // Check two conditions:
        // 1. The user must be an employee.
        // 2. The task must belong to this specific employee.
        return Auth::check() &&
            Auth::user()->role === 'employee' &&
            $task &&
            $task->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // The employee is only allowed to update the status of the task.
            'status' => ['required', 'in:pending,accepted,in_progress,completed,rejected'],
        ];
    }
}