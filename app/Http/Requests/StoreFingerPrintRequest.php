<?php

namespace App\Http\Requests;

use App\Interfaces\Repositories\IUserRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFingerPrintRequest extends FormRequest
{
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->userRepository->get($this->user)->first();

        return $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        return [
            'finger_print' => ['required', 'string', 'digits:6'],
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'failed',
            'message' => 'Validation error!',
            'error' => $validator->errors(),
        ],
        422));
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
