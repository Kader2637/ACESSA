<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer',
            'name' => 'nullable|string',
            'codeClass' => 'nullable|string',
            'limit' => 'nullable|integer',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|file',
            'statusClass' => 'nullable|in:private,public',
            'semester_id' => 'nullable|exists:semesters,id',
            'sks' => 'nullable|integer|min:1|max:8'
        ];
    }
}
