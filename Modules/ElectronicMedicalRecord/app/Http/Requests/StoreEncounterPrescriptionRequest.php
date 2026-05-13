<?php

namespace Modules\ElectronicMedicalRecord\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEncounterPrescriptionRequest extends FormRequest
{
    public function rules(): array
    {
        $routeKeys = array_keys(config('electronicmedicalrecord.prescription_routes', []));

        return [
            'drug_name' => ['required', 'string', 'max:255'],
            'strength' => ['nullable', 'string', 'max:64'],
            'form' => ['nullable', 'string', 'max:64'],
            'dosage' => ['required', 'string', 'max:64'],
            'route' => ['nullable', Rule::in($routeKeys)],
            'frequency' => ['required', 'string', 'max:64'],
            'duration' => ['nullable', 'string', 'max:64'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'instructions' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
