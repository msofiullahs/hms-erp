<?php

namespace Modules\ElectronicMedicalRecord\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEncounterDiagnosisRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'icd10_code' => ['required', 'string', 'max:16'],
            'description' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['primary', 'secondary', 'differential'])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
