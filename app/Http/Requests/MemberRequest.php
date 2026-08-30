<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/*
|--------------------------------------------------------------------------
| MemberRequest Form Request Class
|--------------------------------------------------------------------------
| This class is responsible for handling validation rules and authorization
| checks when adding or managing team members in the application.
*/

class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /*
         * Change from false to true to allow authorized users
         * to pass through this request filter.
         */
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
            /*
            |--------------------------------------------------------------------------
            | Members Validation Rules
            |--------------------------------------------------------------------------
            | 'members' ensures that the input is a mandatory array of IDs.
            | 'members.*' checks every individual ID inside the array to make sure
            | it exists within the 'id' column of the 'users' database table.
            */
            'members'   => ['required', 'array'],
            'members.*' => ['exists:users,id'],
        ];
    }
}