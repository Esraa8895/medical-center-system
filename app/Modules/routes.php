<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/me',      [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ─── Patients ─────────────────────────────────────────────────────────────────
use App\Modules\Patient\Http\Controllers\PatientController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/patients',                   [PatientController::class, 'index']);
    Route::post('/patients',                  [PatientController::class, 'store']);
    Route::get('/patients/{id}',              [PatientController::class, 'show']);
    Route::put('/patients/{id}',              [PatientController::class, 'update']);
    Route::delete('/patients/{id}',           [PatientController::class, 'destroy']);
    Route::get('/patients/{id}/appointments', [PatientController::class, 'appointments']);
    Route::get('/patients/{id}/visits',       [PatientController::class, 'visits']);
});

// ─── Treatment Plans ──────────────────────────────────────────────────────────
use App\Modules\TreatmentPlan\Http\Controllers\TreatmentPlanController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/treatment-plans',         [TreatmentPlanController::class, 'index']);
    Route::post('/treatment-plans',        [TreatmentPlanController::class, 'store']);
    Route::get('/treatment-plans/{id}',    [TreatmentPlanController::class, 'show']);
    Route::put('/treatment-plans/{id}',    [TreatmentPlanController::class, 'update']);
    Route::delete('/treatment-plans/{id}', [TreatmentPlanController::class, 'destroy']);
});

// ─── Visits + Visit Services ──────────────────────────────────────────────────
use App\Modules\Visit\Http\Controllers\VisitController;
use App\Modules\VisitService\Http\Controllers\VisitServiceController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/visits',      [VisitController::class, 'index']);
    Route::post('/visits',     [VisitController::class, 'store']);
    Route::get('/visits/{id}', [VisitController::class, 'show']);

    Route::post('/visit-services',            [VisitServiceController::class, 'store']);
    Route::get('/visit-services/{visitId}',   [VisitServiceController::class, 'getByVisit']);
    Route::put('/visit-services/{id}',        [VisitServiceController::class, 'update']);
    Route::delete('/visit-services/{id}',     [VisitServiceController::class, 'destroy']);
});

// ─── Payments ─────────────────────────────────────────────────────────────────
use App\Modules\Payment\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/payments',  [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
});

// ─── Reports (Admin only) ─────────────────────────────────────────────────────
use App\Modules\Reports\Http\Controllers\ReportController;

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('reports')->group(function () {
    Route::get('/financial',       [ReportController::class, 'financial']);
    Route::get('/clinic-profit',   [ReportController::class, 'clinicProfit']);
    Route::get('/patient/{id}',    [ReportController::class, 'patient']);
    Route::get('/doctor/{id}',     [ReportController::class, 'doctor']);
});
