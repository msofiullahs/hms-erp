<?php

namespace Modules\MedicalRecords\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecordVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'visit_number',
        'visit_date',
        'visit_type',
        'department',
        'doctor',
        'chief_complaint',
        'diagnosis',
        'icd10_code',
        'status',
        'source',
        'source_id',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
