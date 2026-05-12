<?php

namespace Modules\SelfServiceQueue\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSelfServiceQueueTicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['nullable', 'string', 'max:255'],
            'kiosk_id' => ['nullable', 'string', 'max:100'],
            'service_type' => ['nullable', 'string', 'max:100'],
            'counter' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'in:waiting,called,served,cancelled'],
            'metadata' => ['nullable', 'array'],
            'metadata.*' => ['nullable', 'string'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
