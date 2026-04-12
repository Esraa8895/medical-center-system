<?php

namespace App\Modules\TreatmentPlan\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\TreatmentPlan\Http\Requests\StoreTreatmentPlanRequest;
use App\Modules\TreatmentPlan\Http\Requests\UpdateTreatmentPlanRequest;
use App\Modules\TreatmentPlan\Resources\TreatmentPlanResource;
use App\Modules\TreatmentPlan\Services\TreatmentPlanService;

class TreatmentPlanController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private TreatmentPlanService $service) {}

    public function index()
    {
        $plans = $this->service->getAll();
        return $this->successResponse(
            TreatmentPlanResource::collection($plans)->response()->getData(true)
        );
    }

    public function store(StoreTreatmentPlanRequest $request)
    {
        $plan = $this->service->create($request->validated());
        return $this->successResponse(
            new TreatmentPlanResource($plan),
            'تم إنشاء خطة العلاج بنجاح',
            201
        );
    }

    public function show(int $id)
    {
        $plan = $this->service->getById($id);
        return $this->successResponse(new TreatmentPlanResource($plan));
    }

    public function update(UpdateTreatmentPlanRequest $request, int $id)
    {
        $plan = $this->service->update($id, $request->validated());
        return $this->successResponse(
            new TreatmentPlanResource($plan),
            'تم تحديث خطة العلاج بنجاح'
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return $this->successResponse(null, 'تم حذف خطة العلاج بنجاح');
    }
}