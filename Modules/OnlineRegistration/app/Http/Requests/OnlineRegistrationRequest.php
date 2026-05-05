<?php

namespace Modules\OnlineRegistration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OnlineRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:255'],
            'visit_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'consent' => ['accepted'],
        ];
    }
}
