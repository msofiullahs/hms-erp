<?php

namespace Modules\ElectronicMedicalRecord\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class ElectronicMedicalRecordServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'ElectronicMedicalRecord';

    protected string $nameLower = 'electronicmedicalrecord';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
