<?php

namespace Modules\MedicalRecords\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordVisitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'visit_date' => ['required', 'date'],
            'visit_type' => ['required', 'in:rawat_jalan,rawat_inap,igd,penunjang'],
            'department' => ['nullable', 'string', 'max:100'],
            'doctor' => ['nullable', 'string', 'max:100'],
            'chief_complaint' => ['nullable', 'string', 'max:255'],
            'diagnosis' => ['nullable', 'string', 'max:2000'],
            'icd10_code' => ['nullable', 'string', 'max:16'],
            'status' => ['nullable', 'in:active,closed'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
