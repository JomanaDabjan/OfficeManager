<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
            'project_id'  => ['sometimes', 'exists:projects,id'],
            'user_id'     => ['nullable', 'exists:users,id'],
            'status'      => ['sometimes', 'in:pending,accepted,in_progress,completed,rejected'],
        ];
    }
}
