<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateAddressRequest extends FormRequest
{

    protected $id;


    public function __construct(Request $request)
    {
        $this->id = $request->route()->manager;
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
            
            'first_name' => 'required|string|max:30',
            'other_name' => 'required|string|max:30',
            'phone_no' => 'required|digits:11|unique:managers,phone_no,'.$this->id,
            'email' => 'required|email|max:30|unique:managers',
            'whatsapp_no' => 'required|digits:11|unique:managers',
        ];
    }


    
    public function after(): array
    { 
        print_r($this->id);
        
        return [
            
            function(Validator $validator){

            }
        ];
    }




}
