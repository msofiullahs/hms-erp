<?php

namespace Modules\ElectronicMedicalRecord\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EncounterAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinical_encounter_id',
        'label',
        'disk',
        'file_path',
        'file_name',
        'mime_type',
        'size_bytes',
        'uploaded_by',
    ];

    protected static function booted(): void
    {
        static::deleting(function (EncounterAttachment $attachment) {
            if ($attachment->disk && $attachment->file_path) {
                Storage::disk($attachment->disk)->delete($attachment->file_path);
            }
        });
    }

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(ClinicalEncounter::class, 'clinical_encounter_id');
    }
}
