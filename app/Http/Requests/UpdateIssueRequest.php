<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIssueRequest extends FormRequest
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
            'status' => ['required', 'string','max:255'],
            'priority' => ['required', 'string','max:255'],
            'due_date' => ['required', 'date'],
        ];
    }
}
