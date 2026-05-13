<?php

use Illuminate\Support\Facades\Route;
use Modules\ElectronicMedicalRecord\Http\Controllers\ClinicalEncounterController;

Route::middleware(['auth', 'verified'])->prefix('emr')->name('emr.')->group(function () {
    Route::get('/', [ClinicalEncounterController::class, 'index'])->name('index');
    Route::get('/visits/{visit}', [ClinicalEncounterController::class, 'show'])->name('show');
    Route::put('/visits/{visit}', [ClinicalEncounterController::class, 'update'])->name('update');
    Route::post('/visits/{visit}/lock', [ClinicalEncounterController::class, 'lock'])->name('lock');
    Route::post('/visits/{visit}/unlock', [ClinicalEncounterController::class, 'unlock'])->name('unlock');

    Route::post('/encounters/{encounter}/diagnoses', [ClinicalEncounterController::class, 'storeDiagnosis'])->name('diagnoses.store');
    Route::delete('/encounters/{encounter}/diagnoses/{diagnosis}', [ClinicalEncounterController::class, 'destroyDiagnosis'])->name('diagnoses.destroy');

    Route::post('/encounters/{encounter}/prescriptions', [ClinicalEncounterController::class, 'storePrescription'])->name('prescriptions.store');
    Route::delete('/encounters/{encounter}/prescriptions/{prescription}', [ClinicalEncounterController::class, 'destroyPrescription'])->name('prescriptions.destroy');

    Route::post('/encounters/{encounter}/attachments', [ClinicalEncounterController::class, 'storeAttachment'])->name('attachments.store');
    Route::get('/encounters/{encounter}/attachments/{attachment}/download', [ClinicalEncounterController::class, 'downloadAttachment'])->name('attachments.download');
    Route::delete('/encounters/{encounter}/attachments/{attachment}', [ClinicalEncounterController::class, 'destroyAttachment'])->name('attachments.destroy');
});
