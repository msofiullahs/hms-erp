<?php

namespace Modules\ElectronicMedicalRecord\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterPrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinical_encounter_id',
        'drug_name',
        'strength',
        'form',
        'dosage',
        'route',
        'frequency',
        'duration',
        'quantity',
        'instructions',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(ClinicalEncounter::class, 'clinical_encounter_id');
    }
}
