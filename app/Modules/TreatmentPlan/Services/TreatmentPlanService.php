<?php

namespace App\Modules\TreatmentPlan\Services;

use App\Modules\TreatmentPlan\Models\TreatmentPlan;
use App\Modules\TreatmentPlan\Repositories\TreatmentPlanRepository;
use Illuminate\Support\Facades\DB;
class TreatmentPlanService
{
    public function __construct(
        private TreatmentPlanRepository $repo
    ) {}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getById(int $id): TreatmentPlan
    {
        return $this->repo->findById($id);
    }

    public function create(array $data): TreatmentPlan
    {
        $data['expected_total'] = $this->calcExpectedTotal($data);
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): TreatmentPlan
    {
        $plan = $this->repo->findById($id);
        if (isset($data['total_sessions']) || isset($data['service_id'])) {
            $data['expected_total'] = $this->calcExpectedTotal(array_merge($plan->toArray(), $data));
        }
        return $this->repo->update($plan, $data);
    }

    public function delete(int $id): void
    {
        $plan = $this->repo->findById($id);
        $this->repo->delete($plan);
    }
    private function calcExpectedTotal(array $data): float
    {
        if (empty($data['total_sessions']) || empty($data['service_id'])) {
            return 0;
        }
        $service = DB::table('services')->find($data['service_id']);
        if (!$service) return 0;
        return $service->price * $data['total_sessions'];
    }
}