<?php

use Illuminate\Support\Facades\Route;
use Modules\MedicalRecords\Http\Controllers\MedicalRecordController;

Route::middleware(['auth', 'verified'])->prefix('medical-records')->name('medicalrecords.')->group(function () {
    Route::get('/', [MedicalRecordController::class, 'index'])->name('index');
    Route::get('/reports', [MedicalRecordController::class, 'reports'])->name('reports');
    Route::get('/create', [MedicalRecordController::class, 'create'])->name('create');
    Route::post('/', [MedicalRecordController::class, 'store'])->name('store');
    Route::get('/{medicalrecord}', [MedicalRecordController::class, 'show'])->name('show');
    Route::get('/{medicalrecord}/edit', [MedicalRecordController::class, 'edit'])->name('edit');
    Route::put('/{medicalrecord}', [MedicalRecordController::class, 'update'])->name('update');
    Route::delete('/{medicalrecord}', [MedicalRecordController::class, 'destroy'])->name('destroy');

    Route::post('/{medicalrecord}/visits', [MedicalRecordController::class, 'storeVisit'])->name('visits.store');
    Route::post('/{medicalrecord}/visits/{visit}/close', [MedicalRecordController::class, 'closeVisit'])->name('visits.close');
});
