<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1/medical-records')->name('medicalrecords.')->group(function () {
    //
});
