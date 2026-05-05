<?php

namespace Modules\AdmissionRegistration\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionRegistration extends Model
{
    protected $fillable = [
        'patient_name',
        'patient_number',
        'department',
        'doctor',
        'admission_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];
}
