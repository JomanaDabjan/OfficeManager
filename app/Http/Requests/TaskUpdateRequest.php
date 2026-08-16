<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Authorization is centrally managed in the TaskController middleware.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * We use 'sometimes' to allow updating only specific fields.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Title is optional, must be a string if provided.
            'title'       => ['sometimes', 'string', 'max:255'],

            // Description is optional, must be a string if provided.
            'description' => ['sometimes', 'string'],

            // Project ID is optional; must exist in projects table if provided.
            'project_id'  => ['sometimes', 'exists:projects,id'],

            // User ID is optional; must exist in users table if provided.
            'user_id'     => ['nullable', 'exists:users,id'],

            // Status is optional; must be one of the allowed workflow states.
            'status'      => ['sometimes', 'in:pending,accepted,in_progress,completed,rejected'],

            // Started at date/time is optional, must be a valid date if provided.
            'started_at'  => ['nullable', 'date'],

            // Due date/time is optional, must be a valid date if provided.
            'due_date'    => ['nullable', 'date'],

            // Attachment is optional during update, but if provided, must be a valid file within size limits.
            'attachment'  => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png,jpeg', 'max:2048'],
        ];
    }
}