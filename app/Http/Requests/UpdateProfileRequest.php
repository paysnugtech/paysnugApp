<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Interfaces\Repositories\IProfileRepository;
use App\Interfaces\Repositories\IUserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class UpdateProfileRequest extends FormRequest
{
    
    protected $profileRepository;
    protected $userRepository;
    protected $user_id;

    
    public function __construct(IProfileRepository $profileRepository, IUserRepository $userRepository, Request $request)
    {
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
        $this->user_id = $request->route('id');
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $model = $this->profileRepository->getByUserId($this->user_id)->first();

        return $this->user()->can('update', $model);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $dt = now()->subYears(18)->toDateString();

        $id = $this->profileRepository->getByUserId($this->user_id)->first()->id;
        
        return [
            'first_name' => 'required|string|max:25',
            'other_name' => 'required|string|max:25',
            'phone_no' => ['required', 'digits_between:11,15', 'unique:profiles,phone_no,'.$id],
            'dob' => 'required|date|before:'.$dt,
            'gender' => ['required', new Enum(Gender::class)],
            'profile_image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'street' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'postal_code' => 'sometimes|string|max:15',
        ];
    }
}
