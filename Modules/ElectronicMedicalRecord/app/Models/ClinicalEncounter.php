<?php

namespace Modules\ElectronicMedicalRecord\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\MedicalRecords\Models\MedicalRecordVisit;

class ClinicalEncounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_visit_id',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'systolic_bp',
        'diastolic_bp',
        'pulse',
        'respiratory_rate',
        'temperature',
        'spo2',
        'weight_kg',
        'height_cm',
        'bmi',
        'recorded_by',
        'locked_at',
    ];

    protected $casts = [
        'temperature' => 'decimal:1',
        'weight_kg' => 'decimal:2',
        'height_cm' => 'decimal:1',
        'bmi' => 'decimal:2',
        'locked_at' => 'datetime',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(MedicalRecordVisit::class, 'medical_record_visit_id');
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(EncounterDiagnosis::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(EncounterPrescription::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EncounterAttachment::class);
    }

    public function isLocked(): bool
    {
        return $this->locked_at !== null;
    }

    public function recomputeBmi(): void
    {
        $this->bmi = self::computeBmi($this->weight_kg, $this->height_cm);
    }

    public static function computeBmi(?float $weightKg, ?float $heightCm): ?float
    {
        if (! $weightKg || ! $heightCm) {
            return null;
        }
        $heightM = $heightCm / 100;
        if ($heightM <= 0) {
            return null;
        }

        return round($weightKg / ($heightM * $heightM), 2);
    }
}
