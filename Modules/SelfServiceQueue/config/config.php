<?php

return [
    'name' => 'SelfServiceQueue',
    'queue' => [
        'default_service_type' => 'general',
        'ticket_prefix' => 'SSQ',
        'statuses' => [
            'waiting',
            'called',
            'served',
            'cancelled',
        ],
        'expires_after_hours' => 6,
    ],
    'service_types' => [
        'general' => [
            'label' => 'General Registration',
            'prefix' => 'A',
            'description' => 'General patient registration counter.',
        ],
        'bpjs' => [
            'label' => 'BPJS Insurance',
            'prefix' => 'B',
            'description' => 'Dedicated counter for BPJS patients.',
        ],
        'pharmacy' => [
            'label' => 'Pharmacy',
            'prefix' => 'F',
            'description' => 'Medication and prescription pickup.',
        ],
        'lab' => [
            'label' => 'Laboratory',
            'prefix' => 'L',
            'description' => 'Laboratory examinations.',
        ],
        'radiology' => [
            'label' => 'Radiology',
            'prefix' => 'R',
            'description' => 'Radiology examinations.',
        ],
        'cashier' => [
            'label' => 'Cashier',
            'prefix' => 'K',
            'description' => 'Payment and administration.',
        ],
    ],
    'board' => [
        'refresh_seconds' => 5,
        'recent_called_limit' => 5,
        'waiting_preview_limit' => 10,
    ],
];
