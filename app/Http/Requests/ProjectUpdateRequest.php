<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Authorization logic is handled within the Controller's middleware.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * We use 'sometimes' to allow partial updates of project fields.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Title is optional, must be a string if provided, with a maximum of 255 characters
            'title'       => ['sometimes', 'string', 'max:255'],

            // Description is optional and must be a string if provided
            'description' => ['sometimes', 'string'],

            // Manager ID is optional; if provided, it must exist in the users table
            'manager_id'  => ['sometimes', 'exists:users,id'],

            // Status is optional; if provided, it must be one of the specified allowed values
            'status'      => ['sometimes', 'in:pending,in_progress,completed'],

            // Project start date is optional; must be a valid date if provided
            'start_date'  => ['sometimes', 'nullable', 'date'],

            // Project end date is optional; must be a valid date and not earlier than start date if provided
            'end_date'    => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}