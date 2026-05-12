<?php

namespace Modules\SelfServiceQueue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSelfServiceQueueTicketRequest extends FormRequest
{
    public function rules(): array
    {
        $serviceTypeKeys = array_keys(config('selfservicequeue.service_types', []));

        return [
            'customer_name' => ['nullable', 'string', 'max:255'],
            'kiosk_id' => ['nullable', 'string', 'max:100'],
            'service_type' => ['required', 'string', Rule::in($serviceTypeKeys)],
            'metadata' => ['nullable', 'array'],
            'metadata.*' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
