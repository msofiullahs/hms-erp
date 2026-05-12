<?php

use Illuminate\Support\Facades\Route;
use Modules\SelfServiceQueue\Http\Controllers\SelfServiceQueueController;

Route::prefix('kiosk')->name('selfservicequeue.kiosk.')->group(function () {
    Route::get('/', [SelfServiceQueueController::class, 'kiosk'])
        ->middleware('throttle:60,1')
        ->name('home');

    Route::post('/issue', [SelfServiceQueueController::class, 'issue'])
        ->middleware('throttle:30,1')
        ->name('issue');

    Route::get('/ticket/{ticket}', [SelfServiceQueueController::class, 'ticket'])
        ->name('ticket');
});

Route::get('/queue-board', [SelfServiceQueueController::class, 'board'])->name('selfservicequeue.board');

Route::middleware(['auth', 'verified'])->prefix('selfservicequeue')->name('selfservicequeue.')->group(function () {
    Route::get('/', [SelfServiceQueueController::class, 'index'])->name('index');
    Route::get('/{selfservicequeue}', [SelfServiceQueueController::class, 'show'])->name('show');
    Route::put('/{selfservicequeue}', [SelfServiceQueueController::class, 'update'])->name('update');
    Route::delete('/{selfservicequeue}', [SelfServiceQueueController::class, 'destroy'])->name('destroy');

    Route::post('/{selfservicequeue}/call', [SelfServiceQueueController::class, 'call'])->name('call');
    Route::post('/{selfservicequeue}/serve', [SelfServiceQueueController::class, 'serve'])->name('serve');
    Route::post('/{selfservicequeue}/cancel', [SelfServiceQueueController::class, 'cancel'])->name('cancel');
});
