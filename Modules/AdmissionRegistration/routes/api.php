<?php

use Illuminate\Support\Facades\Route;
use Modules\AdmissionRegistration\Http\Controllers\AdmissionRegistrationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('admissionregistrations', AdmissionRegistrationController::class)->names('admissionregistration');
});
