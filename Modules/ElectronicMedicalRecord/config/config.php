<?php

return [
    'name' => 'ElectronicMedicalRecord',
    'diagnosis_types' => [
        'primary' => 'Diagnosis Utama',
        'secondary' => 'Diagnosis Sekunder',
        'differential' => 'Diagnosis Banding',
    ],
    'prescription_routes' => [
        'po' => 'Per Oral (PO)',
        'iv' => 'Intravena (IV)',
        'im' => 'Intramuskular (IM)',
        'sc' => 'Subkutan (SC)',
        'topical' => 'Topikal',
        'inhaler' => 'Inhaler',
        'other' => 'Lainnya',
    ],
    'attachment' => [
        'disk' => env('EMR_ATTACHMENT_DISK', 'local'),
        'directory' => 'emr-attachments',
        'max_size_kb' => 10240,
        'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'docx', 'xlsx'],
    ],
];
