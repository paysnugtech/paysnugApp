<?php

namespace App\Http\Requests;

use App\Interfaces\Repositories\IVerificationRepository;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBillRequest extends FormRequest
{

    use ResponseTrait;


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        return $this->user()->can('create', $user->verification);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        return [
            'bill' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'city' => ['required', 'string', 'min:3', 'max:255'],
            'country' => ['required', 'string', 'min:3', 'max:255'],
            'postal_code' => ['nullable', 'string', 'min:3', 'max:255'],
            'street' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse(
            422,
            'Validation error!',
            $validator->errors()
        ));
    }


    /* public function after(): array
    {
        return [
            function (Validator $validator) {

                $account_no = (int)$this->phone_no;
                $accountExist = $this->accountRepository->getByAccountNo($account_no)->first();
                
                if ($accountExist) {
                    $validator->errors()->add(
                        'account',
                        'Account number ('. $account_no .') already taken by another user!'
                    );
                }
            }
        ];
    } */
}
