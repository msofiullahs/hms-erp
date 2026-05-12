<?php

use Illuminate\Support\Facades\Route;
use Modules\SelfServiceQueue\Http\Controllers\SelfServiceQueueController;

Route::prefix('v1/selfservicequeue')->name('selfservicequeue.')->group(function () {
    Route::get('board', [SelfServiceQueueController::class, 'boardData'])->name('board');
});
