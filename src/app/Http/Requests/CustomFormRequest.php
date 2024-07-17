<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CustomFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {

        $errors = $validator->errors()->all();

        $response = new JsonResponse([
            'status' => 'error',
            'message' => 'Validation errors occurred. Please check the inputs.',
            'errors' => $validator->errors()->getMessages(),
            'help' => 'For more information, visit our error handling documentation.',
        ], 422);

        throw (new ValidationException($validator, $response));
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
