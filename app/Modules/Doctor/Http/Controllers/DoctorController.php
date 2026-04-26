<?php

namespace App\Modules\Doctor\Http\Controllers;

use App\Modules\Doctor\Http\Requests\StoreDoctorRequest;
use App\Modules\Doctor\Http\Requests\UpdateDoctorRequest;
use App\Modules\Doctor\Http\Resources\DoctorResource;
use App\Modules\Doctor\Models\Doctor;
use App\Modules\Doctor\Services\DoctorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DoctorController extends Controller
{
    private DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function index(): JsonResponse
    {
        $doctors = $this->doctorService->getAllDoctors();
        return response()->json(DoctorResource::collection($doctors));
    }

    public function show(Doctor $doctor): JsonResponse
    {
        return response()->json(new DoctorResource($doctor->load('specialty')));
    }

    public function store(StoreDoctorRequest $request): JsonResponse
    {
        $doctor = $this->doctorService->createDoctor($request->validated());
        return response()->json(new DoctorResource($doctor), 201);
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor): JsonResponse
    {
        $updatedDoctor = $this->doctorService->updateDoctor($doctor, $request->validated());
        return response()->json(new DoctorResource($updatedDoctor));
    }

    public function destroy(Doctor $doctor): JsonResponse
    {
        $this->doctorService->deleteDoctor($doctor);
        return response()->json(null, 204);
    }
}
