<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetDataPackageRequest extends FormRequest
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
            "snug_id" => ['required', 'string', 'max:2000']
        ];
    }


    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException($this->errorResponse(
            422,
            'Validation error!',
            $validator->errors()
        ));
    }
}
