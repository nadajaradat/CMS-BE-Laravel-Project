<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends CustomFormRequest
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
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'contact_information' => 'required|string|max:255',
            'password' => 'string|min:8',
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
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not be greater than 255 characters',
            'user_name.required' => 'User name is required',
            'user_name.string' => 'User name must be a string',
            'user_name.max' => 'User name must not be greater than 255 characters',
            'user_name.unique' => 'User name must be unique',
            'contact_information.required' => 'Contact information is required',
            'contact_information.string' => 'Contact information must be a string',
            'contact_information.max' => 'Contact information must not be greater than 255 characters',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must not be less than 8 characters',
        ];
    }
}
