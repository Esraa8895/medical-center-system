<?php

use App\Modules\Service\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin|receptionist'])->group(function () {
    Route::get('/services',             [ServiceController::class, 'index']);
    Route::get('/services/search',      [ServiceController::class, 'search']);
    Route::get('/services/{id}',        [ServiceController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/services',            [ServiceController::class, 'store']);
    Route::put('/services/{id}',        [ServiceController::class, 'update']);
    Route::delete('/services/{id}',     [ServiceController::class, 'destroy']);
});

