<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/*
|--------------------------------------------------------------------------
| TeamUpdateRequest Class
|--------------------------------------------------------------------------
| This Form Request class is responsible for validating incoming HTTP
| requests when an administrator updates an existing team in the system.
| It ensures that modified data meets all criteria before updating the database.
*/

class TeamUpdateRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | Determine if the user is authorized to make this request.
    |--------------------------------------------------------------------------
    | Returns true to allow authenticated users (or admins) to execute
    | this update request without hitting a 403 authorization error.
    */
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Get the validation rules that apply to the request.
    |--------------------------------------------------------------------------
    | Defines the rules for fields being updated. We use 'sometimes' or
    | conditional rules so that fields are only validated if they are
    | actually present in the incoming HTTP request.

    * @return array<string, ValidationRule|array<mixed>|string>
    */
    public function rules(): array
    {
        return [
            /* The team name is sometimes required, but if present, must be a valid text string up to 255 chars */
            'name'        => ['sometimes', 'required', 'string', 'max:255'],

            /* Optional description text field, can be null or updated with new text */
            'description' => ['nullable', 'string'],

            /* The project ID is sometimes required, but if provided, must exist in the projects table */
            'project_id'  => ['sometimes', 'required', 'exists:projects,id'],

            /* Members can be updated as an array of user IDs, validating that each user exists */
            'members'     => ['nullable', 'array'],
            'members.*'   => ['exists:users,id'],
        ];
    }
}