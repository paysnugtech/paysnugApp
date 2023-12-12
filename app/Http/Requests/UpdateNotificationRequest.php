<?php

namespace App\Http\Requests;

use App\Enums\EmailNotificationEnum;
use App\Enums\PushNotificationEnum;
use App\Enums\SmsNotificationEnum;
use App\Interfaces\Repositories\IUserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class UpdateNotificationRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user =  Auth::user();

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
            'is_email' => ['sometimes', new Enum(EmailNotificationEnum::class)],
            'is_push' => ['sometimes', new Enum(PushNotificationEnum::class)],
            'is_sms' => ['sometimes', new Enum(SmsNotificationEnum::class)],
        ];
    }
}
