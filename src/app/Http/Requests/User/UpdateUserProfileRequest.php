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
            'education' => 'array',
            'education.*.id' => 'nullable|integer',
            'education.*.institution_name' => 'string',
            'education.*.degree' => 'string',
            'education.*.start_date' => 'date',
            'education.*.end_date' => 'nullable|date',
            'education.*.description' => 'string',

            'experience' => 'array',
            'experience.*.id' => 'nullable|integer',
            'experience.*.company_name' => 'string|max:255',
            'experience.*.role' => 'string|max:255',
            'experience.*.start_date' => 'date',
            'experience.*.end_date' => 'nullable|date|after_or_equal:start_date',
            'experience.*.description' => 'string',

            'skills' => 'array',
            'skills.*.id' => 'nullable|integer',
            'skills.*.skill_name' => 'string',
            'skills.*.proficiency' => 'string',

            'website' => 'array',
            'website.*.id' => 'nullable|integer',
            'website.*.name' => 'string',
            'website.*.url' => 'url',
            'website.*.icon' => 'nullable',
            'website.*.description' => 'string',

            'user' => 'array',
            'user.name' => 'string|max:255',
            'user.user_name' => 'string|max:255|unique:users,user_name',
            'user.contact_information' => 'string|max:255',
            'user.password' => 'string|min:8'

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
            'user.name.string' => 'Name must be a string',
            'user.name.max' => 'Name must not be greater than 255 characters',
            'user.user_name.string' => 'User name must be a string',
            'user.user_name.max' => 'User name must not be greater than 255 characters',
            'user.user_name.unique' => 'User name must be unique',
            'user.contact_information.string' => 'Contact information must be a string',
            'user.contact_information.max' => 'Contact information must not be greater than 255 characters',
            'user.password.string' => 'Password must be a string',
            'user.password.min' => 'Password must not be less than 8 characters',

            'education.*.institution_name.string' => 'Institution name must be a string',
            'education.*.degree.string' => 'Degree must be a string',
            'education.*.start_date.date' => 'Start date must be a date',
            'education.*.end_date.date' => 'End date must be a date',
            'education.*.description.string' => 'Description must be a string',

            'experience.*.company_name.string' => 'Company name must be a string',
            'experience.*.role.string' => 'Role must be a string',
            'experience.*.start_date.date' => 'Start date must be a date',
            'experience.*.end_date.date' => 'End date must be a date',
            'experience.*.description.string' => 'Description must be a string',

            'skills.*.skill_name.string' => 'Skill name must be a string',
            'skills.*.proficiency.string' => 'Proficiency must be a string',

            'website.*.name.string' => 'Name must be a string',
            'website.*.url.url' => 'URL must be a valid URL',
            'website.*.description.string' => 'Description must be a string',
        ];
    }
}
