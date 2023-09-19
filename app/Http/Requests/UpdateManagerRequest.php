<?php

namespace App\Http\Requests;

use App\Interfaces\Repositories\IManagerRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateManagerRequest extends FormRequest
{

    protected $managerRepository;
    protected $id;


    public function __construct(Request $request, IManagerRepository $managerRepository)
    {
        $this->id = $request->route()->manager;

        $this->managerRepository = $managerRepository;
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
            'phone_no' => ['required', 'digits_between:11,15', Rule::unique('managers', 'phone_no')->ignore($this->id)],
            'email' => ['required', 'email', 'max:30', Rule::unique('managers', 'email')->ignore($this->id)],
            'whatsapp_no' => ['required', 'digits_between:11,15', Rule::unique('managers', 'whatsapp_no')->ignore($this->id)],
        ];
    }
    
    public function after(): array
    { 
        
        return [
            
            function(Validator $validator){

                $manager = $this->managerRepository->get($this->id);

                if(!$manager)
                {
                    $validator->errors()->add(
                        'id',
                        'Manager not found!'
                    );
                }
            }
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'failed',
            'massage' => 'Validation error!',
            'error' => $validator->errors(),
        ]));
    }
}
