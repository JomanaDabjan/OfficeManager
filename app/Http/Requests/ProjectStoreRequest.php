<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Authorization is handled by the Middleware in the Controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Title must be present, a string, and not exceed 255 characters.
            'title'       => ['required', 'string', 'max:255'],

            // Description must be present and a string.
            'description' => ['required', 'string'],

            // Manager ID is required and must exist in the users table to ensure valid relationships.
            'manager_id'  => ['required', 'exists:users,id'],

            // Status is optional; if provided, it must match one of the defined statuses.
            'status'      => ['sometimes', 'in:pending,in_progress,completed'],

            // Project start date must be a valid date format.
            'start_date'  => ['nullable', 'date'],

            // Project end date must be a valid date and must not be earlier than the start date.
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}