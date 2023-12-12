<?php

namespace App\Http\Requests;


use App\Enums\AuthMethodEnum;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreDataRequest extends FormRequest
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
            
            "wallet_id" => ['required', 'string', 'max:255', 'exists:wallets,id'],
            "customer_id" => ['required', 'string', 'max:55'],
            "amount" => ['required', 'numeric', 'min:100'],
            "transaction_no" => ['required', 'string', 'max:55', 'unique:transactions,number'],
            "add_beneficiary" => ['sometimes', 'boolean'],
            "package_id" => ['required', 'string', 'max:2000'],
            "auth_method" => ['required', new Enum(AuthMethodEnum::class)],
            "auth" => ['required', 'string', 'max:255'],
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
