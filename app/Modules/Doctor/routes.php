<?php

use App\Modules\Doctor\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin|receptionist'])->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/doctors', [DoctorController::class, 'store']);
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update']);
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);
});
