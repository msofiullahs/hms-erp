<?php

return [
    'name' => 'ElectronicMedicalRecord',
    'diagnosis_types' => [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'differential' => 'Differential',
    ],
    'prescription_routes' => [
        'po' => 'Oral (PO)',
        'iv' => 'Intravenous (IV)',
        'im' => 'Intramuscular (IM)',
        'sc' => 'Subcutaneous (SC)',
        'topical' => 'Topical',
        'inhaler' => 'Inhaler',
        'other' => 'Other',
    ],
    'attachment' => [
        'disk' => env('EMR_ATTACHMENT_DISK', 'local'),
        'directory' => 'emr-attachments',
        'max_size_kb' => 10240,
        'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'docx', 'xlsx'],
    ],
];
