<?php

namespace App\Http\Requests\Department;

use App\Http\Requests\CustomFormRequest;

class StoreDepartmentRequest extends CustomFormRequest
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
            'name' => 'required|string|max:255|unique:departments',
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
             'name.required' => 'Department name is required',
             'name.string' => 'Department name must be a string',
             'name.max' => 'Department name must not be greater than 255 characters',
             'name.unique' => 'Department name must be unique',
             'description.string' => 'Department description must be a string',
             'description.max' => 'Department description must not be greater than 255 characters',
             'is_active.boolean' => 'Department status must be a boolean',
         ];
     }
}
