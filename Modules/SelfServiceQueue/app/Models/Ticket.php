<?php

namespace Modules\SelfServiceQueue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'customer_name',
        'kiosk_id',
        'service_type',
        'counter',
        'status',
        'qr_code',
        'barcode_data',
        'metadata',
        'issued_at',
        'called_at',
        'served_at',
        'expires_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'issued_at' => 'datetime',
        'called_at' => 'datetime',
        'served_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeCalled($query)
    {
        return $query->where('status', 'called');
    }

    public function scopeServed($query)
    {
        return $query->where('status', 'served');
    }

    public static function generateTicketNumber(string $serviceType = 'general'): string
    {
        $serviceTypes = config('selfservicequeue.service_types', []);
        $prefix = $serviceTypes[$serviceType]['prefix'] ?? strtoupper(substr($serviceType, 0, 1));

        $count = self::whereDate('created_at', now()->toDateString())
            ->where('service_type', $serviceType)
            ->count() + 1;

        return sprintf('%s-%03d', $prefix, $count);
    }

    public static function generateQrCode(string $ticketNumber): string
    {
        return sprintf('SSQ:%s', $ticketNumber);
    }

    public static function generateBarcodeData(string $ticketNumber): string
    {
        return sprintf('%s|%s', $ticketNumber, now()->timestamp);
    }
}
