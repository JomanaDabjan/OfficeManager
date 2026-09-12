<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /** 
     * Determine if the user is authorized to make this request. 
     * Authorization is centrally managed via Middleware in the TaskController. 
     */
    public function authorize(): bool
    {
        return true;
    }

    /** 
     * Get the validation rules that apply to the request. 
     * These rules cover the requirements for both Admin and Project Manager roles. 
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> 
     */
    public function rules(): array
    {
        return [
            // الحقول الأساسية في الإضافة يجب أن تكون مطلوبة (required) 
            'title'       => ['required', 'string', 'max:255'],
            'project_id'  => ['required', 'exists:projects,id'],
            'status'      => ['required', 'in:pending,accepted,in_progress,completed,rejected'],
            'team_id'     => ['required', 'exists:teams,id'],
            'user_id'     => ['required', 'exists:users,id'],

            // الحقول الاختيارية 
            'description' => ['nullable', 'string'],
            'started_at'  => ['nullable', 'date'],
            'due_date'    => ['nullable', 'date'],
            'attachments'   => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,xls,zip,jpg,png,jpeg', 'max:2048'],
        ];
    }
}
