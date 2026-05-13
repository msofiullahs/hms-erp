<?php

return [
    'name' => 'MedicalRecords',
    'mrn' => [
        'prefix' => 'RM',
    ],
    'visit_types' => [
        'rawat_jalan' => 'Outpatient',
        'rawat_inap' => 'Inpatient',
        'igd' => 'Emergency (ER)',
        'penunjang' => 'Ancillary',
    ],
    'insurance_types' => [
        'umum' => 'Self-pay',
        'bpjs' => 'BPJS',
        'asuransi' => 'Other Insurance',
    ],
];
