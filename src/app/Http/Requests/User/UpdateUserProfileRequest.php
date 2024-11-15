<?php

namespace App\Http\Requests\User;

use App\Http\Controllers\CustomController;
use App\Http\Requests\CustomFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends CustomFormRequest
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
            'educations' => 'array',
            'educations.*.id' => 'nullable|integer',
            'educations.*.institution_name' => 'string',
            'educations.*.degree' => 'string',
            'educations.*.start_date' => 'date',
            'educations.*.end_date' => 'nullable|date',
            'educations.*.description' => 'string|nullable',

            'experiences' => 'array',
            'experiences.*.id' => 'nullable|integer',
            'experiences.*.company_name' => 'string|max:255',
            'experiences.*.role' => 'string|max:255',
            'experiences.*.start_date' => 'date',
            'experiences.*.end_date' => 'nullable|date|after_or_equal:start_date',
            'experiences.*.description' => 'string|nullable',

            'skills' => 'array',
            'skills.*.id' => 'nullable|integer',
            'skills.*.skill_name' => 'string',
            'skills.*.proficiency' => 'string',

            'websites' => 'array',
            'websites.*.id' => 'nullable|integer',
            'websites.*.name' => 'string',
            'websites.*.url' => 'url',
            'websites.*.icon' => 'nullable',
            'websites.*.description' => 'string|nullable',

            'name' => 'string|max:255',
            'user_name' => 'string|max:255|unique:users,user_name',
            'contact_information' => 'string|max:255',
            'password' => 'string|min:8'

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
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not be greater than 255 characters',
            'user_name.string' => 'User name must be a string',
            'user_name.max' => 'User name must not be greater than 255 characters',
            'user_name.unique' => 'User name must be unique',
            'contact_information.string' => 'Contact information must be a string',
            'contact_information.max' => 'Contact information must not be greater than 255 characters',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must not be less than 8 characters',

            'educations.*.institution_name.string' => 'Institution name must be a string',
            'educations.*.degree.string' => 'Degree must be a string',
            'educations.*.start_date.date' => 'Start date must be a date',
            'educations.*.end_date.date' => 'End date must be a date',
            'educations.*.description.string' => 'Description must be a string',

            'experiences.*.company_name.string' => 'Company name must be a string',
            'experiences.*.role.string' => 'Role must be a string',
            'experiences.*.start_date.date' => 'Start date must be a date',
            'experiences.*.end_date.date' => 'End date must be a date',
            'experiences.*.description.string' => 'Description must be a string',

            'skills.*.skill_name.string' => 'Skill name must be a string',
            'skills.*.proficiency.string' => 'Proficiency must be a string',

            'websites.*.name.string' => 'Name must be a string',
            'websites.*.url.url' => 'URL must be a valid URL',
            'websites.*.description.string' => 'Description must be a string',
        ];
    }
}
