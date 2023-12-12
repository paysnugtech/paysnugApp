<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccountEnquiryRequest extends FormRequest
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
            'account_no' => ['required', 'string', 'digits:10'],
            'bank_code' => ['required', 'string', 'max:255'],
        ];
    }


    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'status' => 'failed',
            'message' => 'Validation error!',
            'error' => $validator->errors(),
        ]));
    }
}
