<?php

namespace App\Http\Requests\Doctor;

use App\Http\Requests\CustomFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends CustomFormRequest
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
            // User data validation rules
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'contact_information' => 'string|max:255',
            'password' => 'nullable|string|min:8',

            // Doctor data validation rules
            'department_id' => 'required|integer|exists:departments,id',
            'description' => 'string',
            'income_percentage' => 'required|numeric|min:0|max:100',
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
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not be greater than 255 characters',
            'user_name.required' => 'User name is required',
            'user_name.string' => 'User name must be a string',
            'user_name.max' => 'User name must not be greater than 255 characters',
            'user_name.unique' => 'User name must be unique',
            'contact_information.string' => 'Contact information must be a string',
            'contact_information.max' => 'Contact information must not be greater than 255 characters',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must not be less than 8 characters',

            // Doctor data validation messages
            'department_id.required' => 'Department is required',
            'department_id.integer' => 'Department must be an integer',
            'department_id.exists' => 'Department must exist',
            'description.string' => 'Description must be a string',
            'income_percentage.required' => 'Income percentage is required',
            'income_percentage.numeric' => 'Income percentage must be a number',
            'income_percentage.min' => 'Income percentage must not be less than 0',
            'income_percentage.max' => 'Income percentage must not be greater than 100',
        ];
    }
}
