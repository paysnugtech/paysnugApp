<?php

namespace App\Http\Requests;

use App\Enums\AuthMethodEnum;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreAirtimeRequest extends FormRequest
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
            "auth" => ['required', 'string', 'max:255'],
            "auth_method" => ['required', new Enum(AuthMethodEnum::class)],
            'wallet_id' => ['required', 'exists:wallets,id', 'max:255'],
            'snug_id' => ['required', 'string', 'max:2500'],
            'customer_id' => ['required', 'string', 'digits:11'],
            'amount' => ['required', 'numeric', 'min:50'],
            'add_beneficiary' => ['sometimes', 'required', 'boolean'],
            'transaction_no' => ['required', 'string', 'min:3', 'max:255', 'unique:transactions,number']
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
