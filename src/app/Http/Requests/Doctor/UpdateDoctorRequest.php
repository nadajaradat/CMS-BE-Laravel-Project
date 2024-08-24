<?php

namespace App\Http\Requests\Doctor;

use App\Http\Requests\CustomFormRequest;

class UpdateDoctorRequest extends CustomFormRequest
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
            'name' => 'string|max:255',
            'user_name' => 'string|max:255|unique:users',
            'contact_information' => 'string|max:255',
            'password' => 'string|min:8',

            // Doctor data validation rules
            'department_id' => 'integer|exists:departments,id',
            'description' => 'nullable|string',
            'income_percentage' => 'numeric|min:0|max:100',
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
            // User data validation messages
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not be greater than 255 characters',
            'user_name.string' => 'User name must be a string',
            'user_name.max' => 'User name must not be greater than 255 characters',
            'user_name.unique' => 'User name must be unique',
            'contact_information.string' => 'Contact information must be a string',
            'contact_information.max' => 'Contact information must not be greater than 255 characters',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must not be less than 8 characters',

            // Doctor data validation messages
            'department_id.integer' => 'Department ID must be an integer',
            'department_id.exists' => 'Department ID must exist in the departments table',
            'description.string' => 'Description must be a string',
            'income_percentage.numeric' => 'Income percentage must be a number',
            'income_percentage.min' => 'Income percentage must not be less than 0',
            'income_percentage.max' => 'Income percentage must not be greater than 100',
        ];
    }
}
