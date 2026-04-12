<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (){
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function (){
  Route::get('/me',[AuthController::class,'me']);
  Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
});
 
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
use App\Modules\TreatmentPlan\Http\Controllers\TreatmentPlanController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/treatment-plans',       [TreatmentPlanController::class, 'index']);
    Route::post('/treatment-plans',      [TreatmentPlanController::class, 'store']);
    Route::get('/treatment-plans/{id}',  [TreatmentPlanController::class, 'show']);
    Route::put('/treatment-plans/{id}',  [TreatmentPlanController::class, 'update']);
    Route::delete('/treatment-plans/{id}', [TreatmentPlanController::class, 'destroy']);
});
use App\Modules\Visit\Http\Controllers\VisitController;
use App\Modules\VisitService\Http\Controllers\VisitServiceController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/visits',              [VisitController::class, 'index']);
    Route::post('/visits',             [VisitController::class, 'store']);
    Route::get('/visits/{id}',         [VisitController::class, 'show']);

    Route::post('/visit-services',                      [VisitServiceController::class, 'store']);
    Route::get('/visit-services/{visitId}',             [VisitServiceController::class, 'getByVisit']);
    Route::put('/visit-services/{id}',                  [VisitServiceController::class, 'update']);
    Route::delete('/visit-services/{id}',               [VisitServiceController::class, 'destroy']);
});