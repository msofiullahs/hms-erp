<?php

namespace Modules\OnlineRegistration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnlineRegistrationOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration_id' => ['required', 'exists:online_registrations,id'],
            'otp_code' => ['required', 'string', 'size:6'],
        ];
    }
}
