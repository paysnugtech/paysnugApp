<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Interfaces\Repositories\IUserRepository;
use App\Models\V1\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{

    protected $userRepository;
    protected $id;

    
    public function __construct(Request $request, IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->id = $request->route()->user;
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $user = $this->user();
        $model = $this->userRepository->get($this->route('user'))->first();

        return $this->user()->can(
            'update', $model
        );
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
            'phone_no' => 'required|digits_between:11,15|unique:users,phone_no,'.$this->id,
            // 'email' => 'required|email|max:25|unique:users,email,'.$this->id,
            // 'password' => 'required|current_password',
            'dob' => 'required|date|before:'.$dt,
            'gender' => ['required', new Enum(Gender::class)],
            'profile_image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // 'country_id' => 'required|string|max:50|exists:countries,id',
            'street' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'postal_code' => 'sometimes|string|max:15',
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
