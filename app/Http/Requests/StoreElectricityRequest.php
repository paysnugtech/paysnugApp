<?php

namespace App\Http\Requests;

use App\Enums\AuthMethodEnum;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreElectricityRequest extends FormRequest
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
            "wallet_id" => ['required', 'string', 'max:255', 'exists:wallets,id'],
            "customer_id" => ['required', 'string', 'max:55'],
            "customer_name" => ['required', 'string', 'max:55'],
            "amount" => ['required', 'numeric', 'min:100'],
            "transaction_no" => ['required', 'string', 'max:55', 'unique:transactions,number'],
            "add_beneficiary" => ['sometimes', 'boolean'],
            // "meter_type" => ['required', 'string', 'max:55'],
            "package_id" => ['required', 'string', 'max:1500']
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
