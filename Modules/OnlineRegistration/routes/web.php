<?php

use Illuminate\Support\Facades\Route;
use Modules\OnlineRegistration\Http\Controllers\OnlineRegistrationController;

Route::get('/online-registration', [OnlineRegistrationController::class, 'create'])->name('online-registration.create');
Route::post('/online-registration', [OnlineRegistrationController::class, 'store'])->name('online-registration.store');
Route::get('/online-registration/verify', [OnlineRegistrationController::class, 'verifyForm'])->name('online-registration.verify.form');
Route::post('/online-registration/verify', [OnlineRegistrationController::class, 'verify'])->name('online-registration.verify');
Route::get('/online-registration/success', [OnlineRegistrationController::class, 'success'])->name('online-registration.success');
