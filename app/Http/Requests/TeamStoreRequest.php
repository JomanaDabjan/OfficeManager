<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/*
|--------------------------------------------------------------------------
| TeamStoreRequest Class
|--------------------------------------------------------------------------
| This Form Request class is responsible for validating incoming HTTP
| requests specifically when creating a new team in the system.
| It keeps validation logic clean and separated from the controller.
*/

class TeamStoreRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | Determine if the user is authorized to make this request.
    |--------------------------------------------------------------------------
    | Returns true to allow authenticated users (or admins) to execute
    | this creation request. (Changed from false to true to prevent 403 errors).
    */
    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Get the validation rules that apply to the request.
    |--------------------------------------------------------------------------
    | Defines the strict rules that incoming form fields must follow
    | before the data is safely passed to the controller and database.

    * @return array<string, ValidationRule|array<mixed>|string>
    */
    public function rules(): array
    {
        return [
            /* The team name is required, must be text, and cannot exceed 255 characters */
            'name'       => ['required', 'string', 'max:255'],

            /* Optional text description for the team objectives or notes */
            'description' => ['nullable', 'string'],

            /* The project ID is required and must exist in the projects table */
            'project_id' => ['required', 'exists:projects,id'],

            /* Members must be sent as an array of user IDs, and each user must exist */
            'members'    => ['nullable', 'array'],
            'members.*'  => ['exists:users,id'],
        ];
    }
}