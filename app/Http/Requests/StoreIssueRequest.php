<?php

namespace App\Http\Requests;

use App\Models\Enum\IssuePriority;
use App\Models\Enum\IssueStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest
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
            //
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string','max:255'],
            'description' => ['required', 'string','max:255'],
            'status' => ['required', new \Illuminate\Validation\Rules\Enum(IssueStatus::class)],
            'priority' => ['required', new \Illuminate\Validation\Rules\Enum(IssuePriority::class)],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
