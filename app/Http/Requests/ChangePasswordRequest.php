<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{

    use ResponseTrait;

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
            'password' => [
                'required', 
                'string', 
                'max:15', 
                'different:current_password',
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                // ->uncompromised()
            ],
            'current_password' => ['required', 'current_password'],
        ];
    }


    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException($this->errorResponse(
            422,
            'Validation error',
            $validator->errors()
        ));
    }
}
