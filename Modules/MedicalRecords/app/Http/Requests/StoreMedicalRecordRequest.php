<?php

namespace Modules\MedicalRecords\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicalRecordRequest extends FormRequest
{
    public function rules(): array
    {
        $recordId = $this->route('medicalrecord')?->id;

        return [
            'nik' => ['nullable', 'string', 'max:32', Rule::unique('medical_records', 'nik')->ignore($recordId)->whereNull('deleted_at')],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:M,F,O'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'blood_type' => ['nullable', 'string', 'max:3'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:16'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'religion' => ['nullable', 'string', 'max:50'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'education' => ['nullable', 'string', 'max:100'],
            'insurance_type' => ['required', 'in:umum,bpjs,asuransi'],
            'bpjs_number' => ['nullable', 'string', 'max:32', 'required_if:insurance_type,bpjs'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:32'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
