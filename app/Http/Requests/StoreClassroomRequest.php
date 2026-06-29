<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
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
            'name' => 'required',
            'codeClass' => 'required',
            'limit' => 'required',
            'description' => 'required',
            'thumbnail' => 'required',
            'statusClass' => 'required',
            'user_id' => 'nullable',
            'semester_id' => 'required|exists:semesters,id',
            'sks' => 'required|integer|min:1|max:8'
        ];
    }
}
