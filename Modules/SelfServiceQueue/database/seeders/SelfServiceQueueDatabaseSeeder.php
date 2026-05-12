<?php

namespace Modules\SelfServiceQueue\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SelfServiceQueue\Models\Ticket;

class SelfServiceQueueDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $serviceTypes = array_keys(config('selfservicequeue.service_types', ['general' => []]));

        $sampleStatuses = [
            ['status' => 'served', 'called_offset' => 30, 'served_offset' => 25],
            ['status' => 'called', 'called_offset' => 2, 'served_offset' => null],
            ['status' => 'waiting', 'called_offset' => null, 'served_offset' => null],
            ['status' => 'waiting', 'called_offset' => null, 'served_offset' => null],
        ];

        foreach ($serviceTypes as $type) {
            foreach ($sampleStatuses as $sample) {
                $number = Ticket::generateTicketNumber($type);

                Ticket::create([
                    'ticket_number' => $number,
                    'kiosk_id' => 'KIOSK-01',
                    'service_type' => $type,
                    'status' => $sample['status'],
                    'counter' => $sample['status'] === 'waiting' ? null : '1',
                    'issued_at' => now()->subMinutes(45),
                    'called_at' => $sample['called_offset'] !== null ? now()->subMinutes($sample['called_offset']) : null,
                    'served_at' => $sample['served_offset'] !== null ? now()->subMinutes($sample['served_offset']) : null,
                    'expires_at' => now()->addHours(6),
                    'qr_code' => Ticket::generateQrCode($number),
                    'barcode_data' => Ticket::generateBarcodeData($number),
                ]);
            }
        }
    }
}
