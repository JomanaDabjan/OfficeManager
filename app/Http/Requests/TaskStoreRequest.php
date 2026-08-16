<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Authorization is centrally managed via Middleware in the TaskController.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * These rules cover the requirements for both Admin and Project Manager roles.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Title is required, must be a string, and max 255 characters.
            'title'       => ['required', 'string', 'max:255'],

            // Description is required and must be a string.
            'description' => ['required', 'string'],

            // Project ID must exist in the projects table to ensure a valid relationship.
            'project_id'  => ['required', 'exists:projects,id'],

            // User ID is optional (nullable), but if provided, must exist in the users table.
            'user_id'     => ['nullable', 'exists:users,id'],

            // Status is required and must match one of the allowed workflow states.
            'status'      => ['required', 'in:pending,accepted,in_progress,completed,rejected'],

            // Started at date/time is optional, but if provided, must be a valid date.
            'started_at'  => ['nullable', 'date'],

            // Due date/time is optional, but if provided, must be a valid date, and can include after_or_equal if needed.
            'due_date'    => ['nullable', 'date'],

            // Attachment is optional, but if provided, must be a valid file type and within size limits.
            'attachment'  => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png,jpeg', 'max:2048'],
        ];
    }
}