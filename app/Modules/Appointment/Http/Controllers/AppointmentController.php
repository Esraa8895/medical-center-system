<?php

namespace App\Modules\Appointment\Http\Controllers;

use App\Modules\Appointment\Services\AppointmentService;
use App\Modules\Appointment\Http\Requests\StoreAppointmentRequest;
use App\Modules\Appointment\Http\Requests\UpdateAppointmentRequest;
use App\Modules\Appointment\Http\Resources\AppointmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class AppointmentController extends Controller
{
    private AppointmentService $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $appointments = $this->service->getAll(
            request('search', ''),
            request('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => AppointmentResource::collection($appointments)
        ]);
    }

public function specialtyAppointments($specialtyId): JsonResponse
{
    $appointments = $this->service->getBySpecialty(
        $specialtyId,
        request('search'),
        request('date'),
        request('per_page', 15)
    );

    return response()->json([
        'success' => true,
        'data' => AppointmentResource::collection($appointments)
    ]);
}
    public function show($id): JsonResponse
    {
        $appointment = $this->service->getById($id);

        return response()->json([
            'success' => true,
            'data' => new AppointmentResource($appointment)
        ]);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointment = $this->service->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الموعد بنجاح',
            'data' => new AppointmentResource($appointment)
        ], 201);
    }

    public function update(UpdateAppointmentRequest $request, $id): JsonResponse
    {
        $appointment = $this->service->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل الموعد',
            'data' => new AppointmentResource($appointment)
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الموعد'
        ]);
    }
}
