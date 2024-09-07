<?php

namespace App\Http\Requests\Receptionist;

use App\Http\Requests\CustomFormRequest;

class UpdateReceptionistRequest extends CustomFormRequest
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
            'name' => 'string|max:255',
            'user_name' => 'string|max:255|unique:users',
            'contact_information' => 'string|max:255',
            'password' => 'nullable|string|min:8',

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

        ];
    }
}
