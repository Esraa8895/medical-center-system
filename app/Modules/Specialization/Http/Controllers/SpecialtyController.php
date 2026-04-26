<?php

namespace App\Modules\Specialization\Http\Controllers;

use App\Modules\Specialization\Http\Requests\StoreSpecialtyRequest;
use App\Modules\Specialization\Http\Requests\UpdateSpecialtyRequest;
use App\Modules\Specialization\Http\Resources\SpecialtyResource;
use App\Modules\Specialization\Models\Specialty;
use App\Modules\Specialization\Services\SpecialtyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class SpecialtyController extends Controller
{
    private SpecialtyService $specialtyService;

    public function __construct(SpecialtyService $specialtyService)
    {
        $this->specialtyService = $specialtyService;
    }

    public function index(): JsonResponse
    {
        $specialties = $this->specialtyService->getAllSpecialties();
        return response()->json(SpecialtyResource::collection($specialties));
    }

    public function show(Specialty $specialty): JsonResponse
    {
        return response()->json(new SpecialtyResource($specialty));
    }

    public function store(StoreSpecialtyRequest $request): JsonResponse
    {
        $specialty = $this->specialtyService->createSpecialty($request->validated());
        return response()->json(new SpecialtyResource($specialty), 201);
    }

    public function update(UpdateSpecialtyRequest $request, Specialty $specialty): JsonResponse
    {
        $updatedSpecialty = $this->specialtyService->updateSpecialty($specialty, $request->validated());
        return response()->json(new SpecialtyResource($updatedSpecialty));
    }

    public function destroy(Specialty $specialty): JsonResponse
    {
        $this->specialtyService->deleteSpecialty($specialty);
        return response()->json(null, 204);
    }
}
