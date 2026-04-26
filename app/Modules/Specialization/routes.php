<?php

use App\Modules\Specialization\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin|receptionist'])->group(function () {
    Route::get('/specialties', [SpecialtyController::class, 'index']);
    Route::get('/specialties/{specialty}', [SpecialtyController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/specialties', [SpecialtyController::class, 'store']);
    Route::put('/specialties/{specialty}', [SpecialtyController::class, 'update']);
    Route::delete('/specialties/{specialty}', [SpecialtyController::class, 'destroy']);
});
