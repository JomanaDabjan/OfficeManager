<?php

namespace App\Http\Requests\ProjectManager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure that the authenticated user is the manager of the project being updated. This is a security check to prevent unauthorized users from updating projects they do not manage. We use route('project') to get the project being updated and check if its manager_id matches the id of the currently authenticated user.
        // To pring the project being updated, you can use the following code:
        $project = $this->route('project');

        return $project && $project->manager_id === Auth::id();
    }

    /**
     * قواعد التحقق (Validation Rules)
     */
    public function rules(): array
    {
        return [
            // We check only the description and status fields for updates. The title field is not included in the update request.
            'description' => ['required', 'string', 'max:1000'],
            'status'      => ['required', 'in:in_progress,completed'],
        ];
    }
}
