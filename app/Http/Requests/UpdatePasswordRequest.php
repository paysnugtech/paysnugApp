<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{

    protected $id;

    
    public function __construct(Request $request)
    {
        $this->id = $request->route()->password;
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
            'password' => [
                'required', 
                'string', 
                'max:15', 
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                // ->uncompromised()
            ],
            'current_password' => 'required|current_password',
        ];
    }
    
    
    
    public function after(): array
    {
        
        
        return [
            function(Validator $validator){

                if($this->id != Auth::id())
                {
                    $validator->errors()->add(
                        'id',
                        'Invalid user Id'
                    );
                }
                
                if($this->password == $this->current_password)
                {
                    $validator->errors()->add(
                        'password',
                        'Current password and new password cannot be same!'
                    );
                }

            }
        ];
    }


    public function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'error' => $validator->errors(),
        ],422));
    }
}
