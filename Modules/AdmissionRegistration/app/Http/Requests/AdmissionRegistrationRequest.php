<?php

namespace Modules\AdmissionRegistration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_number' => ['nullable', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:255'],
            'doctor' => ['nullable', 'string', 'max:255'],
            'admission_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,admitted,discharged'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'patient_name' => 'patient name',
            'patient_number' => 'patient number',
            'admission_date' => 'admission date',
        ];
    }
}
