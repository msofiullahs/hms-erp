<?php

namespace Modules\MedicalRecords\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MedicalRecords\Models\MedicalRecord;

class MedicalRecordsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $samples = [
            [
                'name' => 'Andi Saputra',
                'gender' => 'M',
                'date_of_birth' => '1985-03-12',
                'place_of_birth' => 'Surabaya',
                'blood_type' => 'O+',
                'phone' => '081234567890',
                'city' => 'Malang',
                'province' => 'Jawa Timur',
                'insurance_type' => 'bpjs',
                'bpjs_number' => '0001234567890',
            ],
            [
                'name' => 'Siti Rahmawati',
                'gender' => 'F',
                'date_of_birth' => '1992-07-25',
                'place_of_birth' => 'Malang',
                'blood_type' => 'A+',
                'phone' => '081298765432',
                'city' => 'Malang',
                'province' => 'Jawa Timur',
                'insurance_type' => 'umum',
            ],
            [
                'name' => 'Budi Hartono',
                'gender' => 'M',
                'date_of_birth' => '1978-11-03',
                'blood_type' => 'B+',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'insurance_type' => 'asuransi',
            ],
        ];

        foreach ($samples as $data) {
            $record = MedicalRecord::create(array_merge($data, [
                'mrn' => MedicalRecord::generateMrn(),
            ]));

            $record->visits()->create([
                'visit_number' => $record->nextVisitNumber(),
                'visit_date' => now()->subDays(rand(1, 10)),
                'visit_type' => 'rawat_jalan',
                'department' => 'Poli Umum',
                'doctor' => 'dr. Hidayat',
                'chief_complaint' => 'Demam dan batuk',
                'status' => 'closed',
            ]);
        }
    }
}
