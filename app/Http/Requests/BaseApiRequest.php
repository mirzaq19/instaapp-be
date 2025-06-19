<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseApiRequest extends FormRequest
{
 /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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

    /**
     * When we fail validation, override our default error.
     *
     * @param ValidatorContract $validator
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $this->validator->errors();

        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                "success" => false,
                "message" => "Validation error",
                "error" => [
                    "name" => "ValidationException",
                    "code" => 422,
                    "messages" => $errors
                ]
            ], 422)
        );
    }
}
