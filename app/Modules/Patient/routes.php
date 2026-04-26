<?php

use App\Modules\Patient\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum', 'role:admin|receptionist')->group(function () {
    Route::prefix('patients')->group(function () {
        Route::get('/', [PatientController::class, 'index']);
        Route::post('/', [PatientController::class, 'store']);
        Route::get('/{id}', [PatientController::class, 'show']);
        Route::put('/{id}', [PatientController::class, 'update']);
        Route::delete('/{id}', [PatientController::class, 'destroy']);
        Route::get('/{id}/appointments', [PatientController::class, 'appointments']);
        Route::get('/{id}/visits', [PatientController::class, 'visits']);
    });
});
