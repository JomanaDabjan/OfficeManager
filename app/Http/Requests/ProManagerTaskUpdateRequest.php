<?php

namespace App\Http\Requests;

//use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProManagerTaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure that the task being updated belongs to a project managed by the currently authenticated project manager.
        $task = $this->route('task');
        return $task && $task->project->manager_id === Auth::id();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'project_id'  => 'nullable|exists:projects,id',
            'user_id'     => 'nullable|exists:users,id',
            'status'      => 'nullable|in:pending,in_progress,completed',
        ];
    }
}
