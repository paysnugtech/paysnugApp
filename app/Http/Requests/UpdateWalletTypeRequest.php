<?php

namespace App\Http\Requests;

use App\Enums\WalletTypeStatusEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class UpdateWalletTypeRequest extends FormRequest
{

    protected $id;


    public function __construct(Request $request)
    {
        $this->id = $request->route()->type;
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
            'name' => ['required', 'string', 'between:3,50', 'unique:wallet_types,name,'. $this->id],
            'description' => ['sometimes', 'string', 'min:3', 'max:255'],
            'status' => ['required', new Enum(WalletTypeStatusEnum::class)]
        ];
    }

    

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'failed',
            'message' => 'Validation error!',
            'error' => $validator->errors(),
        ], 422));
    }
}
