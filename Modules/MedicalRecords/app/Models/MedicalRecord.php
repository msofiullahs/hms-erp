<?php

namespace Modules\MedicalRecords\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'mrn',
        'nik',
        'name',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'blood_type',
        'address',
        'city',
        'province',
        'postal_code',
        'phone',
        'email',
        'religion',
        'marital_status',
        'occupation',
        'education',
        'insurance_type',
        'bpjs_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function visits(): HasMany
    {
        return $this->hasMany(MedicalRecordVisit::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    public static function generateMrn(): string
    {
        $year = now()->format('Y');
        $count = self::withTrashed()
            ->whereYear('created_at', $year)
            ->count() + 1;

        return sprintf('RM-%s-%06d', $year, $count);
    }

    public function nextVisitNumber(): string
    {
        $count = $this->visits()->count() + 1;

        return sprintf('%s/V%04d', $this->mrn, $count);
    }
}
