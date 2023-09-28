<?php

namespace App\Http\Requests;

use App\Enums\DevicePlatformEnum;
use App\Enums\DeviceTypeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreDeviceRequest extends FormRequest
{
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
            'email' => ['required', 'email', 'exists:users,email', 'max:255'],
            'token' => ['required', 'exists:device_verification_tokens,token'],
            'device_name' => ['required', 'string', 'min:3', 'max:255'],
            'device_id' => ['required', 'string', 'min:3', 'max:255', 'unique:devices,device_id'],
            'device_type' => ['required', new Enum(DeviceTypeEnum::class)],
            'platform' => ['required', new Enum(DevicePlatformEnum::class)],
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
}
