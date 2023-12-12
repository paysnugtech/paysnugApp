<?php

namespace App\Http\Requests;

use App\Interfaces\Repositories\IBvnRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreBvnRequest extends FormRequest
{
    protected $bvnRepository;

    public function __construct(
        IBvnRepository $bvnRepository
    )
    {
        $this->bvnRepository = $bvnRepository;
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        
        $user = Auth::user();
        
        return $this->user()->can('create', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        $bvn_id = auth()->user()->verification->bvn->id;

        return [
            'bvn' => ['required', 'string', 'digits:11', 'unique:bvns,number,'.$bvn_id],
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
