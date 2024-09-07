<?php

namespace App\Http\Requests\Department;

use App\Http\Requests\CustomFormRequest;

class UpdateDepartmentRequest extends CustomFormRequest
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
            'name' => 'string|max:255|unique:departments',
            'description' => 'string|max:255',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required' => 'ID is required',
            'id.integer' => 'ID must be an integer',
            'id.exists' => 'ID does not exist',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not exceed 255 characters',
            'name.unique' => 'Name must be unique',
            'description.string' => 'Description must be a string',
            'description.max' => 'Description must not exceed 255 characters',
            'is_active.boolean' => 'Is active must be a boolean',
        ];
    }
}
