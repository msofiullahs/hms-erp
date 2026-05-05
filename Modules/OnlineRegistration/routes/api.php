<?php

use Illuminate\Support\Facades\Route;
use Modules\OnlineRegistration\Http\Controllers\OnlineRegistrationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('onlineregistrations', OnlineRegistrationController::class)->names('onlineregistration');
});
