<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email|exists:password_reset_tokens,email',
            'token' => 'required|digits:6',
            'password' => 'required|string|min:8|max:15'
        ];
    }

    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'status' => "false",
            'message' => 'Validation error!',
            'error' => $validator->errors(),
        ]));
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'Email does not exist',
        ];
    }
}
