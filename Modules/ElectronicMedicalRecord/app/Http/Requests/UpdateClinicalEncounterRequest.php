<?php

namespace Modules\ElectronicMedicalRecord\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicalEncounterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subjective' => ['nullable', 'string', 'max:10000'],
            'objective' => ['nullable', 'string', 'max:10000'],
            'assessment' => ['nullable', 'string', 'max:10000'],
            'plan' => ['nullable', 'string', 'max:10000'],

            'systolic_bp' => ['nullable', 'integer', 'between:0,300'],
            'diastolic_bp' => ['nullable', 'integer', 'between:0,250'],
            'pulse' => ['nullable', 'integer', 'between:0,300'],
            'respiratory_rate' => ['nullable', 'integer', 'between:0,80'],
            'temperature' => ['nullable', 'numeric', 'between:20,45'],
            'spo2' => ['nullable', 'integer', 'between:0,100'],
            'weight_kg' => ['nullable', 'numeric', 'between:0,500'],
            'height_cm' => ['nullable', 'numeric', 'between:0,250'],

            'recorded_by' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
