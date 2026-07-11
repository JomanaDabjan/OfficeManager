<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class ProManagerTaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * We verify that the selected project belongs to the
     * currently authenticated project manager.
     */
    public function authorize(): bool
    {
        $projectId = $this->input('project_id');
        $project = Project::find($projectId);

        // Check if the project exists and if its manager_id matches the logged-in user
        return $project && $project->manager_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'project_id'  => ['required', 'exists:projects,id'],
            'user_id'     => ['nullable', 'exists:users,id'],
            'status'      => ['required', 'in:pending,in_progress,completed'],
        ];
    }
}
