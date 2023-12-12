<?php

namespace App\Http\Requests;

use App\Enums\DevicePlatformEnum;
use App\Enums\DeviceTypeEnum;
use App\Enums\Gender;
use App\Interfaces\Repositories\IAccountRepository;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{

    use ResponseTrait;

    protected $accountRepository;

    public function __construct(IAccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
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
        $dt = now()->subYears(18)->toDateString();
        
        return [
            'first_name' => 'required|string|max:25',
            'other_name' => 'required|string|max:25',
            'phone_no' => 'required|digits_between:11,15|unique:profiles',
            'email' => 'required|email|max:25|unique:users',
            'password' => 'required|string|min:8|max:15',
            'gender' => ['required', new Enum(Gender::class)],
            'dob' => ['required', 'date', 'before:'.$dt],
            'country_id' => 'required|string|min:3|max:50|exists:countries,id',
            'pin' => ['required', 'numeric', 'digits:4'],
            'token_signature' => ['required', 'string', 'max:1000'],
            "device_name" => ['required', 'string', 'max:50'],
            "device_id" => ['required', 'string', 'max:255'],
            "device_type" => ['required', new Enum(DeviceTypeEnum::class)],
            "platform"  => ['required', new Enum(DevicePlatformEnum::class)]
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
