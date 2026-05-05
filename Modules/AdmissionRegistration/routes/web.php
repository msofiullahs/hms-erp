<?php

use Illuminate\Support\Facades\Route;
use Modules\AdmissionRegistration\Http\Controllers\AdmissionRegistrationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('admissionregistrations', AdmissionRegistrationController::class)->names('admissionregistration');
});
