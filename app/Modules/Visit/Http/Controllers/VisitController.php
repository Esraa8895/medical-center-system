<?php

namespace App\Modules\Visit\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\Visit\Http\Requests\StoreVisitRequest;
use App\Modules\Visit\Resources\VisitResource;
use App\Modules\Visit\Services\VisitService;

class VisitController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private VisitService $service) {}

    public function index()
    {
        $visits = $this->service->getAll();
        return $this->successResponse(
            VisitResource::collection($visits)->response()->getData(true)
        );
    }

    public function store(StoreVisitRequest $request)
    {
        $visit = $this->service->create($request->validated());
        return $this->successResponse(
            new VisitResource($visit),
            'تم إنشاء الزيارة بنجاح',
            201
        );
    }

    public function show(int $id)
    {
        $visit = $this->service->getById($id);
        return $this->successResponse(new VisitResource($visit));
    }
}