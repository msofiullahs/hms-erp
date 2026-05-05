<?php

namespace Modules\AdmissionRegistration\database\seeders;

use Illuminate\Database\Seeder;
use Modules\AdmissionRegistration\Models\AdmissionRegistration;

class AdmissionRegistrationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdmissionRegistration::create([
            'patient_name' => 'John Doe',
            'patient_number' => 'P-00123',
            'department' => 'Emergency',
            'doctor' => 'Dr. Putra',
            'admission_date' => now()->subDays(1)->toDateString(),
            'status' => 'admitted',
            'notes' => 'Patient admitted for observation and initial treatment.',
        ]);

        AdmissionRegistration::create([
            'patient_name' => 'Siti Aisyah',
            'patient_number' => 'P-00124',
            'department' => 'Outpatient',
            'doctor' => 'Dr. Dewi',
            'admission_date' => now()->toDateString(),
            'status' => 'pending',
            'notes' => 'Online registration completed, awaiting document verification.',
        ]);
    }
}
