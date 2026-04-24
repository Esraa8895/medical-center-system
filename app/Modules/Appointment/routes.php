<?php

use App\Modules\Appointment\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin|receptionist'])->group(function () {

    Route::prefix('appointments')->group(function () {

        Route::get('/', [AppointmentController::class, 'index']);
        Route::get('/{id}', [AppointmentController::class, 'show']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::put('/{id}', [AppointmentController::class, 'update']);
        Route::delete('/{id}', [AppointmentController::class, 'destroy']);

    });
      Route::get('doctors/{doctorId}/appointments', [AppointmentController::class, 'doctorAppointments']);
});
