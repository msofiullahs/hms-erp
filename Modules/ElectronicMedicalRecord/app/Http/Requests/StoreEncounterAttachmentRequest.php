<?php

namespace Modules\ElectronicMedicalRecord\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEncounterAttachmentRequest extends FormRequest
{
    public function rules(): array
    {
        $maxKb = (int) config('electronicmedicalrecord.attachment.max_size_kb', 10240);
        $mimes = implode(',', config('electronicmedicalrecord.attachment.allowed_mimes', ['pdf', 'jpg', 'png']));

        return [
            'label' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', "max:$maxKb", "mimes:$mimes"],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
