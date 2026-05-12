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
            'label' => 'Pendaftaran Umum',
            'prefix' => 'A',
            'description' => 'Loket pendaftaran pasien umum.',
        ],
        'bpjs' => [
            'label' => 'BPJS Kesehatan',
            'prefix' => 'B',
            'description' => 'Loket khusus pasien BPJS.',
        ],
        'pharmacy' => [
            'label' => 'Farmasi',
            'prefix' => 'F',
            'description' => 'Pengambilan obat dan resep.',
        ],
        'lab' => [
            'label' => 'Laboratorium',
            'prefix' => 'L',
            'description' => 'Pemeriksaan laboratorium.',
        ],
        'radiology' => [
            'label' => 'Radiologi',
            'prefix' => 'R',
            'description' => 'Pemeriksaan radiologi.',
        ],
        'cashier' => [
            'label' => 'Kasir',
            'prefix' => 'K',
            'description' => 'Pembayaran dan administrasi.',
        ],
    ],
    'board' => [
        'refresh_seconds' => 5,
        'recent_called_limit' => 5,
        'waiting_preview_limit' => 10,
    ],
];
