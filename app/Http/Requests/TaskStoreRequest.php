<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'project_id'  => ['required', 'exists:projects,id'], // To ensure that the project exists in the projects table
            'user_id'     => ['nullable', 'exists:users,id'],    // To ensure that the user exists in the users table, but it's optional
            'status'      => ['required', 'in:pending,accepted,in_progress,completed,rejected'],
        ];
    }
}
