<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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
            // Because this is an update request, we use 'sometimes' to indicate that these fields are optional. If they are present in the request, they will be validated according to the specified rules.
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],

            // Validate that the manager_id, if provided, exists in the users table

            'manager_id'  => ['sometimes', 'exists:users,id'],

            // Check if the status is provided, and if so, validate it against the allowed values

            'status'      => ['sometimes', 'in:pending,in_progress,completed'],
        ];
    }
}
