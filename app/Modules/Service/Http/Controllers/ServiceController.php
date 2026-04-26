<?php

namespace App\Modules\Service\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\Service\Http\Requests\StoreServiceRequest;
use App\Modules\Service\Http\Requests\UpdateServiceRequest;
use App\Modules\Service\Http\Resources\ServiceResource;
use App\Modules\Service\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private ServiceService $service) {}

    public function index(Request $request)
    {
        $services = $this->service->getAll(
            $request->get('search', ''),
            (int) $request->get('per_page', 15)
        );

        return $this->successResponse(
            ServiceResource::collection($services)->response()->getData(true)
        );
    }

    public function store(StoreServiceRequest $request)
    {
        $service = $this->service->create($request->validated());

        return $this->successResponse(
            new ServiceResource($service),
            'تم إضافة الخدمة بنجاح',
            201
        );
    }

    public function show(int $id)
    {
        $service = $this->service->getById($id);

        return $this->successResponse(new ServiceResource($service));
    }

    public function update(UpdateServiceRequest $request, int $id)
    {
        $service = $this->service->update($id, $request->validated());

        return $this->successResponse(
            new ServiceResource($service),
            'تم تحديث الخدمة بنجاح'
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return $this->successResponse(null, 'تم حذف الخدمة بنجاح');
    }

    public function search(Request $request)
    {
        $services = $this->service->search(
            $request->get('search', ''),
            (int) $request->get('per_page', 15)
        );

        return $this->successResponse(
            ServiceResource::collection($services)->response()->getData(true)
        );
    }
}
