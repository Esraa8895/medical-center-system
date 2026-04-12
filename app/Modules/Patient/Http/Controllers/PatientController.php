<?php

namespace App\Modules\Patient\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\Patient\Http\Requests\StorePatientRequest;
use App\Modules\Patient\Http\Requests\UpdatePatientRequest;
use App\Modules\Patient\Resources\PatientResource;
use App\Modules\Patient\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private PatientService $service) {}

    public function index(Request $request)
    {
        $patients = $this->service->getAll($request->get('search', ''));
        return $this->successResponse(
            PatientResource::collection($patients)->response()->getData(true)
        );
    }

    public function store(StorePatientRequest $request)
    {
        $patient = $this->service->create($request->validated());
        return $this->successResponse(
            new PatientResource($patient),
            'تم إضافة المريضة بنجاح',
            201
        );
    }

    public function show(int $id)
    {
        $patient = $this->service->getById($id);
        return $this->successResponse(new PatientResource($patient));
    }

    public function update(UpdatePatientRequest $request, int $id)
    {
        $patient = $this->service->update($id, $request->validated());
        return $this->successResponse(
            new PatientResource($patient),
            'تم تحديث بيانات المريضة بنجاح'
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return $this->successResponse(null, 'تم حذف المريضة بنجاح');
    }

    public function appointments(int $id)
    {
        $appointments = $this->service->getAppointments($id);
        return $this->successResponse($appointments);
    }

    public function visits(int $id)
    {
        $visits = $this->service->getVisits($id);
        return $this->successResponse($visits);
    }
}