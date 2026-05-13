<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('v1/emr')->name('emr.')->group(function () {
    //
});
