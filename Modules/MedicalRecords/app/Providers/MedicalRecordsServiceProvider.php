<?php

namespace Modules\MedicalRecords\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class MedicalRecordsServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'MedicalRecords';

    protected string $nameLower = 'medicalrecords';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
