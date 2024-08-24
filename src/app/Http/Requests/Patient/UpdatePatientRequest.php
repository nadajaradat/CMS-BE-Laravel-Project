<?php

namespace App\Http\Requests\Patient;

use App\Http\Requests\CustomFormRequest;

class UpdatePatientRequest extends CustomFormRequest
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
            'national_id' => 'string|max:255|unique:patients,national_id',
            'name' => 'string|max:255',
            'phone' => 'string|max:15',
            'date_of_birth' => 'date',
            'gender' => 'string|in:male,female,other',
            'uhid' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string',
        ];
    }




    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'national_id.string' => 'The national ID must be a string.',
            'national_id.max' => 'The national ID may not be greater than 255 characters.',
            'national_id.unique' => 'The national ID has already been taken.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'phone.string' => 'The phone number must be a string.',
            'phone.max' => 'The phone number may not be greater than 15 characters.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'gender.string' => 'The gender must be a string.',
            'gender.in' => 'The gender must be one of the following: male, female, other.',
            'uhid.string' => 'The UHID must be a string.',
            'uhid.max' => 'The UHID may not be greater than 255 characters.',
            'medical_history.string' => 'The medical history must be a string.',
        ];
    }
}
