<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],

            // Validate that the manager_id is provided and exists in the users table

            'manager_id' => ['required', 'exists:users,id'],

            // Check if the status is provided, and if so, validate it against the allowed values
            'status'      => ['sometimes', 'in:pending,in_progress,completed'],
        ];
    }
}
