<?php

namespace App\Modules\VisitService\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\VisitService\Http\Requests\StoreVisitServiceRequest;
use App\Modules\VisitService\Http\Requests\UpdateVisitServiceRequest;
use App\Modules\VisitService\Resources\VisitServiceResource;
use App\Modules\VisitService\Services\VisitServiceService;

class VisitServiceController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private VisitServiceService $service) {}

    public function store(StoreVisitServiceRequest $request)
    {
        $vs = $this->service->create($request->validated());
        return $this->successResponse(
            new VisitServiceResource($vs),
            'تم إضافة الخدمة للزيارة بنجاح',
            201
        );
    }

    public function getByVisit(int $visitId)
    {
        $services = $this->service->getByVisit($visitId);
        return $this->successResponse(VisitServiceResource::collection($services));
    }

    public function update(UpdateVisitServiceRequest $request, int $id)
    {
        $vs = $this->service->update($id, $request->validated());
        return $this->successResponse(
            new VisitServiceResource($vs),
            'تم تحديث الخدمة بنجاح'
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return $this->successResponse(null, 'تم حذف الخدمة بنجاح');
    }
}