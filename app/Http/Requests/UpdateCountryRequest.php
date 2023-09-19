<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class UpdateCountryRequest extends FormRequest
{

    protected $id;


    public function __construct(Request $request)
    {
        $this->id = $request->route()->country;
    }




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
            "name" => [
                'required', 
                'max:30', 
                'unique:countries,name,'.$this->id
            ],
            "currency" => [
                'required', 
                'max:30', 
                'unique:countries,currency,'.$this->id
            ],
            "currency_code" => [
                'required', 
                'max:30', 
                'unique:countries,currency_code,'.$this->id
            ],
            "is_available" => [
                'required', 
                new Enum(StatusEnum::class)
            ]
        ];
    }


    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => "validation error!",
            'error' => $validator->errors(),
        ], 422));
    }

}
