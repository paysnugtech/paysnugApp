<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreManagerRequest extends FormRequest
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
            'first_name' => 'required|string|max:30',
            'other_name' => 'required|string|max:30',
            'phone_no' => 'required|digits_between:11,15|unique:managers',
            'email' => 'required|email|max:30|unique:managers',
            'whatsapp_no' => 'required|digits_between:11,15|unique:managers',
        ];
    }


    public function failedValidation(Validator $validator){

            throw new HttpResponseException(response()->json([
                'status' => 'failed',
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422));
    }

}
