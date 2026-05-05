<?php

namespace Modules\OnlineRegistration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'department',
        'visit_date',
        'notes',
        'otp_code',
        'otp_sent_at',
        'otp_expires_at',
        'verified_at',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'otp_sent_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }
}
