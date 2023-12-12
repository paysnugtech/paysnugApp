<?php

namespace App\Http\Requests;

use App\Enums\AuthMethodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCableRequest extends FormRequest
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
            "auth" => ['required', 'string', 'max:255'],
            "auth_method" => ['required', new Enum(AuthMethodEnum::class)],
            "wallet_id" => ['required', 'string', 'max:255', 'exists:wallets,id'],
            "customer_id" => ['required', 'string', 'max:55'],
            "customer_name" => ['required', 'string', 'max:55'],
            "amount" => ['required', 'numeric', 'min:100'],
            "transaction_no" => ['required', 'string', 'max:55', 'unique:transactions,number'],
            "add_beneficiary" => ['sometimes', 'boolean'],
            "package_id" => ['required', 'string', 'max:1500']
        ];
    }
}
